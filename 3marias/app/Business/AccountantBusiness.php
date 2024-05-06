<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\Accountant;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Exception;
use Illuminate\Http\Request;

class AccountantBusiness {

    public function get(int $enterpriseId) {
        Logger::info("Iniciando a recuperação de contadores.");
        $accountants = (new Accountant())->getByEnterprise(enterpriseId: $enterpriseId);
        $amount = count($accountants);
        Logger::info("Foram recuperados {$amount} contadores.");
        Logger::info("Finalizando a recuperação de contadores.");
        return $accountants;
    }

    public function getById(int $id, bool $merge = true) {
        Logger::info("Iniciando a recuperação de contador $id.");
        try {
            $accountant = (new Accountant())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador"));
        }
        if ($merge) {
            $address = (new AddressBusiness())->getById($accountant->address_id);
            $accountant = $this->mountClientAddressInline($accountant, $address);
        }
        Logger::info("Finalizando a recuperação de contador $id.");
        return $accountant;
    }

    public function delete(int $id) {
        $accountant = $this->getById(id: $id, merge: false);
        Logger::info("Deletando o contador $id.");
        $accountant->deleted = true;
        $accountant->save();
        return $accountant;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de contador.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $accountantValidator = new ModelValidator(Accountant::$rules, Accountant::$rulesMessages);
        $hasErrors = $accountantValidator->validate(data: $data);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }

        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova contador.");
        $accountant = new Accountant($data);
        $accountant->address_id = $address->id;
        $accountant->save();
        Logger::info("Finalizando a atualização de contador.");
        return $accountant;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do contador.");
        $accountant = $this->getById(id: $id, merge: false);
        $accountantUpdated = UpdateUtils::updateFields(fieldsToBeUpdated: Accountant::$fieldsToBeUpdated, model: $accountant, requestData: $request->all());

        Logger::info("Validando as informações do contador.");
        $accountantValidator = new ModelValidator(Accountant::$rules, Accountant::$rulesMessages);
        $validation = $accountantValidator->validate(data: $request->all());
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        (new AddressBusiness())->update($request->all(), id: $accountant->address_id);

        Logger::info("Atualizando as informações do contador.");
        $accountantUpdated->save();
        return $this->getById(id: $accountantUpdated->id);
    }

    private function mountClientAddressInline(Accountant $accountant, Address $address) {
        $accountant["address"] = $address->address;
        $accountant["neighborhood"] = $address->neighborhood;
        $accountant["number"] = $address->number;
        $accountant["complement"] = $address->complement;
        $accountant["city_id"] = $address->city_id;
        $accountant["zipcode"] = $address->zipcode;
        return $accountant;
    }

}