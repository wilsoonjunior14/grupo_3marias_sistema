<?php

namespace App\Validation;

use App\Models\Enterprise;
use App\Models\Group;
use App\Models\Role;
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
        $groupObj = $groupInstance->getById($data["group_id"]);
        if ($groupObj === null || empty($groupObj) || $groupObj->deleted) {
            $errors = "Identificador do grupo informado é inexistente.";
        }

        $roleInstance = new Role();
        $roleObj = $roleInstance->getById($data["role_id"]);
        if ($roleObj === null || empty($roleObj) || $roleObj->deleted) {
            $errors = "Identificador da permissão informada é inexistente.";
        }

        return $errors;
    }
}
