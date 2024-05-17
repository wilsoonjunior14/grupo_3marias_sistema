<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\BillPay;
use App\Models\BillReceive;
use App\Models\BillTicket;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;

class BillReceiveBusiness {

    public function getByContract(int $id) {
        Logger::info("Iniciando a recuperação dos pagamentos a receber por contrato $id.");
        $bills = (new BillReceive())->getByContractId(id: $id);
        return $bills;
    }

    public function getAll() {
        Logger::info("Iniciando a recuperação dos pagamentos.");
        $bills = (new BillReceive())->getAll("desired_date");

        foreach ($bills as $bill) {
            $bill["contract"] = (new ContractBusiness())->getById(id: $bill->contract_id);
            $this->setStatusIcon(bill: $bill);
        }

        Logger::info("Finalizando a recuperação dos pagamentos.");
        return $bills;
    }

    public function getBillsInProgress() {
        Logger::info("Iniciando a recuperação dos pagamentos.");
        $receiveBills = (new BillReceive())->getBillsNotDone();
        $payBills = (new BillPay())->getBillsNotDone();
        $bills = (new BillReceive())->getBillsInProgress();
        foreach ($bills as $bill) {
            $bill["contract"] = (new ContractBusiness())->getById(id: $bill->contract_id);
        }

        Logger::info("Finalizando a recuperação dos pagamentos.");
        $receiveValuePaid = (new BillReceive())->getValueAlreadyPaid();
        $payValuePaid = (new BillPay())->getValueAlreadyPaid();
        $enterpriseValue = $receiveValuePaid - $payValuePaid;
        return [
            'toReceiveValue' => $receiveBills - $receiveValuePaid,
            'toPayValue' => $payBills - $payValuePaid,
            'value' => $enterpriseValue,
            'bills' => $bills
        ];
    }

    public function getById($id, bool $mergeFields = false) {
        Logger::info("Iniciando a recuperação do pagamento.");
        try {
            $bill = (new BillReceive())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Pagamento a Receber"));
        }
        if (!$mergeFields) {
            Logger::info("Finalizando a recuperação do pagamento.");
            return $bill;
        }   
        
        $bill["tickets"] = (new BillTicketBusiness())->getByBillReceive(billReceiveId: $id);
        return $bill;
    }

    public function deleteByContractId(int $contractId) {
        Logger::info("Iniciando a recuperação dos pagamentos por contrato.");
        $bills = (new BillReceive())->getByContractId(id: $contractId);
        foreach ($bills as $bill) {
            $bill->deleted = true;
            $bill->save();
        }
        Logger::info("Finalizando a exclusão dos pagamentos.");
        return $bills;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação do pagamento.");
        Logger::info("Salvando a pagamento.");
        $payment = new BillReceive($data);
        $payment->save();
        Logger::info("Finalizando a atualização do pagamento.");
        return $payment;
    }

    public function refreshBillReceive(int $id) {
        Logger::info("Atualizando Conta a receber $id.");
        $billReceive = $this->getById(id: $id, mergeFields: false);
        $tickets = (new BillTicketBusiness())->getByBillReceive(billReceiveId: $id);
        $billReceive->value_performed = 0;
        foreach ($tickets as $ticket) {
            $billReceive->value_performed += $ticket->value;
        }
        if ($billReceive->value == $billReceive->value_performed) {
            $billReceive->status = 1;
        }
        $billReceive->save();
    }

    public function update(int $id, array $data) {
        $bill = $this->getById(id: $id);
        $bill = UpdateUtils::updateFields(BillReceive::$fieldsToBeUpdated, $bill, $data);

        Logger::info("Validando as informações do pagamento.");
        $rules = BillReceive::$rules;
        unset($rules["status"]);

        $validation = new ModelValidator($rules, BillReceive::$rulesMessages);
        $errors = $validation->validate(data: $data);
        if (!is_null($errors)) {
            throw new InputValidationException($errors);
        }
        if ($bill["value_performed"] < 0 || $bill["value_performed"] > $bill["value"]) {
            throw new InputValidationException("Valor já pago inválido. Não pode ser negativo ou superior ao valor global do pagamento.");
        }

        if (strval($bill["value"]) === strval($bill["value_performed"])) {
            $bill["status"] = 1;
        }

        Logger::info("Atualizando as informações do pagamento.");
        $bill->save();
        return $bill;
    }

    public function setStatusIcon(BillReceive $bill) {
        if ($bill->status === 0) {
            $bill["icon"] = "access_time";
            $bill["icon_color"] = "gray";
        }
        if ($bill->status === 1) {
            $bill["icon"] = "done";
            $bill["icon_color"] = "green";
        }
    }
}