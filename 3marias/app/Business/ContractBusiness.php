<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Contract;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ContractBusiness {

    private $stockBusiness;
    private $proposalBusiness;

    public function __construct() {
        $this->stockBusiness = new StockBusiness();
        $this->proposalBusiness = new ProposalBusiness();
    }

    public function get(bool $mergeFields = true) {
        Logger::info("Iniciando a recuperação de contratos.");
        $contracts = (new Contract())->getAll("code");

        if ($mergeFields) {
            foreach ($contracts as $contract) {
                $contract["proposal"] = (new ProposalBusiness())->getById(id: $contract->proposal_id);
                $contract["client"] = (new ClientBusiness())->getById(id: $contract["proposal"]["client_id"]);
                $contract["address"] = (new AddressBusiness())->getById(id: $contract->address_id);
                $contract["bills_receive"] = (new BillReceiveBusiness())->getByContract(id: $contract->id);
            }
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

    public function getByProposalId(int $id) {
        Logger::info("Iniciando a recuperação de contrato pelo identificador da proposta $id.");
        $contract = (new Contract())->getByProposalId(id: $id);
        Logger::info("Finalizando a recuperação de contrato pelo identificador da proposta $id.");
        return $contract;
    }

    public function delete(int $id) {
        $contract = $this->getById(id: $id);
        Logger::info("Deletando o contrato $id.");

        // TODO: A CONTRACT CANNOT BE DELETED IF IT HAS SOME PAYMENT DONE.
        // TODO: IF CONTRACT WILL BE DELETED, WE ALSO NEED DELETE PAYMENTS FROM BILLTORECEIVE table, NEED DELETE THE STOCK ASSOCIATED

        // TODO: DELETE A STOCK ASSOCIATED TO CONTRACT
        // TODO: DELETE A BILLS RECEIVED ASSOCIATED TO CONTRACT

        $contract->deleted = true;
        $contract->save();
        return $contract;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de contrato.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();
        $code = count($this->get(mergeFields: false)) . "" . date('Y') . "" . date('m') . "" . random_int(10, 99) . "3MCRT";
        $data["code"] = $code;
        $data["address_id"] = 0;
        $data["date"] = date('Y-m-d');

        $enterpriseValidator = new ModelValidator(Contract::$rules, Contract::$rulesMessages);
        $validation = $enterpriseValidator->validate(data: $data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        // Checking if the proposal is available
        $proposal = (new ProposalBusiness())->getById(id: $data["proposal_id"], mergeFields: false);
        if (!($proposal->status === 2 && count($this->getByProposalId(id: $proposal->id)) === 0)) {
            throw new InputValidationException("Não foi possível criar o contrato. Proposta informada não foi aprovada ou já possui contrato associado.");
        }

        $address = (new AddressBusiness())->create($data);
        Logger::info("Salvando a nova contrato.");
        $contract = new Contract($data);
        $contract->address_id = $address->id;
        $contract->save();

        // Create the stock associated
        Logger::info("Criando o novo centro de custo do contrato.");
        $this->stockBusiness->create(payload: [
            "name" => "Centro de Custo - $contract->code",
            "contract_id" => $contract->id,
            "status" => "Ativo"
        ]);

        // Create the bills to receive related to the contract
        Logger::info("Criando os pagamentos a receber do contrato.");
        $proposal = $this->proposalBusiness->getById(id: $contract->proposal_id);
        foreach ($proposal->payments as $payment) {
            $payload = [
                "code" => $payment->code,
                "type" => $payment->type,
                "value" => $payment->value,
                "value_performed" => 0,
                "description" => $payment->description,
                "source" => $payment->source,
                "desired_date" => $payment->desired_date,
                "bank" => $payment->bank,
                "contract_id" => $contract->id,
                "status" => 0
            ];
            (new BillReceiveBusiness())->create(data: $payload);
        }

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