<?php

namespace App\Business;

use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\User;
use App\Utils\ErrorMessage;

class UserBusiness {

    public function create(array $data) {
        $validation = User::validateUserData($data);
        if (!is_null($validation)){
            throw new InputValidationException($validation);
        }

        Logger::info("Verificando se já existe usuário com os dados informados.");
        $existingUsers = (new User())->getUserByEmail($data["email"]);
        if (count($existingUsers) > 0){
            throw new EntityAlreadyExistsException(ErrorMessage::$EMAIL_ALREADY_EXISTS);
        }

        Logger::info("Criando o novo usuário.");
        $userObj = new User($data);
        $userObj->save();
        return $userObj;
    }

}