<?php

namespace App\Business;

use App\Models\State;
use App\Models\Logger;

class StateBusiness {

    public function getById(int $id) {
        Logger::info("Recuperando estado.");
        $state = (new State())->getById($id);
        Logger::info("Finalizando a recuperação de estado.");
        return $state;
    }

}