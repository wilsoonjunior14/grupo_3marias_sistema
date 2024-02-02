<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\Contract;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\enterpriseValidator;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ContractBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de contratos.");
        $contracts = (new Contract())->getAll("code");

        foreach ($contracts as $contract) {
            $contract["proposal"] = (new ProposalBusiness())->getById(id: $contract->proposal_id);
            $contract["address"] = (new AddressBusiness())->getById(id: $contract->address_id);
        }

        $amount = count($contracts);
        Logger::info("Foram recuperados {$amount} contratos.");
        Logger::info("Finalizando a recuperação de contratos.");
        return $contracts;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de contrato $id.");
        $contract = (new Contract())->getById($id);
        $contract["address"] = (new AddressBusiness())->getById($contract->address_id, merge: true);
        $contract["proposal"] = (new ProposalBusiness())->getById(id: $contract->proposal_id);
        Logger::info("Finalizando a recuperação de contrato $id.");
        return $contract;
    }

    public function delete(int $id) {
        $contract = $this->getById(id: $id);
        Logger::info("Deletando o contrato $id.");
        $contract->deleted = true;
        $contract->save();
        return $contract;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de contrato.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();
        $code = count($this->get()) . "" . date('Y') . "" . date('m') . "" . random_int(10, 99) . "3MCRT";
        $data["code"] = $code;
        $data["address_id"] = 0;

        $enterpriseValidator = new ModelValidator(Contract::$rules, Contract::$rulesMessages);
        $validation = $enterpriseValidator->validate(data: $data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova contrato.");
        $contract = new Contract($data);
        $contract->address_id = $address->id;
        $contract->save();

        // TODO: CREATE STOCK ASSOCIATED
        // TODO: CREATE BILLS TO RECEIVE
        // TODO: CREATE BUILDING

        Logger::info("Finalizando a atualização de contrato.");
        return $contract;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do contrato.");
        $contract = (new Contract())->getById($id);
        $contractModel = ((array) new Contract($request->all()));
        $contractUpdated = UpdateUtils::processFieldsToBeUpdated($contract, $contractModel, Contract::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do contrato.");
        $contractValidator = new ModelValidator(Contract::$rules, Contract::$rulesMessages);
        $validation = $contractValidator->validate(data: $contractUpdated);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        (new AddressBusiness())->update($request->all(), id: $contract->address_id);

        Logger::info("Atualizando as informações do contrato.");
        $contractUpdated->save();
        return $this->getById(id: $contractUpdated->id);
    }

}