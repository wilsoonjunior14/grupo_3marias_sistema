<?php

namespace App\Business;

use App\Models\Address;
use App\Models\Enterprise;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\enterpriseValidator;
use Illuminate\Http\Request;

class EnterpriseBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de empresas.");
        $enterprises = (new Enterprise())->getAll("name");
        $amount = count($enterprises);
        Logger::info("Foram recuperados {$amount} empresas.");
        Logger::info("Finalizando a recuperação de empresas.");
        return $enterprises;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de empresa $id.");
        $enterprise = (new Enterprise())->getById($id);
        $address = (new AddressBusiness())->getById($enterprise->address_id);
        $enterprise = $this->mountenterpriseAddressInline($enterprise, $address);
        $enterprise["accountants"] = (new AccountantBusiness())->get(enterpriseId: $id);
        $enterprise["partners"] = (new EnterprisePartnerBusiness())->get(enterpriseId: $id);
        $enterprise["owners"] = (new EnterpriseOwnerBusiness())->get(enterpriseId: $id);
        $enterprise["branches"] = (new EnterpriseBranchBusiness())->get(enterpriseId: $id);
        $enterprise["files"] = (new EnterpriseFileBusiness())->get(enterpriseId: $id);
        Logger::info("Finalizando a recuperação de empresa $id.");
        return $enterprise;
    }

    public function delete(int $id) {
        $enterprise = $this->getById(id: $id);
        Logger::info("Deletando a empresa $id.");
        $enterprise->deleted = true;
        $enterprise->save();
        return $enterprise;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de empresa.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $enterpriseValidator = new EnterpriseValidator(enterprise::$rules, enterprise::$rulesMessages);
        $enterpriseValidator->validateData(request: $request);

        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova empresa.");
        $enterprise = new enterprise($data);
        $enterprise->address_id = $address->id;
        $enterprise->save();
        Logger::info("Finalizando a atualização de empresa.");
        return $enterprise;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do empresa.");
        $enterprise = (new Enterprise())->getById($id);
        $enterpriseModel = ((array) new Enterprise($request->all()));
        $enterpriseUpdated = UpdateUtils::processFieldsToBeUpdated($enterprise, $enterpriseModel, enterprise::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do empresa.");
        $enterpriseValidator = new EnterpriseValidator(enterprise::$rules, enterprise::$rulesMessages);
        $enterpriseValidator->validateUpdate(request: $request);

        (new AddressBusiness())->update($request->all(), id: $enterprise->address_id);

        Logger::info("Atualizando as informações do empresa.");
        $enterpriseUpdated->save();
        return $this->getById(id: $enterpriseUpdated->id);
    }

    private function mountenterpriseAddressInline(enterprise $enterprise, Address $address) {
        $enterprise["address"] = $address->address;
        $enterprise["neighborhood"] = $address->neighborhood;
        $enterprise["number"] = $address->number;
        $enterprise["complement"] = $address->complement;
        $enterprise["city_id"] = $address->city_id;
        $enterprise["zipcode"] = $address->zipcode;
        return $enterprise;
    }

}