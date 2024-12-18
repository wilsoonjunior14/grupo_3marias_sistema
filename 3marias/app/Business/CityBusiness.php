<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\City;
use App\Models\Logger;
use App\Models\State;
use App\Utils\ErrorMessage;

class CityBusiness {

    public function getCitiesWithUF() {
        return City::where("deleted", false)
        ->with("state")
        ->orderBy("name")
        ->get();
    }

    public function getById(int $id, bool $mergeFields = true) {
        Logger::info("Recuperando cidade.");
        try {
            $city = (new City())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cidade"));
        }

        if (!$mergeFields) {
            return $city;
        }
        // TODO: it must be located on statebusiness
        try {
            $state = (new State())->getById($city->state_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Estado"));
        }

        $city->state_name = $state->name;
        $city->state_acronym = $state->acronym;
        Logger::info("Finalizando a recuperação de cidade.");
        return $city;
    }

}