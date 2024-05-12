<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\BillPay;
use App\Models\BillReceive;
use App\Models\BillTicket;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class BillTicketBusiness {

    public function getAll() {
        Logger::info("Iniciando a recuperação dos recibos.");
        $bills = (new BillTicket())->getAll("date");
        Logger::info("Finalizando a recuperação dos recibos.");
        return $bills;
    }

    public function getByBillReceive(int $billReceiveId) {
        Logger::info("Iniciando a recuperação dos recibos.");
        $bills = (new BillTicket())->getByBillReceiveId(id: $billReceiveId);
        Logger::info("Finalizando a recuperação dos recibos.");
        return $bills;
    }

    public function getByBillPay(int $billPayId) {
        Logger::info("Iniciando a recuperação dos recibos.");
        $bills = (new BillTicket())->getByBillPayId(id: $billPayId);
        Logger::info("Finalizando a recuperação dos recibos.");
        return $bills;
    }

    public function getById($id) {
        Logger::info("Iniciando a recuperação do recibo.");
        try {
            $bill = (new BillTicket())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Recibo de Conta de Pagamento"));
        }      
        Logger::info("Finalizando a recuperação do recibo.");
        return $bill;
    }

    public function delete(int $id) {
        Logger::info("Iniciando a recuperação dos pagamentos por contrato.");
        $bill = $this->getById(id: $id);
        $bill->deleted = true;
        $bill->save();
        if (!is_null($bill->bill_receive_id)) {
            (new BillReceiveBusiness())->refreshBillReceive(id: $bill->bill_receive_id);
        }
        Logger::info("Finalizando a exclusão dos pagamentos.");
        return $bill;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação do pagamento.");
        $bill = new BillTicket($data);
        Logger::info("Validação dos dados fornecidos.");
        $this->validate(data: $data);

        Logger::info("Salvando o pagamento.");
        $bill->save();

        if (!is_null($bill->bill_pay_id)) {
            (new BillPayBusiness())->performBillTicket(ticket: $bill);
        }
        if (!is_null($bill->bill_receive_id)) {
            (new BillReceiveBusiness())->refreshBillReceive(id: $bill->bill_receive_id);
        }

        Logger::info("Finalizando a atualização do pagamento.");
        return $bill;
    }

    private function validate(array $data) {
        $payment = new BillTicket($data);
        Logger::info("Validação dos dados fornecidos.");
        $payment->validate(BillTicket::$rules, BillTicket::$rulesMessages);

        $existsBillReceive = isset($data["bill_receive_id"]) && !empty($data["bill_receive_id"]);
        $existsBillPay = isset($data["bill_pay_id"]) && !empty($data["bill_pay_id"]);

        if (!$existsBillReceive && !$existsBillPay){
            throw new InputValidationException("Conta de Pagamento não informada.");
        }
        if ($existsBillReceive && $existsBillPay) {
            throw new InputValidationException("Somente um tipo de conta de pagamento pode ser informada.");
        }
        // only to check if it is not deleted
        if ($existsBillReceive) {
            $billReceive = (new BillReceiveBusiness())->getById(id: $data["bill_receive_id"]);
            if ($billReceive->value < $payment->value || $billReceive->value < $payment->value + $billReceive->value_performed) {
                throw new InputValidationException("Valor do recibo inválido. O valor informado é superior ao valor da conta a receber.");
            }
        }
        if ($existsBillPay) {
            $billPay = (new BillPayBusiness())->getById(id: $data["bill_pay_id"]);
            if ($billPay->value < $payment->value || $billPay->value < $payment->value + $billPay->value_performed) {
                throw new InputValidationException("Valor do recibo inválido. O valor informado é superior ao valor da conta a pagar.");
            }
        }
    }
}