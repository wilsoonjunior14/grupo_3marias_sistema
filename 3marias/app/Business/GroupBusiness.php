<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\GroupRole;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class GroupBusiness {

    public function checkRoleExists(array $data) {
        Logger::info("Verificando se já existe permissão no grupo.");
        $exists = count((new GroupRole())->getGroupRole(group_id: $data["group_id"], role_id: $data["role_id"])) > 0;
        if ($exists) {
            Logger::info("Permissão já existente no grupo.");
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "permissão", "grupo"));
        }
    }

}