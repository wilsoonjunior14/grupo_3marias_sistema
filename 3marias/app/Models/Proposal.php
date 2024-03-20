<?php

namespace App\Models;

class Proposal extends BaseModel
{
    protected $table = "proposals";
    protected $fillable = ["id", "construction_type", "proposal_type", 
    "global_value", "proposal_date", "description", "discount",
    "status", "code",
    "address_id", "client_id", "project_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = [ "construction_type", "status", "proposal_type", 
    "global_value", "proposal_date", "description", "discount",
    "address_id", "client_id", "project_id"];

    static $rules = [
        'code' => 'required|max:100|min:3',
        'construction_type' => 'required|max:100|min:3|string',
        'proposal_type' => 'required|max:100|min:3|string',
        'global_value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'proposal_date' => 'required|date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'description' => 'required|max:1000|min:3|string',
        'discount' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'address_id' => 'required',
        'client_id' => 'required',
        'project_id' => 'required|integer',
        'status' => 'required|in:0,1,2'
    ];

    static $rulesMessages = [
        'code.required' => 'Campo Código da Proposta é obrigatório.',
        'code.max' => 'Campo Código da Proposta permite no máximo 100 caracteres.',
        'code.min' => 'Campo Código da Proposta deve conter no mínimo 3 caracteres.',
        'construction_type.required' => 'Campo Tipo de Construção da Proposta é obrigatório.',
        'construction_type.max' => 'Campo Tipo de Construção da Proposta permite no máximo 100 caracteres.',
        'construction_type.min' => 'Campo Tipo de Construção da Proposta deve conter no mínimo 3 caracteres.',
        'construction_type.string' => 'Campo Tipo de Construção da Proposta está inválido.',
        'proposal_type.required' => 'Campo Tipo da Proposta é obrigatório.',
        'proposal_type.max' => 'Campo Tipo da Proposta permite no máximo 100 caracteres.',
        'proposal_type.min' => 'Campo Tipo da Proposta deve conter no mínimo 3 caracteres.',
        'proposal_type.string' => 'Campo Tipo da Proposta está inválido.',
        'global_value.required' => 'Campo Valor Global da Proposta é obrigatório.',
        'global_value.regex' => 'Campo Valor Global da Proposta está inválido.',
        'proposal_date.required' => 'Campo Data da Proposta é obrigatório.',
        'proposal_date.date' => 'Campo Data da Proposta está inválido.',
        'proposal_date.regex' => 'Campo Data da Proposta está inválido.',
        'description.required' => 'Campo Descrição da Proposta é obrigatório.',
        'description.max' => 'Campo Descrição da Proposta permite no máximo 1000 caracteres.',
        'description.min' => 'Campo Descrição da Proposta deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição da Proposta está inválido.',
        'discount.required' => 'Campo Valor do Desconto da Proposta é obrigatório.',
        'discount.regex' => 'Campo Valor do Desconto da Proposta está inválido.',
        'address_id.required' => 'Campo Identificador do Endereço é obrigatório.',
        'client_id.required' => 'Campo Identificador do Cliente é obrigatório.',
        'project_id.required' => 'Campo Identificador do Projeto é obrigatório.',
        'project_id.integer' => 'Campo Identificador do Projeto está inválido.',
        'status.required' => 'Campo Status da Proposta é obrigatório.',
        'status.in' => 'Campo Status da Proposta está inválido.'
    ];

    public function getByClientId(int $clientId) {
        return $this::where("deleted", false)
        ->where("client_id", $clientId)
        ->get();
    }
}
