<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\MeasurementItem;
use App\Utils\ErrorMessage;

class MeasurementItemBusiness {

    public function getById($id, bool $mergeFields = false) {
        Logger::info("Iniciando a recuperação do item de medição.");
        try {
            $bill = (new MeasurementItem())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Item de Medição"));
        }
        if (!$mergeFields) {
            Logger::info("Finalizando a recuperação do item de medição.");
            return $bill;
        }   
        return $bill;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação da medição inicial.");
        Logger::info("Validando a medição inicial.");

        $measurementItem = new MeasurementItem($data);
        $measurementItem->validate(rules: MeasurementItem::$rules, rulesMessages: MeasurementItem::$rulesMessages);
        $measurementItem->save();

        Logger::info("Finalizando a criação da medição inicial.");
        return $measurementItem;
    }
}