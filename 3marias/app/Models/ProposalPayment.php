<?php

namespace App\Models;

class ProposalPayment extends BaseModel
{
    protected $table = "proposal_payments";
    protected $fillable = ["id", "type", "value", "code", "description",
    "source", "desired_date", "proposal_id", "bank", "status",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["type", "value", "description", "bank",
    "source", "desired_date", "proposal_id"];

    static $rules = [
        'code' => 'required|max:100|min:3',
        'type' => 'required|max:100|min:3|string',
        'bank' => 'max:100|min:3',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'description' => 'required|max:255|min:3',
        'source' => 'required|string|in:Cliente,Banco',
        'desired_date' => 'date',
        'proposal_id' => 'required',
        'status' => 'required|in:0,1,2'
    ];

    static $rulesMessages = [
        'code.required' => 'Campo Código do Pagamento é obrigatório.',
        'code.max' => 'Campo Código do Pagamento permite no máximo 100 caracteres.',
        'code.min' => 'Campo Código do Pagamento deve conter no mínimo 3 caracteres.',
        'type.required' => 'Campo Tipo de Pagamento é obrigatório.',
        'type.max' => 'Campo Tipo de Pagamento permite no máximo 100 caracteres.',
        'type.min' => 'Campo Tipo de Pagamento deve conter no mínimo 3 caracteres.',
        'type.string' => 'Campo Tipo de Pagamento está inválido.',
        'bank.max' => 'Campo Banco permite no máximo 100 caracteres.',
        'bank.min' => 'Campo Banco deve conter no mínimo 3 caracteres.',
        'value.required' => 'Campo Valor do Pagamento é obrigatório.',
        'value.regex' => 'Campo Valor do Pagamento está inválido.',
        'description.required' => 'Campo Descrição do Pagamento é obrigatório.',
        'description.max' => 'Campo Descrição do Pagamento permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Pagamento deve conter no mínimo 3 caracteres.',
        'source.required' => 'Campo Fonte do Pagamento é obrigatório.',
        'source.in' => 'Campo Fonte do Pagamento é inválido.',
        'source.string' => 'Campo Fonte do Pagamento está inválido.',
        'desired_date.date' => 'Campo Data Prevista de Pagamento está inválido.',
        'proposal_id.required' => 'Campo Identificador da Proposta é obrigatório.',
        'status.required' => 'Campo Status do Pagamento é obrigatório.',
        'status.in' => 'Campo Status do Pagamento está inválido.'
    ];

    public function getByProposalId(int $id) {
        return (new ProposalPayment())->where("deleted", false)
        ->where("proposal_id", $id)
        ->get();
    }
}
