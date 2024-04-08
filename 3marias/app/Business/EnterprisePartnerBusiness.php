<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\EnterprisePartner;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class EnterprisePartnerBusiness {

    public function get(int $enterpriseId) {
        Logger::info("Iniciando a recuperação de sócios.");
        $enteprisePartners = (new EnterprisePartner())->getByEnterprise(enterpriseId: $enterpriseId);
        $amount = count($enteprisePartners);
        Logger::info("Foram recuperados {$amount} sócios.");
        Logger::info("Finalizando a recuperação de sócios.");
        return $enteprisePartners;
    }

    public function getById(int $id, bool $merge = true) {
        Logger::info("Iniciando a recuperação de sócio $id.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Sócio da Empresa"));
        }
        $enteprisePartner = (new EnterprisePartner())->getById($id);
        if (is_null($enteprisePartner)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Sócio da Empresa"));
        }
        if ($merge) {
            $address = (new AddressBusiness())->getById($enteprisePartner->address_id);
            $enteprisePartner = $this->mountClientAddressInline($enteprisePartner, $address);
        }
        Logger::info("Finalizando a recuperação de sócio $id.");
        return $enteprisePartner;
    }

    public function delete(int $id) {
        $enteprisePartner = $this->getById(id: $id, merge: false);
        Logger::info("Deletando o sócio $id.");
        $enteprisePartner->deleted = true;
        $enteprisePartner->save();
        return $enteprisePartner;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de sócio.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $enteprisePartnerValidator = new ModelValidator(EnterprisePartner::$rules, EnterprisePartner::$rulesMessages);
        $hasErrors = $enteprisePartnerValidator->validate(data: $data);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }

        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova sócio.");
        $enteprisePartner = new EnterprisePartner($data);
        $enteprisePartner->address_id = $address->id;
        $enteprisePartner->save();
        Logger::info("Finalizando a atualização de sócio.");
        return $enteprisePartner;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do sócio.");
        $enteprisePartner = (new EnterprisePartner())->getById($id);
        $enteprisePartnerUpdated = UpdateUtils
            ::processFieldsToBeUpdated($enteprisePartner, $request->all(), EnterprisePartner::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do sócio.");
        $enteprisePartnerValidator = new ModelValidator(EnterprisePartner::$rules, EnterprisePartner::$rulesMessages);
        $hasErrors = $enteprisePartnerValidator->validate(data: $request->all());
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        (new AddressBusiness())->update($request->all(), id: $enteprisePartner->address_id);

        Logger::info("Atualizando as informações do sócio.");
        $enteprisePartnerUpdated->save();
        return $this->getById(id: $enteprisePartnerUpdated->id);
    }

    private function mountClientAddressInline(EnterprisePartner $enterprisePartner, Address $address) {
        $enterprisePartner["address"] = $address->address;
        $enterprisePartner["neighborhood"] = $address->neighborhood;
        $enterprisePartner["number"] = $address->number;
        $enterprisePartner["complement"] = $address->complement;
        $enterprisePartner["city_id"] = $address->city_id;
        $enterprisePartner["zipcode"] = $address->zipcode;
        return $enterprisePartner;
    }

}