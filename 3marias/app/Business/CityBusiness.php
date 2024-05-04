<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\City;
use App\Models\Logger;
use App\Models\State;
use App\Utils\ErrorMessage;

class CityBusiness {

    public function getById(int $id) {
        Logger::info("Recuperando cidade.");
        try {
            $city = (new City())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cidade"));
        }
        // TODO: it must be located on statebusiness
        try {
            $state = (new State())->getById($city->state_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Estado"));
        }

        $city["state_name"] = $state->name;
        $city["state_acronym"] = $state->acronym;
        Logger::info("Finalizando a recuperação de cidade.");
        return $city;
    }

}