<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Enterprise;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\EnterpriseValidator;
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

    public function getById(int $id, bool $mergeFields = false) {
        Logger::info("Iniciando a recuperação de empresa $id.");
        try {
            $enterprise = (new Enterprise())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Empresa"));
        }
        if (!$mergeFields) {
            Logger::info("Finalizando a recuperação de empresa $id.");
            return $enterprise;
        }
        $address = (new AddressBusiness())->getById($enterprise->address_id, merge: true);
        $enterprise = $enterprise->mountAddressInline($enterprise, $address);
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
        $enterprise = $this->getById($id);
        $enterpriseToBeUpdated = new Enterprise($request->all());
        $enterpriseToBeUpdated->validate(rules:Enterprise::$rules, rulesMessages:Enterprise::$rulesMessages);
        $enterpriseUpdated = UpdateUtils::processFieldsToBeUpdated($enterprise, $request->all(), Enterprise::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do empresa.");
        $enterpriseUpdated->validate(rules:Enterprise::$rules, rulesMessages:Enterprise::$rulesMessages);
        (new AddressBusiness())->update($request->all(), id: $enterprise->address_id);

        Logger::info("Atualizando as informações do empresa.");
        $enterpriseUpdated->save();
        return $this->getById(id: $id);
    }

}