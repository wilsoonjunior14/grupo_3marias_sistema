<?php

namespace App\Models;

class BillReceive extends BaseModel
{
    protected $table = "bill_receives";
    protected $fillable = ["id", "type", "value", "value_performed", "code", "description",
    "source", "desired_date", "bank", "status",
    "contract_id", "purchase_order_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["value_performed", "description", "desired_date"];

    static $rules = [
        'code' => 'required|max:100|min:3',
        'type' => 'required|max:100|min:3',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'value_performed' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'description' => 'required|max:255|min:3',
        'source' => 'required|max:100|min:3',
        'desired_date' => 'date',
        'bank' => 'max:100|min:3',
        'contract_id' => 'integer',
        'purchase_order_id' => 'integer',
        'status' => 'required|in:0,1'
    ];

    static $rulesMessages = [
        'code.required' => 'Campo Código do Pagamento é obrigatório.',
        'code.max' => 'Campo Código do Pagamento permite no máximo 100 caracteres.',
        'code.min' => 'Campo Código do Pagamento deve conter no mínimo 3 caracteres.',
        'type.required' => 'Campo Tipo de Pagamento é obrigatório.',
        'type.max' => 'Campo Tipo de Pagamento permite no máximo 100 caracteres.',
        'type.min' => 'Campo Tipo de Pagamento deve conter no mínimo 3 caracteres.',
        'bank.max' => 'Campo Banco permite no máximo 100 caracteres.',
        'bank.min' => 'Campo Banco deve conter no mínimo 3 caracteres.',
        'value.required' => 'Campo Valor do Pagamento é obrigatório.',
        'value_performed.required' => 'Campo Valor do Pagamento é obrigatório.',
        'value.regex' => 'Campo Valor do Pagamento está inválido.',
        'value_performed.regex' => 'Campo Valor Realizado do Pagamento está inválido.',
        'description.required' => 'Campo Descrição do Pagamento é obrigatório.',
        'description.max' => 'Campo Descrição do Pagamento permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Pagamento deve conter no mínimo 3 caracteres.',
        'source.required' => 'Campo Fonte do Pagamento é obrigatório.',
        'source.max' => 'Campo Fonte do Pagamento permite no máximo 100 caracteres.',
        'source.min' => 'Campo Fonte do Pagamento deve conter no mínimo 3 caracteres.',
        'desired_date.date' => 'Campo Data Prevista de Pagamento está inválido.',
        'contract_id.integer' => 'Campo Identificador do Contrato está inválido.',
        'purchase_order_id.integer' => 'Campo Identificador da Compra está inválido.',
        'status.required' => 'Campo Status do Pagamento é obrigatório.',
        'status.in' => 'Campo Status do Pagamento está inválido.'
    ];

    public function getByContractId(int $id) {
        return (new BillReceive())->where("deleted", false)
        ->where("contract_id", $id)
        ->get();
    }

    public function getBillsNotDone() {
        return (new BillReceive())->where("deleted", false)
        ->where("status", 0)
        ->orderBy("desired_date")
        ->get();
    }

    public function getValueAlreadyPaid() {
        return (new BillReceive())->where("deleted", false)
        ->where("status", 1)
        ->sum('value_performed');
    }
}
