<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\BillReceive;
use App\Models\ProposalPayment;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

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
        $bills = (new BillReceive())->getBillsNotDone();
        Logger::info("Finalizando a recuperação dos pagamentos.");
        return $bills;
    }

    public function getById($id) {
        Logger::info("Iniciando a recuperação do pagamento.");
        $bill = (new BillReceive())->getById(id: $id);
        Logger::info("Finalizando a recuperação do pagamento.");
        return $bill;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação do pagamento.");
        Logger::info("Salvando a pagamento.");
        $payment = new BillReceive($data);
        $payment->save();
        Logger::info("Finalizando a atualização do pagamento.");
        return $payment;
    }

    public function update(int $id, Request $request) {
        $bill = $this->getById(id: $id);
        $data = $request->all();

        $bill = UpdateUtils::updateFields(BillReceive::$fieldsToBeUpdated, $bill, $data);

        Logger::info("Validando as informações do pagamento.");
        $rules = BillReceive::$rules;
        unset($rules["status"]);

        $validation = new ModelValidator($rules, BillReceive::$rulesMessages);
        $errors = $validation->validate(data: $request->all());
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