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

    public function create(array $data) {
        Logger::info("Iniciando a criação do pagamento.");
        Logger::info("Salvando a pagamento.");
        $payment = new BillReceive($data);
        $payment->save();
        Logger::info("Finalizando a atualização do pagamento.");
        return $payment;
    }
}