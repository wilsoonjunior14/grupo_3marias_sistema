<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\EnterpriseBranch;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class EnterpriseBranchBusiness {

    public function get(int $enterpriseId) {
        Logger::info("Iniciando a recuperação de filiais.");
        $entepriseBranches = (new EnterpriseBranch())->getByEnterprise(enterpriseId: $enterpriseId);
        $amount = count($entepriseBranches);
        Logger::info("Foram recuperados {$amount} filiais.");
        Logger::info("Finalizando a recuperação de filiais.");
        return $entepriseBranches;
    }

    public function getById(int $id, bool $merge = true) {
        Logger::info("Iniciando a recuperação de filial $id.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Filial da Empresa"));
        }
        $enterpriseBranch = (new EnterpriseBranch())->getById($id);
        if (is_null($enterpriseBranch)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Filial da Empresa"));
        }
        if ($merge) {
            $address = (new AddressBusiness())->getById($enterpriseBranch->address_id);
            $enterpriseBranch = $this->mountClientAddressInline($enterpriseBranch, $address);
        }
        Logger::info("Finalizando a recuperação de filial $id.");
        return $enterpriseBranch;
    }

    public function delete(int $id) {
        $enterpriseBranch = $this->getById(id: $id, merge: false);
        Logger::info("Deletando o filial $id.");
        $enterpriseBranch->deleted = true;
        $enterpriseBranch->save();
        return $enterpriseBranch;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de filial.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $enterpriseBranchValidator = new ModelValidator(EnterpriseBranch::$rules, EnterpriseBranch::$rulesMessages);
        $hasErrors = $enterpriseBranchValidator->validate(data: $data);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        $this->existsEntity(cnpj: $data["cnpj"]);
        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova filial.");
        $enterpriseBranch = new EnterpriseBranch($data);
        $enterpriseBranch->address_id = $address->id;
        $enterpriseBranch->save();
        Logger::info("Finalizando a atualização de filial.");
        return $enterpriseBranch;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do filial.");
        $enterpriseBranch = $this->getById($id, merge: false);
        $enterpriseBranchUpdated = UpdateUtils
            ::processFieldsToBeUpdated($enterpriseBranch, $request->all(), EnterpriseBranch::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do filial.");
        $enterpriseBranchValidator = new ModelValidator(EnterpriseBranch::$rules, EnterpriseBranch::$rulesMessages);
        $hasErrors = $enterpriseBranchValidator->validate(data: $request->all());
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        $this->existsEntity(cnpj: $enterpriseBranch["cnpj"], id: $id);
        (new AddressBusiness())->update($request->all(), id: $enterpriseBranch->address_id);

        Logger::info("Atualizando as informações do filial.");
        $enterpriseBranchUpdated->save();
        return $this->getById(id: $enterpriseBranchUpdated->id);
    }

    private function mountClientAddressInline(EnterpriseBranch $enterpriseBranch, Address $address) {
        $enterpriseBranch["address"] = $address->address;
        $enterpriseBranch["neighborhood"] = $address->neighborhood;
        $enterpriseBranch["number"] = $address->number;
        $enterpriseBranch["complement"] = $address->complement;
        $enterpriseBranch["city_id"] = $address->city_id;
        $enterpriseBranch["zipcode"] = $address->zipcode;
        return $enterpriseBranch;
    }

    private function existsEntity(string $cnpj, int $id = null) {
        $condition = [["cnpj", "=", $cnpj]];
        $exists = (new EnterpriseBranch())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "CNPJ da Filial", "Filiais"));
        }
        return $exists;
    }

}