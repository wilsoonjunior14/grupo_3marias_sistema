<?php

namespace App\Validation;

use App\Exceptions\InputValidationException;
use App\Models\Client;
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
        
        $data = UpdateUtils::clearFields(targetData: $data, fields: Client::$dependentFields);
    }
}
