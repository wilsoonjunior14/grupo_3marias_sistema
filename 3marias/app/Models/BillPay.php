<?php

namespace App\Models;

class BillPay extends BaseModel
{
    protected $table = "bill_pays";
    protected $fillable = ["id", "code", "value", "value_performed", "description",
    "status", "service_orders_id", "purchase_orders_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["code", "value_performed", "description", "status"];

    static $rules = [
        'code' => 'required|max:100|min:3',
        'type' => 'required|max:100|min:3',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'value_performed' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'description' => 'required|max:255|min:3',
        'service_orders_id' => 'integer|gt:0',
        'purchase_orders_id' => 'integer|gt:0',
        'status' => 'required|in:0,1'
    ];

    static $rulesMessages = [
        'code.required' => 'Campo Código do Pagamento é obrigatório.',
        'code.max' => 'Campo Código do Pagamento permite no máximo 100 caracteres.',
        'code.min' => 'Campo Código do Pagamento deve conter no mínimo 3 caracteres.',
        'value.required' => 'Campo Valor do Pagamento é obrigatório.',
        'value_performed.required' => 'Campo Valor do Pagamento é obrigatório.',
        'value.regex' => 'Campo Valor do Pagamento está inválido.',
        'value_performed.regex' => 'Campo Valor Realizado do Pagamento está inválido.',
        'description.required' => 'Campo Descrição do Pagamento é obrigatório.',
        'description.max' => 'Campo Descrição do Pagamento permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Pagamento deve conter no mínimo 3 caracteres.',
        'service_orders.integer' => 'Campo Identificador da Compra está inválido.',
        'service_orders.gt' => 'Campo Identificador da Compra está inválido.',
        'purchase_orders_id.integer' => 'Campo Identificador da Compra está inválido.',
        'purchase_orders_id.gt' => 'Campo Identificador da Compra está inválido.',
        'status.required' => 'Campo Status do Pagamento é obrigatório.',
        'status.in' => 'Campo Status do Pagamento está inválido.'
    ];

    public function getByServiceId(int $id) {
        return (new BillPay())
            ->where("deleted", false)
            ->where("service_orders_id", $id)
            ->get();
    }

    public function getByPurchaseId(int $id) {
        return (new BillPay())
            ->where("deleted", false)
            ->where("purchase_orders_id", $id)
            ->get();
    }

    public function getValueAlreadyPaid() {
        return (new BillPay())->where("deleted", false)
        ->sum('value_performed');
    }

    public function getBillsNotDone() {
        return (new BillPay())->where("deleted", false)
        ->get()
        ->sum('value');
    }
}
