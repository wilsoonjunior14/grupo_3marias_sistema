<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Utils\ErrorMessage;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class PurchaseOrderItemBusiness {

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de item $id ordem de compra.");
        $item = (new PurchaseOrderItem())->getById(id: $id);
        if (is_null($item)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Item de Ordem de Compra"));
        }
        Logger::info("Finalizando a recuperação de item $id ordem de compra.");
        return $item;
    }

    public function delete(int $id) {
        Logger::info("Deletando um item $id da ordem de compra.");
        $purchaseItem = $this->getById(id: $id);
        $purchaseItem->deleted = true;
        $purchaseItem->save();
        Logger::info("Finalizando Deleção de um item $id da ordem de compra.");
        return $purchaseItem;
    }

    public function deleteByPurchaseId(int $id) {
        Logger::info("Deletando itens da ordem de compra $id.");
        $items = (new PurchaseOrderItem())->getByPurchaseOrder(id: $id);
        foreach ($items as $item) {
            $this->delete(id: $item->id);
        }
        Logger::info("Finalizando deleção de itens da ordem de compra $id.");
    }

}