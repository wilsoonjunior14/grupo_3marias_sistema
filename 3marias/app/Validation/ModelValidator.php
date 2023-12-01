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
     * Method used to validate enterprise entity
     */
    public function validateEnterprise($data, bool $isUpdate = false) {
        if (empty($_FILES) || !isset($_FILES) 
            || !isset($_FILES["image"]) || empty($_FILES["image"])
            || empty($_FILES["image"]["name"])
            ) {
            $data["image"] = "default.png";
        }

        $validation = $this->validate($data);
        if ($validation !== null) {
            return $validation;
        }

        if (!$isUpdate) {
            $enterprisesList = (new Enterprise())->getByNameOrEmail($data["name"], $data["email"]);
            if (count($enterprisesList) > 0) {
                return "Empresa com nome ou email já existentes.";
            }
        }
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
