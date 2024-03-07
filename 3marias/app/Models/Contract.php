<?php

namespace App\Models;

class Contract extends BaseModel
{
    protected $table = "contracts";
    protected $fillable = ["id", 
    "code", "building_type", "description", "meters",
    "value", "witness_one_name", "witness_one_cpf",
    "witness_two_name", "witness_two_cpf", "date",
    "address_id", "proposal_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["code", "building_type", "description", "meters",
    "value", "witness_one_name", "witness_one_cpf", "date",
    "witness_two_name", "witness_two_cpf",
    "address_id", "proposal_id"];

    static $rules = [
        'code' => 'bail|required|max:255|min:3',
        'building_type' => 'bail|string|required|max:255|min:3',
        'description' => 'bail|string|required|max:1000|min:3',
        'meters' => 'bail|string|required|max:1000|min:3',
        'value' => 'required|',
        'date' => 'required|date',
        'witness_one_name' => 'bail|string|required|max:255|min:3',
        'witness_one_cpf' => 'required|string|cpf',
        'witness_two_name' => 'bail|string|required|max:255|min:3|different:witness_one_name',
        'witness_two_cpf' => 'required|string|cpf|different:witness_one_cpf',
        'address_id' => 'required|integer',
        'proposal_id' => 'required|integer',
    ];

    static $rulesMessages = [
        'code.required' => 'Campo Código do Contrato é obrigatório.',
        'code.max' => 'Campo Código do Contrato permite no máximo 255 caracteres.',
        'code.min' => 'Campo Código do Contrato deve conter no mínimo 3 caracteres.',
        'building_type.required' => 'Campo Tipo de Construção é obrigatório.',
        'building_type.max' => 'Campo Tipo de Construção permite no máximo 255 caracteres.',
        'building_type.min' => 'Campo Tipo de Construção deve conter no mínimo 3 caracteres.',
        'building_type.string' => 'Campo Tipo de Construção está inválido.',
        'description.required' => 'Campo Descrição da Obra é obrigatório.',
        'description.max' => 'Campo Descrição da Obra permite no máximo 1000 caracteres.',
        'description.min' => 'Campo Descrição da Obra deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição está inválido.',
        'meters.required' => 'Campo Metros Quadrados da Obra é obrigatório.',
        'meters.max' => 'Campo Metros Quadrados da Obra permite no máximo 1000 caracteres.',
        'meters.min' => 'Campo Metros Quadrados da Obra deve conter no mínimo 3 caracteres.',
        'meters.string' => 'Campo Metros Quadrados da Obra está inválido.',
        'value.required' => 'Campo Valor do Contrato é obrigatório.',
        'value.regex' => 'Campo Valor do Contrato está inválido.',
        'date.required' => 'Campo Data de Assinatura do Contrato é obrigatório.',
        'date.date' => 'Campo de Data de Assinatura do Contrato é inválido.',
        'witness_one_name.required' => 'Campo Nome da Primeira Testemunha é obrigatório.',
        'witness_one_name.max' => 'Campo Nome da Primeira Testemunha permite no máximo 255 caracteres.',
        'witness_one_name.min' => 'Campo Nome da Primeira Testemunha deve conter no mínimo 3 caracteres.',
        'witness_one_name.string' => 'Campo Nome da Primeira Testemunha está inválido.',
        'witness_one_cpf.required' => 'Campo CPF da Primeira Testemunha é obrigatório.',
        'witness_one_cpf.cpf' => 'Campo CPF da Primeira Testemunha é inválido.',
        'witness_one_cpf.string' => 'Campo CPF da Primeira Testemunha está inválido.',
        'witness_two_cpf.cpf' => 'Campo CPF da Segunda Testemunha é inválido.',
        'witness_two_cpf.required' => 'Campo CPF da Segunda Testemunha é obrigatório.',
        'witness_two_cpf.string' => 'Campo CPF da Segunda Testemunha está inválido.',
        'witness_two_cpf.different' => 'Campo CPF da Segunda Testemunha deve ser diferente do CPF da Primeira Testemunha.',
        'witness_two_name.different' => 'Campo Nome da Segunda Testemunha deve ser diferente do Nome da Primeira Testemunha.',
        'witness_two_name.required' => 'Campo Nome da Segunda Testemunha é obrigatório.',
        'witness_two_name.string' => 'Campo Nome da Segunda Testemunha está inválido.',
        'witness_two_name.max' => 'Campo Nome da Segunda Testemunha permite no máximo 255 caracteres.',
        'witness_two_name.min' => 'Campo Nome da Segunda Testemunha deve conter no mínimo 3 caracteres.',
        'proposal_id.required' => 'Campo Identificador de Proposta é obrigatório.',
        'proposal_id.integer' => 'Campo Identificador de Proposta está inválido.',
        'address_id.required' => 'Campo Identificador de Endereço é obrigatório.',
        'address_id.integer' => 'Campo Identificador de Endereço está inválido.'
    ];

    public function proposal(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Proposal::class, "id", "proposal_id")->where("deleted", false);
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id")->where("deleted", false);
    }

    public function getAll(string $orderBy) {
        return $this::where("deleted", false)
        ->with("proposal")
        ->with("address")
        ->orderBy($orderBy)
        ->get();
    }

    public function getByProposalId(int $id) {
        return $this::where("deleted", false)
        ->where("proposal_id", $id)
        ->get();
    }
}
