<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Proposal;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ProposalBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de propostas.");
        $proposals = (new Proposal())->getAll("proposal_date");
        $amount = count($proposals);

        foreach ($proposals as $proposal) {
            $proposal["payments"] = (new ProposalPaymentBusiness())->getByProposalId(proposalId: $proposal->id);
            $proposal["icon"] = $this->getStatusIcon(status: $proposal->status);
        }

        Logger::info("Foram recuperados {$amount} propostas.");
        Logger::info("Finalizando a recuperação de propostas.");
        return $proposals;
    }

    public function getById(int $id, bool $mergeFields = true) {
        Logger::info("Iniciando a recuperação de proposta $id.");
        $proposal = (new Proposal())->getById($id);
        if ($mergeFields) {
            $proposal->client = (new ClientBusiness())->getById(id: $proposal->client_id);
            $proposal->payments = (new ProposalPaymentBusiness())->getByProposalId(proposalId: $proposal->id);
        }
        Logger::info("Finalizando a recuperação de proposta $id.");
        return $proposal;
    }

    public function delete(int $id) {
        $proposal = $this->getById(id: $id);
        Logger::info("Deletando o de proposta $id.");

        // TODO: NEED DELETE THE ALL PAYMENTS ASSOCIATED TO THIS PROPOSAL

        $proposal->deleted = true;
        $proposal->save();
        return $proposal;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de proposta.");
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
        $data["client_id"] = $client->id;
        $data["code"] = count($this->get()) . "" . date('Y') . "" . date('m') . "" . time() . "P";
        $data["status"] = 0;

        $rules = Proposal::$rules;
        $rulesMessages = Proposal::$rulesMessages;
        unset($rules["address_id"]);
        unset($rulesMessages["address_id.required"]);
        $proposalValidator = new ModelValidator($rules, $rulesMessages);
        $validation = $proposalValidator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        // Validating the address.
        $addressBusiness = new AddressBusiness();
        $addressBusiness->validateData(data: $data);

        // Validating the proposal payments.
        if ( (!isset($data["clientPayments"]) && !isset($data["bankPayments"])) ) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_NOT_PROVIDED, "Lista de Pagamentos"));
        }
        if ( (empty($data["clientPayments"]) && empty($data["bankPayments"])) ) {
            throw new InputValidationException("Lista de Pagamentos vazias");
        }
        $proposalPaymentBusiness = new ProposalPaymentBusiness();
        $totalPayments = 0;
        $counter = 0;
        foreach ($data["clientPayments"] as $payment) {
            $payment["code"] = $counter . "" . time() . "GT";
            $payment["status"] = 0;
            $proposalPaymentBusiness->validatePayment(data: $payment, excludeProposalId: true);
            $totalPayments += $payment["value"];
            $counter++;
        }
        foreach ($data["bankPayments"] as $payment) {
            $payment["code"] = $counter . "" . time() . "GT";
            $payment["status"] = 0;
            $proposalPaymentBusiness->validatePayment(data: $payment, excludeProposalId: true);
            $totalPayments += $payment["value"];
            $counter++;
        }

        // Validating the global value with discount and payments
        $globalValue = $data["global_value"] - $data["discount"];
        if ($globalValue !== $totalPayments) {
            $diff = $globalValue - $totalPayments;
            throw new InputValidationException("O valor global da proposta diverge dos valores dos pagamentos fornecidos. Diferença de R$ $diff");
        }

        // Creating the entities : Address, Proposal and ProposalPayments
        $newAddress = $addressBusiness->create(data: $data);
        
        Logger::info("Salvando a nova proposta.");
        $proposal = new proposal($data);
        $proposal->address_id = $newAddress->id;
        $proposal->save();

        $counter = 0;
        foreach ($data["clientPayments"] as $payment) {
            $payment["code"] = $counter . "" . time() . "GT";
            $payment["status"] = 0;
            $payment["proposal_id"] = $proposal->id;
            $proposalPaymentBusiness->create(data: $payment);
            $counter++;
        }
        foreach ($data["bankPayments"] as $payment) {
            $payment["code"] = $counter . "" . time() . "GT";
            $payment["status"] = 0;
            $payment["proposal_id"] = $proposal->id;
            $proposalPaymentBusiness->create(data: $payment);
            $counter++;
        }

        Logger::info("Finalizando a atualização de proposta.");
        return $proposal;
    }

    public function update(int $id, Request $request) {
        // TODO: NEED MORE TIME TO WORK ON THAT.

        // Logger::info("Alterando informações do proposta.");
        // $proposal = (new proposal())->getById($id);
        // $proposalUpdated = UpdateUtils::processFieldsToBeUpdated($proposal, $request->all(), proposal::$fieldsToBeUpdated);
        
        // Logger::info("Validando as informações do proposta.");
        // $proposalValidator = new ModelValidator(proposal::$rules, proposal::$rulesMessages);
        // $proposalValidator->validate($proposalUpdated);

        // Logger::info("Atualizando as informações do proposta.");
        // $proposalUpdated->save();
        // return $this->getById(id: $proposalUpdated->id);
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

    public function getStatusIcon(int $status) {
        if ($status === 0) {
            return "access_time";
        }
        if ($status === 1) {
            return "thumb_down";
        }
        if ($status === 2) {
            return "thumb_up";
        }
    }

}