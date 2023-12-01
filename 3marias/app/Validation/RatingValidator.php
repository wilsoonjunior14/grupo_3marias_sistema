<?php

namespace App\Validation;

use App\Models\Enterprise;
use App\Models\User;
use App\Utils\ErrorMessage;
use Illuminate\Support\Facades\Validator;

/**
 * Generic class to apply validations
 */
class RatingValidator extends ModelValidator
{ 
    /**
     * Method to be used to validate all input data from Rating entity.
     */
    public function validate($data) {
        $validation = parent::validate($data);
        if ($validation !== null){
            return $validation;
        }

        // checking if user exists
        $user = (new User())->getUserById($data["user_id"]);
        if ($user === null) {
            return sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "usuário");
        }

        // checking if enterprise exists
        $enterprise = (new Enterprise())->getById($data["enterprise_id"]);
        if ($enterprise === null) {
            return sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "empresa");
        }

        return $validation;
    }
}
