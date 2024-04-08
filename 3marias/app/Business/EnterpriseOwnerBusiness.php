<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\EnterpriseOwner;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class EnterpriseOwnerBusiness {

    public function get(int $enterpriseId) {
        Logger::info("Iniciando a recuperação de representantes legais.");
        $entepriseOwners = (new EnterpriseOwner())->getByEnterprise(enterpriseId: $enterpriseId);
        $amount = count($entepriseOwners);
        Logger::info("Foram recuperados {$amount} representantes legais.");
        Logger::info("Finalizando a recuperação de representantes legais.");
        return $entepriseOwners;
    }

    public function getById(int $id, bool $merge = true) {
        Logger::info("Iniciando a recuperação de representante legal $id.");
        $enterpriseOwner = (new EnterpriseOwner())->getById($id);
        if ($merge) {
            $address = (new AddressBusiness())->getById($enterpriseOwner->address_id);
            $enterpriseOwner = $this->mountClientAddressInline($enterpriseOwner, $address);
        }
        Logger::info("Finalizando a recuperação de representante legal $id.");
        return $enterpriseOwner;
    }

    public function delete(int $id) {
        $enterpriseOwner = $this->getById(id: $id, merge: false);
        Logger::info("Deletando o representante legal $id.");
        $enterpriseOwner->deleted = true;
        $enterpriseOwner->save();
        return $enterpriseOwner;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de representante legal.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $enterpriseOwnerValidator = new ModelValidator(EnterpriseOwner::$rules, EnterpriseOwner::$rulesMessages);
        $hasErrors = $enterpriseOwnerValidator->validate(data: $data);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova representante legal.");
        $enterpriseOwner = new EnterpriseOwner($data);
        $enterpriseOwner->address_id = $address->id;
        $enterpriseOwner->save();
        Logger::info("Finalizando a atualização de representante legal.");
        return $enterpriseOwner;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do representante legal.");
        $enterpriseOwner = (new EnterpriseOwner())->getById($id);
        $enterpriseOwnerUpdated = UpdateUtils
            ::processFieldsToBeUpdated($enterpriseOwner, $request->all(), EnterpriseOwner::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do representante legal.");
        $enterpriseOwnerValidator = new ModelValidator(EnterpriseOwner::$rules, EnterpriseOwner::$rulesMessages);
        $enterpriseOwnerValidator->validate(data: $request->all());

        (new AddressBusiness())->update($request->all(), id: $enterpriseOwner->address_id);

        Logger::info("Atualizando as informações do representante legal.");
        $enterpriseOwnerUpdated->save();
        return $this->getById(id: $enterpriseOwnerUpdated->id);
    }

    private function mountClientAddressInline(EnterpriseOwner $enterpriseOwner, Address $address) {
        $enterpriseOwner["address"] = $address->address;
        $enterpriseOwner["neighborhood"] = $address->neighborhood;
        $enterpriseOwner["number"] = $address->number;
        $enterpriseOwner["complement"] = $address->complement;
        $enterpriseOwner["city_id"] = $address->city_id;
        $enterpriseOwner["zipcode"] = $address->zipcode;
        $enterpriseOwner["city_name"] = $address->city_name;
        $enterpriseOwner["state_name"] = $address->state_name;
        $enterpriseOwner["state_acronym"] = $address->state_acronym;
        return $enterpriseOwner;
    }

}