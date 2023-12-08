<?php

namespace App\Validation;

use App\Exceptions\InputValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

/**
 * Enterprise Validator
 */
class EnterpriseValidator extends ModelValidator
{

    public function validateUpdate(Request $request) {
        $rules = $this->getRules();
        $rules["cnpj"] = "required|cnpj";
        $this->validateData(request: $request, isUpdate: true, rules: $rules);
    }

    public function validateData(Request $request, bool $isUpdate = false, array $rules = []) {
        $data = $request->all();
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
    }
}
