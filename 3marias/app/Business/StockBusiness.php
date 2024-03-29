<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Stock;
use App\Models\Logger;
use App\Models\PurchaseOrderItem;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;

class StockBusiness {

    public function getById(int $id, bool $mergeFields = false) {
        Logger::info("Recuperando centro de custo.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Centro de Custo"));
        }
        $stock = (new Stock())->getById($id);
        if (is_null($stock)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Centro de Custo"));
        }
        if (!$mergeFields) {
            Logger::info("Finalizando a recuperação do centro de custo.");
            return $stock;
        }
        Logger::info("Recuperando itens do centro de custo.");
        $stock->items = (new StockItemBusiness())->getItemsByStock(id: $id);
        Logger::info("Finalizando a recuperação do centro de custo.");
        return $stock;
    }

    public function deleteByContractId(int $contractId) {
        Logger::info("Iniciando a recuperação de centros de custo.");
        $stocks = (new Stock())->getByContractId(id: $contractId);
        foreach ($stocks as $stock) {
            $stock->deleted = true;
            $stock->save();
        }
        Logger::info("Finalizando a exclusão dos centros de custo associados ao contrato.");
        return $stocks;
    }

    public function create(array $payload) {
        Logger::info("Iniciando a criação de centro de custo.");
        Logger::info("Validando as informações fornecidas.");

        $modelValidator = new ModelValidator(Stock::$rules, Stock::$rulesMessages);
        $hasErrors = $modelValidator->validate(data: $payload);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        
        Logger::info("Salvando a nova modelo.");
        $instance = new Stock($payload);
        $instance->save();
        Logger::info("Finalizando a atualização de centro de custo.");
        return $instance;
    }

    public function update(array $payload, int $id) {
        Logger::info("Iniciando a atualização do centro de custo.");
        Logger::info("Validando as informações fornecidas.");
        if ($id === 1) {
            throw new InputValidationException("Não é possível atualizar centro de custo da construtora.");
        }

        $stock = $this->getById(id: $id);
        if (is_null($stock)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Centro de Custo"));
        }

        $stockUpdated = UpdateUtils::processFieldsToBeUpdated($stock, $payload, Stock::$fieldsToBeUpdated);
        $modelValidator = new ModelValidator(Stock::$rules, Stock::$rulesMessages);
        $hasErrors = $modelValidator->validate(data: $payload);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        // Trying get the contract
        (new ContractBusiness())->getById(id: $payload["contract_id"], mergerFields: false);
        
        Logger::info("Salvando o centro de custo.");
        $stockUpdated->save();
        Logger::info("Finalizando a atualização de centro de custo.");
        return $stockUpdated;
    }

    public function delete(int $id) {
        Logger::info("Excluindo centro de custo $id.");
        if ($id === 1) {
            throw new InputValidationException("Não é possível excluir centro de custo da construtora.");
        }

        Logger::info("Recuperando centro de custo $id.");
        $stock = $this->getById(id: $id);

        Logger::info("Deletando centro de custo $id.");
        $stock->deleted = true;
        $stock->save();

        Logger::info("Finalizando exclusão centro de custo $id.");
        return $stock;
    }

    public function getAll() {
        Logger::info("Iniciando a recuperação de estoques.");
        $stocks = (new Stock())->getAll("id");
        $amount = count($stocks);

        foreach ($stocks as $stock) {
            if ($stock->id === 1) {
                $stock["contract"] = [
                    "code" => "",
                    "building_type" => "",
                    "proposal" => [
                        "client" => [
                            "name" => ""
                        ]
                    ]
                ];
            } else {
                $stock["contract"] = (new ContractBusiness())->getById(id: $stock->contract_id);
            }
        }

        Logger::info("Foram recuperados {$amount} estoques.");
        Logger::info("Finalizando a recuperação de estoques.");
        return $stocks;
    }

    public function refreshStockItem(PurchaseOrderItem $purchaseItem, Stock $stock) {
        $itemFound = $this->getItemOnStock(purchaseItem: $purchaseItem, stock: $stock);
        if (is_null($itemFound)) {
            (new StockItemBusiness())->create([
                "quantity" => $purchaseItem->quantity,
                "value" => $purchaseItem->value,
                "product_id" => $purchaseItem->product_id,
                "cost_center_id" => 1
            ]); 
        } else {
            $itemFound->quantity = $itemFound->quantity + $purchaseItem->quantity;
            (new StockItemBusiness())->update($itemFound->id, [
                "quantity" => $itemFound->quantity,
                "value" => $itemFound->value,
                "product_id" => $itemFound->product_id,
                "cost_center_id" => 1
            ]);
        }
    }

    private function getItemOnStock(PurchaseOrderItem $purchaseItem, Stock $stock) {
        $item = null;
        foreach ($stock->items as $stockItem) {
            if ($purchaseItem->product_id === $stockItem->product_id && 
                $purchaseItem->value === $stockItem->value) {
                $item = $stockItem;
                break;
            }
        }
        return $item;
    }

}