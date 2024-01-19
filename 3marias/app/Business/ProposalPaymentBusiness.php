<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\ProposalPayment;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ProposalPaymentBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de pagamentos de propostas.");
        $payments = (new ProposalPayment())->getAll("created_at");
        $amount = count($payments);
        Logger::info("Foram recuperados {$amount} pagamentos de propostas.");
        Logger::info("Finalizando a recuperação de pagamentos de propostas.");
        return $payments;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de pagamento de proposta $id.");
        $payment = (new ProposalPayment())->getById($id);
        Logger::info("Finalizando a recuperação de pagamento de proposta $id.");
        return $payment;
    }

    public function getByProposalId(int $proposalId) {
        Logger::info("Iniciando a recuperação de pagamentos de uma proposta $proposalId.");
        $payments = (new ProposalPayment())->getByProposalId($proposalId);
        Logger::info("Finalizando a recuperação de pagamentos de uma proposta $proposalId.");
        return $payments;
    }

    public function delete(int $id) {
        $payment = $this->getById(id: $id);
        Logger::info("Deletando o de pagamento de proposta $id.");
        $payment->deleted = true;
        $payment->save();
        return $payment;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação de proposta.");
        Logger::info("Validando as informações fornecidas.");

        $this->validatePayment($data);
        
        Logger::info("Salvando a nova proposta.");
        $proposal = new proposalPayment($data);
        $proposal->save();
        Logger::info("Finalizando a atualização de proposta.");
        return $proposal;
    }

    public function update(int $id, Request $request) {
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

    public function validatePayment(array $data, $excludeProposalId = false) {
        $rules = ProposalPayment::$rules;
        $rulesMessages = ProposalPayment::$rulesMessages;

        if ($excludeProposalId) {
            unset($rules['proposal_id']);
            unset($rulesMessages['proposal_id.required']);
        }

        $proposalValidator = new ModelValidator($rules, $rulesMessages);
        $validation = $proposalValidator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }
    }

}