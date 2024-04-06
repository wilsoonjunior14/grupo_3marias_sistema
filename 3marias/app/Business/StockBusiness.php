<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Stock;
use App\Models\Logger;
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
        (new ContractBusiness())->getById(id: $payload["contract_id"], mergeFields: false);
        
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
            $stock["items"] = (new StockItemBusiness())->getItemsByStock(id: $stock->id);
        }

        Logger::info("Foram recuperados {$amount} estoques.");
        Logger::info("Finalizando a recuperação de estoques.");
        return $stocks;
    }

    public function refreshStockItem(int $productId, float $value, int $quantity, Stock $stock) {
        Logger::info("Salvando produto $productId no centro de custo " . $stock->id);
        $itemFound = $this->getItemOnStock(productId: $productId, value: $value, stock: $stock);
        if (is_null($itemFound)) {
            (new StockItemBusiness())->create([
                "quantity" => $quantity,
                "value" => $value,
                "product_id" => $productId,
                "cost_center_id" => $stock->id
            ]); 
        } else {
            $itemFound->quantity = $itemFound->quantity + $quantity;
            (new StockItemBusiness())->update($itemFound->id, [
                "quantity" => $itemFound->quantity,
                "value" => $itemFound->value,
                "product_id" => $itemFound->product_id,
                "cost_center_id" => $stock->id
            ]);
        }
    }

    private function getItemOnStock(int $productId, float $value, Stock $stock) {
        $item = null;
        foreach ($stock->items as $stockItem) {
            if (strcmp(strval($productId), strval($stockItem->product_id)) === 0 &&
                strcmp(strval($value), strval($stockItem->value)) === 0) {
                $item = $stockItem;
            }
        }
        return $item;
    }

    public function shareProductsAmongCostCenters(array $payload) {
        Logger::info("Validando os dados fornecidos para compartilhamento entre centros de custo.");
        $rules = [
            'cost_center_id' => 'required|integer|gt:0',
            'products' => 'required|array|min:1|distinct',
            'products.*.product_id' => 'distinct'
        ];
        $rulesMessages = [
            'cost_center_id.required' => 'Campo Identificador do Centro de Custo de Destino é obrigatório.',
            'cost_center_id.integer' => 'Campo Identificador do Centro de Custo de Destino está inválido.',
            'cost_center_id.gt' => 'Campo Identificador do Centro de Custo de Destino está inválido.',
            'products.required' => 'Campo Lista de Produtos é obrigatório.',
            'products.array' => 'Campo Lista de Produtos está inválido.',
            'products.min' => 'Campo Lista de Produtos não pode ser vazio.',
            'products.*.product_id.distinct' => 'Campo Lista de Produtos não pode ter produtos repetidos.',
        ];
        $validator = new ModelValidator($rules, $rulesMessages);
        $hasErrors = $validator->validate(data: $payload);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        $stockDestination = $this->getById(id: $payload["cost_center_id"], mergeFields: true);
        // Validate if the stock items and quantity are correct
        Logger::info("Validando produtos a serem compartilhados entre centros de custo.");
        $this->validateStockItemsToBeShared(stockItems: $payload["products"]);

        return $this->shareItems(destination: $stockDestination, stockItems: $payload["products"]);
    }

    private function shareItems(Stock $destination, array $stockItems) {
        // Update old stock item
        Logger::info("Atualizando as quantidades dos produtos no centro de custo.");
        $this->updateStockItemsQuantity(stockItems: $stockItems);

        // Create or Update the stock item on the destination
        Logger::info("Efetuando a transferência de produtos entre centros de custo.");
        foreach ($stockItems as $stockItem) {
            $this->refreshStockItem(productId: $stockItem["product_id"], value: $stockItem["value"],
                quantity: $stockItem["quantity"], stock: $destination);
        }
        return $destination;
    }

    private function validateStockItemsToBeShared(array $stockItems) {
        $stockItemBusiness = new StockItemBusiness();
        foreach ($stockItems as $stockItem) {
            $stockItemBusiness->validateStockItem(payload: $stockItem);
            if (!isset($stockItem["id"]) || empty($stockItem["id"])) {
                throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "Identificador de Produto do Estoque"));
            }
            $oldStockItem = $stockItemBusiness->getById(id: $stockItem["id"]);
            if ($oldStockItem->quantity < $stockItem["quantity"]) {
                throw new InputValidationException("Produto de Código " . $stockItem["id"] . " com quantidade informada inválida.");
            }
        }
    }

    private function updateStockItemsQuantity(array $stockItems) {
        $stockBusinessItem = new StockItemBusiness();
        foreach ($stockItems as $stockItem) {
            $oldStockItem = $stockBusinessItem->getById(id: $stockItem["id"]);
            $oldStockItem->quantity = $oldStockItem->quantity - $stockItem["quantity"];
            $oldStockItem->save();
        }
    }

}