<?php

namespace App\Validation;

use App\Exceptions\InputValidationException;
use App\Models\Enterprise;
use App\Models\Group;
use App\Models\Role;
use App\Utils\ErrorMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * Generic class to apply validations
 */
class ModelValidator
{

    private $rules;
    private $rulesMessages;

    public function __construct($rules, $rulesMessages) {
        $this->rules = $rules;
        $this->rulesMessages = $rulesMessages;
    }   

    public function getRules() {
        return $this->rules;
    }

    public function getRulesMessages() {
        return $this->rulesMessages;
    }

    /**
     * Method to be used to validate all input data.
     */
    public function validate($data) {
        $errors = null;
        $validator = Validator::make($data, $this->rules, $messages = $this->rulesMessages);
        if ($validator->stopOnFirstFailure()->fails()){
            $errors = $validator->stopOnFirstFailure()->errors();
               return $errors->first();
        }
        return $errors;
    }

    /**
     * Method to be used to validate all input data and image attached.
     */
    public function validateWithImage(Request $request) {
        $data = $request->all();
     
        $validator = $this->validate($data);
        if ($validator !== null){
            return $validator;
        }

        $imgValidation = $this->validateImage(request: $request);
        if ($imgValidation !== null) {
            return $imgValidation;
        }
        
        return null;
    }

    /**
     * Validates image field.
     */
    public function validateImage(Request $request, bool $imageIsRequired = true) {
        $imgFile = $request->file("image");
        if ($imageIsRequired && $imgFile === null) {
            return "Campo Imagem é obrigatório";
        }
        if ($imgFile !== null && !strpos($imgFile->getMimeType(), "png")) {
            return "Campo Imagem deve ser um .png.";
        }
        return null;
    }

    /**
     * Method used to validate group role instance.
     */
    public function validateGroupRole($data) {
        $errors = null;

        $validator = $this->validate($data);
        if ($validator !== null){
            return $validator;
        }

        $groupInstance = new Group();
        try {
            $groupInstance->getById($data["group_id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Grupo de Usuário"));
        } 

        $roleInstance = new Role();
        try {
            $roleInstance->getById($data["role_id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Permissão"));
        } 

        return $errors;
    }
}
