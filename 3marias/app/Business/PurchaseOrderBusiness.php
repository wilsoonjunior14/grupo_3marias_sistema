<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class PurchaseOrderBusiness {

    // TODO: replace this function getting the value from database.
    private function getTotal($items) {
        $total = 0;
        foreach ($items as $item) {
            $total += $item->value * $item->quantity;
        }
        return str_replace(".", ",", "$total");
    }

    private function setStatusIcon(PurchaseOrder $purchase) {
        if ($purchase->status === 0) {
            $purchase["icon"] = "access_time";
            $purchase["icon_color"] = "gray";
        }
        if ($purchase->status === 1) {
            $purchase["icon"] = "thumb_down";
            $purchase["icon_color"] = "red";
        }
        if ($purchase->status === 2) {
            $purchase["icon"] = "thumb_up";
            $purchase["icon_color"] = "green";
        }
    }

    public function get() {
        Logger::info("Iniciando a recuperação de ordens de compra.");
        $purchases = (new PurchaseOrder())->getAll("date");
        $amount = count($purchases);
        foreach ($purchases as $purchase) {
            $purchase->items = (new PurchaseOrderItemBusiness())->getByPurchaseId(id: $purchase->id);
            $purchase->value = $this->getTotal(items: $purchase->items);
            $this->setStatusIcon(purchase: $purchase);
        }
        Logger::info("Foram recuperados {$amount} ordens de compra.");
        Logger::info("Finalizando a recuperação de ordens de compra.");
        return $purchases;
    }

    public function getById(int $id, bool $mergeFields = false) {
        Logger::info("Iniciando a recuperação de ordem de compra $id.");
        try {
            $purchase = (new PurchaseOrder())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Compra"));
        }
        if ($mergeFields) {
            Logger::info("Capturando os itens da ordem de compra $id.");
            $purchase->items = (new PurchaseOrderItem())->getByPurchaseOrder(id: $purchase->id);
        }
        Logger::info("Finalizando a recuperação de ordem de compra $id.");
        return $purchase;
    }

    public function delete(int $id) {
        $purchase = $this->getById(id: $id);
        $bills = (new BillPayBusiness())->getBillsByPurchase(purchaseId: $id);
        if (count($bills) > 0) {
            throw new InputValidationException("Operação não permitida. Existem contas a pagar dessa ordem de compra.");
        }
        Logger::info("Deletando a ordem de compra $id.");
        $purchase->deleted = true;
        $purchase->save();

        Logger::info("Deletando itens da ordem de compra $id.");
        (new PurchaseOrderItemBusiness())->deleteByPurchaseId(id: $id);
        return $purchase;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de ordem de compra.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();
        $data["status"] = 0; // Waiting Status
        $data["cost_center_id"] = 1; // The MATRIZ Cost Center
        // Input Validation
        $this->validateIV(data: $data);
        
        Logger::info("Salvando a nova ordem de compra.");
        $purchase = new PurchaseOrder($data);
        $purchase->save();

        Logger::info("Salvando os itens da ordem de compra.");
        foreach ($data["products"] as $product) {
            $product["purchase_order_id"] = $purchase->id;
            $purchaseItem = new PurchaseOrderItem($product);
            $purchaseItem->save();
        }

        Logger::info("Finalizando a atualização de ordem de compra.");
        return $purchase;
    }

    public function update(int $id, Request $request) {
        Logger::info("Iniciando a atualização de ordem de compra.");
        $purchaseOrder = $this->getById(id: $id);
        if ($purchaseOrder->status !== 0) {
            throw new InputValidationException("Não é possível atualizar ordem de compra cancelada ou aprovada.");
        }

        // Input Validation
        $data = $request->all();
        $data["status"] = $purchaseOrder->status;
        $data["cost_center_id"] = $purchaseOrder->cost_center_id;
        $this->validateIV(data: $data);

        // Removing the old purchase items
        Logger::info("Excluindo antigos itens da ordem de compra.");
        (new PurchaseOrderItemBusiness())->deleteByPurchaseId(id: $id);

        Logger::info("Salvando ordem de compra.");
        $purchaseOrder = UpdateUtils::updateFields(PurchaseOrder::$fieldsToBeUpdated, $purchaseOrder, $data);
        $purchaseOrder->save();

        Logger::info("Salvando os itens da ordem de compra.");
        foreach ($data["products"] as $product) {
            $product["purchase_order_id"] = $id;
            $purchaseItem = new PurchaseOrderItem($product);
            $purchaseItem->save();
        }
        return $purchaseOrder;
    }

    private function validateIV(array $data) {
        Logger::info("Validando as informações fornecidas.");
        // Validating the purchase order 
        $purchaseOrderValidator = new ModelValidator(PurchaseOrder::$rules, PurchaseOrder::$rulesMessages);
        $validation = $purchaseOrderValidator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }
        (new PartnerBusiness())->getById(id: $data["partner_id"]);

        // Validating the purchase order items
        if (!isset($data["products"]) || empty($data["products"]) || !is_array($data["products"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Produtos"));
        }
        foreach ($data["products"] as $product) {
            $product["purchase_order_id"] = 1; // Using 1 only for validate the purchase order item
            $purchaseOrderItemValidator = new ModelValidator(PurchaseOrderItem::$rules, PurchaseOrderItem::$rulesMessages);
            $itemValidation = $purchaseOrderItemValidator->validate($product);
            if (!is_null($itemValidation)) {
                throw new InputValidationException($itemValidation);
            }
            (new ProductBusiness())->getById(id: $product["product_id"]);
        }
    }

    public function reject(int $id) {
        Logger::info("Rejeitando uma ordem de compra $id.");
        $purchase = $this->getById(id: $id, mergeFields: false);
        if ($purchase->status !== 0) {
            throw new InputValidationException("Status da Ordem de Compra não pode ser modificada.");
        }
        $purchase->status = 1;
        $purchase->save();
        Logger::info("Finalizando rejeição de ordem de compra $id.");
        return $purchase;
    }

    public function approve(int $id) {
        Logger::info("Aprovando uma ordem de compra $id.");
        $purchase = $this->getById(id: $id, mergeFields: false);
        if ($purchase->status !== 0) {
            throw new InputValidationException("Status da Ordem de Compra não pode ser modificada.");
        }
        $purchase->status = 2;
        $purchase->save();

        // Updating the items of the Matriz Cost Center
        $purchaseOrderItemBusiness = new PurchaseOrderItemBusiness();
        $stockBusiness = new StockBusiness();
        $purchaseItems = $purchaseOrderItemBusiness->getByPurchaseId(id: $purchase->id);
        $stockMatriz = $stockBusiness->getById(id: 1, mergeFields: true);
        foreach ($purchaseItems as $purchaseItem) {
            $stockBusiness->refreshStockItem(productId: $purchaseItem->product_id, value: $purchaseItem->value,
            quantity: $purchaseItem->quantity, stock: $stockMatriz);
        }
        // Creates the bill to pay
        (new BillPayBusiness())->createBillPay(baseModel: $purchase);
        
        Logger::info("Finalizando aprovação de ordem de compra $id.");
        return $purchase;
    }

}