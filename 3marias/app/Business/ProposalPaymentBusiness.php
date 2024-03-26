<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\ProposalPayment;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ProposalPaymentBusiness {

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

    public function deleteByProposalId(int $proposalId) {
        $payments = $this->getByProposalId(proposalId: $proposalId);
        Logger::info("Deletando pagamentos da proposta $proposalId."); 
        foreach ($payments as $payment) {
            $payment->deleted = true;
            $payment->code = "deleted" . time() . $payment->code;
            $payment->save();
        } 
        Logger::info("Finalizando exclusão de pagamentos da proposta $proposalId.");   
        return $payments; 
    }

    public function delete(int $id) {
        Logger::info("Buscando o pagamento da proposta para excluir.");
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

        if ($data["value"] <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_INVALID, "Valor de Pagamento de ". $data["source"]));
        } 
    }

}