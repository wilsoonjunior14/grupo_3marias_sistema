<?php

namespace App\Models;

class BillTicket extends BaseModel
{
    protected $table = "bill_tickets";
    protected $fillable = ["id", "value", "description", "date", 
    "bill_receive_id", "bill_pay_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = [];

    static $rules = [
        'description' => 'required|max:255|min:3|string',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'date' => 'required|date|string|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'bill_receive_id' => 'integer|gt:0|exists:bill_receives,id',
        'bill_pay_id' => 'integer|gt:0|exists:bill_pays,id'
    ];

    static $rulesMessages = [
        'description.required' => 'Campo Descrição do Pagamento é obrigatório.',
        'description.max' => 'Campo Descrição do Pagamento permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Pagamento deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição do Pagamento está inválido.',
        'value.required' => 'Campo Valor do Pagamento é obrigatório.',
        'value.regex' => 'Campo Valor do Pagamento está inválido.',
        'date.required' => 'Campo Data de Pagamento do Recibo é obrigatório.',
        'date.date' => 'Campo Data de Pagamento do Recibo está inválido.',
        'date.regex' => 'Campo Data de Pagamento do Recibo está inválido.',
        'date.string' => 'Campo Data de Pagamento do Recibo está inválido.',
        'bill_receive_id.integer' => 'Campo Identificador de Conta a Receber é obrigatório.',
        'bill_receive_id.gt' => 'Campo Identificador de Conta a Receber está inválido.',
        'bill_receive_id.exists' => 'Campo Identificador de Conta a Receber está inválido.',
        'bill_pay_id.integer' => 'Campo Identificador de Conta a Pagar é obrigatório.',
        'bill_pay_id.gt' => 'Campo Identificador de Conta a Pagar está inválido.',
        'bill_pay_id.exists' => 'Campo Identificador de Conta a Pagar está inválido.'
    ];

    public function getByBillReceiveId(int $id) {
        return (new BillTicket())
            ->where("deleted", false)
            ->where("bill_receive_id", $id)
            ->orderBy("date")
            ->get();
    }

    public function getByBillPayId(int $id) {
        return (new BillTicket())
            ->where("deleted", false)
            ->where("bill_pay_id", $id)
            ->orderBy("date")
            ->get();
    }

    public function withDescrition($description) {
        $this->description = $description; // @phpstan-ignore-line
        return $this;
    }

    public function withValue($value) {
        $this->value = $value; // @phpstan-ignore-line
        return $this;
    }

    public function withDate($date) {
        $this->date = $date; // @phpstan-ignore-line
        return $this;
    }

    public function withBillReceiveId($bill_receive_id) {
        $this->bill_receive_id = $bill_receive_id; // @phpstan-ignore-line
        return $this;
    }

    public function withBillPayId($bill_pay_id) {
        $this->bill_pay_id = $bill_pay_id; // @phpstan-ignore-line
        return $this;
    }
}
