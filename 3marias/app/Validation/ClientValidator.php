<?php

namespace App\Validation;

use App\Exceptions\InputValidationException;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * Client Validator
 */
class ClientValidator extends ModelValidator
{

    public function validateUpdate(Request $request) {
        $rules = $this->getRules();
        $rules["cpf"] = "required|cpf";
        $rules["cpf_dependent"] = "cpf|different:cpf";
        $this->validateData(request: $request, isUpdate: true, rules: $rules);
    }

    public function validateData(Request $request, bool $isUpdate = false, array $rules = []) {
        $data = $request->all();
        if (empty($data["birthdate"])) {
            unset($data["birthdate"]);
        }

        if (empty($data["birthdate_dependent"])) {
            unset($data["birthdate_dependent"]);
        }

        if (empty($data["phone_dependent"])) {
            unset($data["phone_dependent"]);
        }
        
        if ($isUpdate) {
            $validator = Validator::make($data, $rules, $messages = $this->getRulesMessages());
            if ($validator->stopOnFirstFailure()->fails()){
                $errors = $validator->stopOnFirstFailure()->errors();
                   throw new InputValidationException($errors->first());
            }
        } else {
            $clientValidation = parent::validate($data);
            if (!is_null($clientValidation)) {
                throw new InputValidationException($clientValidation);
            }
        }

        if (strcmp($data["state"], "Casado") === 0) {
            $this->validateDependentData(data: $data);
            return;
        }
        
        $data = UpdateUtils::clearFields(targetData: $data, fields: ["name_dependent", "cpf_dependent", "rg_dependent",
            "email_dependent", "phoneNumber_dependent", "ocupation_dependent", "nationality_dependent", "birthdate_dependent"]);
    }

    private function validateDependentData(array $data) {
        if (!isset($data["name_dependent"]) || empty($data["name_dependent"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome Completo do Cônjugue"));
        }
        if (!isset($data["cpf_dependent"]) || empty($data["cpf_dependent"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "CPF do Cônjugue"));
        }
        if (!isset($data["rg_dependent"]) || empty($data["rg_dependent"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "RG do Cônjugue"));
        }
        if (!isset($data["ocupation_dependent"]) || empty($data["ocupation_dependent"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "Profissão do Cônjugue"));
        }
    }
}
