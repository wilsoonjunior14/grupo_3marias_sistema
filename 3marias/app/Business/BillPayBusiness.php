<?php

namespace App\Business;

use App\Models\BaseModel;
use App\Models\BillPay;
use App\Models\Logger;
use App\Models\PurchaseOrder;
use App\Models\ServiceOrder;
use App\Utils\UpdateUtils;

class BillPayBusiness {

    public function getAll() {
        Logger::info("Iniciando a recuperação dos pagamentos.");
        $bills = (new BillPay())->getAll("created_at");
        Logger::info("Finalizando a recuperação dos pagamentos.");
        return $bills;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação de contas a pagar.");
        Logger::info("Salvando e contas a pagar.");
        $payment = new BillPay($data);
        $payment->save();
        Logger::info("Finalizando a atualização de contas a pagar.");
        return $payment;
    }

    public function getBillsByService(int $serviceId) {
        Logger::info("Iniciando a recuperação dos pagamentos.");
        $bills = (new BillPay())->getByServiceId(id: $serviceId);
        Logger::info("Finalizando a recuperação dos pagamentos.");
        return $bills;
    }

    public function getBillsByPurchase(int $purchaseId) {
        Logger::info("Iniciando a recuperação dos pagamentos.");
        $bills = (new BillPay())->getByPurchaseId(id: $purchaseId);
        Logger::info("Finalizando a recuperação dos pagamentos.");
        return $bills;
    }

    public function createBillPay(BaseModel $baseModel) {
        if ($baseModel instanceof ServiceOrder) {
            $this->createBillPayByServiceOrder(serviceOrder: $baseModel);
        }
        if ($baseModel instanceof PurchaseOrder) {
            $this->createBillPayByPurchaseOrder(purchaseOrder: $baseModel);
        }
    }

    private function createBillPayByPurchaseOrder(PurchaseOrder $purchaseOrder) : void {
        $purchasedItems = (new PurchaseOrderItemBusiness())->getByPurchaseId(id: $purchaseOrder->id); // TODO: must get only total value here.
        $totalValue = 0;
        foreach ($purchasedItems as $item) {
            $totalValue += $item->quantity * $item->value;
        }

        $payload = [
            "code" => UpdateUtils::generateCode(count($this->getAll())) . "3MCP",
            "value" => $totalValue,
            "value_performed" => 0,
            "description" => $purchaseOrder->description,
            "status" => 0,
            "purchase_orders_id" => $purchaseOrder->id
        ];
        $this->create(data: $payload);
    }

    private function createBillPayByServiceOrder(ServiceOrder $serviceOrder) : void {
        if (!($serviceOrder->status === 2)) {
            return;
        }
        $payload = [
            "code" => UpdateUtils::generateCode(count($this->getAll())) . "3MCP",
            "value" => $serviceOrder->quantity * $serviceOrder->value,
            "value_performed" => 0,
            "description" => $serviceOrder->description,
            "status" => 0,
            "service_orders_id" => $serviceOrder->id
        ];
        $this->create(data: $payload);
    }
}
