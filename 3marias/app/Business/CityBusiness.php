<?php

namespace App\Business;

use App\Models\City;
use App\Models\Logger;
use App\Models\State;

class CityBusiness {

    public function getById(int $id) {
        Logger::info("Recuperando cidade.");
        $city = (new City())->getById($id);
        $state = (new State())->getById($city->state_id);

        $city["state_name"] = $state->name;
        Logger::info("Finalizando a recuperação de cidade.");
        return $city;
    }

}