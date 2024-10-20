<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Accountant;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        } catch (ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Contador"));
        }
        if ($merge) {
            $address = (new AddressBusiness())->getById($accountant->address_id);
            $accountant = $accountant->mountAddressInline($accountant, $address);
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

    public function create(array $data) {
        Logger::info("Iniciando a criação de contador.");
        Logger::info("Validando as informações fornecidas.");

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

    public function update(int $id, array $data) {
        Logger::info("Alterando informações do contador.");
        $accountant = $this->getById(id: $id, merge: false);
        $accountantUpdated = UpdateUtils::updateFields(fieldsToBeUpdated: Accountant::$fieldsToBeUpdated, model: $accountant, requestData: $data);

        Logger::info("Validando as informações do contador.");
        $accountantValidator = new ModelValidator(Accountant::$rules, Accountant::$rulesMessages);
        $validation = $accountantValidator->validate(data: $data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        (new AddressBusiness())->update($data, id: $accountant->address_id);

        Logger::info("Atualizando as informações do contador.");
        $accountantUpdated->save();
        return $this->getById(id: $accountantUpdated->id);
    }
}