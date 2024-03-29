<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\StockItem;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;

class StockItemBusiness {

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de item do centro de custo $id.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Item do centro de custo"));
        }
        $stockItem = (new StockItem())->getById($id);
        if (is_null($stockItem)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Item do centro de custo"));
        }
        Logger::info("Finalizando a recuperação de item do centro de custo $id.");
        return $stockItem;
    }

    public function create(array $payload) {
        Logger::info("Iniciando a criação de item do centro de custo.");
        Logger::info("Validando as informações fornecidas.");

        $stockItem = new StockItem($payload);
        $stockItem->validate(rules: StockItem::$rules, rulesMessages: StockItem::$rulesMessages);
        
        Logger::info("Salvando o item do centro de custo.");
        $instance = new StockItem($payload);
        $instance->save();
        Logger::info("Finalizando a atualização de item do centro de custo.");
        return $instance;
    }

    public function update(int $id, array $payload) {
        Logger::info("Iniciando a atualização de item do centro de custo.");
        Logger::info("Validando as informações fornecidas.");

        $stockItem = $this->getById(id: $id);
        $stockUpdated = new StockItem($payload);
        $stockUpdated->validate(rules: StockItem::$rules, rulesMessages: StockItem::$rulesMessages);

        Logger::info("Atualizando o item do centro de custo.");
        $stockUpdated = UpdateUtils::processFieldsToBeUpdated($stockItem, $payload, StockItem::$fieldsToBeUpdated);
        
        Logger::info("Salvando o item do centro de custo.");
        $stockUpdated->save();
        Logger::info("Finalizando a atualização de item do centro de custo.");
        return $stockUpdated;
    }

    public function getItemsByStock(int $id) {
        Logger::info("Iniciando a recuperação de itens do centro de custo.");
        $items = (new StockItem())->getItemsByStock(id: $id);
        Logger::info("Finalizando a recuperação de itens do centro de custo.");
        return $items;
    }

}