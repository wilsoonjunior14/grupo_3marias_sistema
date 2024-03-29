<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Proposal;
use App\Models\Logger;
use App\Models\ProposalPayment;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ProposalBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de propostas.");
        $proposals = (new Proposal())->getAll("proposal_date");
        $amount = count($proposals);

        $proposalPaymentBusiness = new ProposalPaymentBusiness();
        $clientBusiness = new ClientBusiness();
        $contractBusiness = new ContractBusiness();
        foreach ($proposals as $proposal) {
            $proposal["payments"] = $proposalPaymentBusiness->getByProposalId(proposalId: $proposal->id);
            $proposal["client"] = $clientBusiness->getById(id: $proposal->client_id);

            $contracts = $contractBusiness->getByProposalId(id: $proposal->id);
            $proposal["has_contract"] = count($contracts) > 0 ? true : false;
            $this->setStatusIcon(proposal: $proposal);
        }

        Logger::info("Foram recuperados {$amount} propostas.");
        Logger::info("Finalizando a recuperação de propostas.");
        return $proposals;
    }

    public function getById(int $id, bool $mergeFields = true) {
        Logger::info("Iniciando a recuperação de proposta $id.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta"));
        }
        $proposal = (new Proposal())->getById($id);
        if (is_null($proposal)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Proposta"));
        }
        if ($mergeFields) {
            $proposal->client = (new ClientBusiness())->getById(id: $proposal->client_id);
            $proposal->address = (new AddressBusiness())->getById(id: $proposal->address_id, merge: false);
            $proposal->payments = (new ProposalPaymentBusiness())->getByProposalId(proposalId: $proposal->id);
        }
        Logger::info("Finalizando a recuperação de proposta $id.");
        return $proposal;
    }

    public function getByClientId(int $clientId) {
        Logger::info("Iniciando a recuperação de proposta do cliente $clientId.");
        $proposal = (new Proposal())->getByClientId(clientId: $clientId);
        Logger::info("Finalizando a recuperação de proposta $clientId.");
        return $proposal;
    }

    public function delete(int $id) {
        $proposal = $this->getById(id: $id, mergeFields: false);
        Logger::info("Deletando o de proposta $id.");

        // Check if there is any contract associated.
        Logger::info("Verificando se existe contrato associado a proposta $id.");
        $contract = (new ContractBusiness())->getByProposalId(id: $id);
        if (!is_null($contract) && count($contract) > 0) {
            throw new InputValidationException("Proposta não pode ser excluída. Existe um contrato associado a proposta.");
        }

        // Deleting the payments associated to proposal.
        Logger::info("Deletando pagamentos relacionados a proposta $id.");
        $proposalPayments = (new ProposalPaymentBusiness())->getByProposalId(proposalId: $id);
        foreach ($proposalPayments as $payment) {
            (new ProposalPaymentBusiness())->delete(id: $payment->id);
        }

        Logger::info("Deletando a proposta $id.");
        $proposal->deleted = true;
        $proposal->save();
        return $proposal;
    }

    private function validateProposalRequest(Request $request, int $id = 0) {
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        // Validating and Getting the Client
        if (!isset($data["client_name"]) || empty($data["client_name"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Nome do Cliente"));
        }
        if (!isset($data["client_cpf"]) || empty($data["client_cpf"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "CPF do Cliente"));
        }
        $clientBusiness = new ClientBusiness();
        $client = $clientBusiness->getByNameAndCPF(name: $data["client_name"], cpf: $data["client_cpf"]);

        // Filling missing fields
        Logger::info("Preenchendo campos automaticamente.");
        $rules = Proposal::$rules;
        $rulesMessages = Proposal::$rulesMessages;
        if ($id === 0) {
            $data["code"] = count($this->get()) . "" . date('Y') . "" . date('m') . "" . random_int(10, 99) . "3MP";
            $data["status"] = 0;
            unset($rules["address_id"]);
            unset($rulesMessages["address_id.required"]);
        }
        $data["client_id"] = $client->id;
        // Validating the proposal data.
        Logger::info("Validando as informações da proposta.");
        $proposalValidator = new ModelValidator($rules, $rulesMessages);
        $validation = $proposalValidator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        if ($data["global_value"] <= 0) {
            throw new InputValidationException("Campo Valor Global da Proposta não pode ser menor ou igual a zero.");
        }

        if ($data["discount"] < 0) {
            throw new InputValidationException("Campo Desconto da Proposta não pode ser menor que zero.");
        }

        if ($data["discount"] > $data["global_value"]) {
            throw new InputValidationException("Valor do desconto não pode ser superior ao valor da proposta.");
        }

        // Validating the project id
        (new ProjectBusiness())->getById(id: $data["project_id"]);

        // Validating the address data.
        Logger::info("Validando as informações de endereço.");
        (new AddressBusiness())->validateData(data: $data);
        // Validating the proposal payments.
        Logger::info("Validando as informações de pagamentos.");
        if ( (!isset($data["clientPayments"]) || !isset($data["bankPayments"])) ) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Pagamentos"));
        }
        if ( (empty($data["clientPayments"]) && empty($data["bankPayments"])) ) {
            throw new InputValidationException("Lista de Pagamentos vazias");
        }
        $totalPayments = 0;
        $counter = 0;
        $totalPayments += $this->validateProposalPayment(payments: $data["clientPayments"], source: "Cliente", proposalId: $id, counter: $counter);
        $totalPayments += $this->validateProposalPayment(payments: $data["bankPayments"], source: "Banco", proposalId: $id, counter: $counter);
        
        // Validating the global value with discount and payments
        Logger::info("Validando os valores globais de pagamento.");
        $globalValue = $data["global_value"] - $data["discount"];
        if ($globalValue !== $totalPayments) {
            $diff = $globalValue - $totalPayments;
            throw new InputValidationException("O valor global da proposta diverge dos valores dos pagamentos fornecidos. Diferença de R$ $diff");
        }
        return $data;
    }

    private function validateProposalPayment(array $payments, string $source, int $proposalId, int $counter) {
        Logger::info("Validando as informações de pagamentos de $source.");
        $shouldExcludeProposalId = false;
        $totalPayments = 0;
        foreach ($payments as $payment) {
            if ($proposalId === 0) {
                $shouldExcludeProposalId = true;
            }
            if (strcmp($source, "Banco") === 0) {
                unset($payment["desired_date"]);
            }
            if (strcmp($source, "Cliente") === 0) {
                unset($payment["bank"]);
            }
            if (isset($payment["desired_date"]) && is_null($payment["desired_date"])) {
                $payment["desired_date"] = "";
            }
            $payment["proposal_id"] = $proposalId;
            $payment["code"] = $counter . "" . random_int(10000, 99999) . "3MPGT";
            $payment["status"] = 0;
            (new ProposalPaymentBusiness())->validatePayment(data: $payment, excludeProposalId: $shouldExcludeProposalId);
            $totalPayments += $payment["value"];
            $counter++;
        }
        return $totalPayments;
    } 

    private function createPayments(array $payments, int $counter, int $proposalId, int $contractId = null) {
        foreach ($payments as $payment) {
            error_log("creating");
            unset($payment["id"]);
            if (strcmp($payment["source"], "Banco") === 0) {
                unset($payment["desired_date"]);
            }
            if (strcmp($payment["source"], "Cliente") === 0) {
                unset($payment["bank"]);
            }
            if (isset($payment["desired_date"]) && (empty($payment["desired_date"]) || is_null($payment["desired_date"]))) {
                unset($payment['desired_date']);
            }
            $payment["code"] = $counter . "" . date('d') . date('m') . date('Y') . random_int(10, 99) . "3MPGT";
            $payment["status"] = 0;
            $payment["proposal_id"] = $proposalId;
            $payment = (new ProposalPaymentBusiness())->create(data: $payment);
            $counter++;
            if (!is_null($contractId)) {
                (new ContractBusiness())->createBillToReceiveForTheContract(proposalPayment: $payment, contractId: $contractId);
            }
        }
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de proposta.");
        $data = $this->validateProposalRequest(request: $request);

        // Creating the entities : Address, Proposal and ProposalPayments
        $newAddress = (new AddressBusiness)->create(data: $data);
        
        Logger::info("Salvando a nova proposta.");
        $proposal = new proposal($data);
        $proposal->address_id = $newAddress->id;
        $proposal->save();

        Logger::info("Salvando os novos pagamentos da proposta.");
        $counter = 0;
        $this->createPayments(payments: $data["clientPayments"], counter: $counter, proposalId: $proposal->id);
        $this->createPayments(payments: $data["bankPayments"], counter: $counter, proposalId: $proposal->id);

        Logger::info("Finalizando a atualização de proposta.");
        return $proposal;
    }

    public function update(int $id, Request $request) {
        Logger::info("Iniciando a criação de proposta.");
        Logger::info("Validando as informações fornecidas.");
        $data = $this->validateProposalRequest(request: $request, id: $id);
        $proposal = $this->getById(id: $id, mergeFields: false);

        // Updating the entities : Address, Proposal and ProposalPayments
        (new AddressBusiness())->update(id: $data["address_id"], data: $data);

        Logger::info("Salvando informações da proposta.");
        $proposal = UpdateUtils::updateFields(Proposal::$fieldsToBeUpdated, $proposal, $data);
        $proposal->save();

        // Checking if there is contract associated
        Logger::info("Verificando se existe contrato associado a proposta.");
        $contract = null;
        $contractsList = (new ContractBusiness())->getByProposalId(id: $id);
        if (count($contractsList) > 0) {
            Logger::info("Excluindo antigos pagamentos do contrato.");
            $contract = $contractsList[0];
            (new BillReceiveBusiness())->deleteByContractId(contractId: $contract->id);
        }

        Logger::info("Excluindo os pagamentos da proposta.");
        (new ProposalPaymentBusiness())->deleteByProposalId(proposalId: $id);

        Logger::info("Atualizando pagamentos da proposta.");
        $contractId = is_null($contract) ? null : $contract->id;
        $counter = 0;
        $this->createPayments(payments: $data["clientPayments"], counter: $counter, proposalId: $id, contractId: $contractId);
        $this->createPayments(payments: $data["bankPayments"], counter: $counter, proposalId: $id, contractId: $contractId);

        // Update the contract global value
        if (!is_null($contract)) {
            Logger::info("Atualizando valor global do contrato.");
            $contractValue = $data["global_value"] - $data["discount"];
            (new ContractBusiness())->changeGlobalValue(id: $contract->id, globalValue: $contractValue);
        }

        Logger::info("Finalizando a atualização de proposta.");
        return $proposal;
    }

    public function reject(int $id) {
        Logger::info("Rejeitando uma proposta $id.");
        $proposal = $this->getById(id: $id, mergeFields: false);
        $proposal->status = 1;
        $proposal->save();
        Logger::info("Finalizando rejeição de proposta $id.");
        return $proposal;
    }

    public function approve(int $id) {
        Logger::info("Aprovando uma proposta $id.");
        $proposal = $this->getById(id: $id, mergeFields: false);
        $proposal->status = 2;
        $proposal->save();
        Logger::info("Finalizando aprovação de proposta $id.");
        return $proposal;
    }

    public function setStatusIcon(Proposal $proposal) {
        if ($proposal->status === 0) {
            $proposal["icon"] = "access_time";
            $proposal["icon_color"] = "gray";
        }
        if ($proposal->status === 1) {
            $proposal["icon"] = "thumb_down";
            $proposal["icon_color"] = "red";
        }
        if ($proposal->status === 2) {
            $proposal["icon"] = "thumb_up";
            $proposal["icon_color"] = "green";
        }
    }

}