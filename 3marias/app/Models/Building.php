<?php

namespace App\Models;

class Building extends BaseModel
{
    protected $table = "buildings";
    protected $fillable = ["id",
    "description", "start_date", "end_date", 
    "art_number", "art_document", "engineer_name",
    "engineer_cpf", "engineer_crea", "contract_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["description", "start_date", "end_date", 
    "art_number", "art_document", "engineer_name",
    "engineer_cpf", "engineer_crea", "contract_id",];

    static $rules = [
        'code' => 'bail|string|required|max:100|min:3',
        'description' => 'bail|string|required|max:255|min:3',
        'start_date' => 'required|date|string|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'end_date' => 'required|date|string|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'art_number' => 'string|max:100|min:3',
        'art_document' => 'string|max:100|min:3',
        'engineer_name' => 'string|max:100|min:3',
        'engineer_cpf' => 'cpf|string',
        'engineer_crea' => 'string|max:100|min:3',
        'contract_id' => 'required|integer|gt:0|exists:contracts,id',
    ];

    static $rulesMessages = [
        'code.required' => 'Campo Código da Obra é obrigatório.',
        'code.max' => 'Campo Código da Obra permite no máximo 255 caracteres.',
        'code.min' => 'Campo Código da Obra deve conter no mínimo 3 caracteres.',
        'description.required' => 'Campo Descrição da Obra é obrigatório.',
        'description.max' => 'Campo Descrição da Obra permite no máximo 1000 caracteres.',
        'description.min' => 'Campo Descrição da Obra deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição da Obra está inválido.',
        'start_date.required' => 'Campo Data de início da Obra é obrigatório.',
        'start_date.date' => 'Campo de Data de início da Obra está inválido.',
        'start_date.regex' => 'Campo de Data de início da Obra está inválido.',
        'end_date.required' => 'Campo Data de Término da Obra é obrigatório.',
        'end_date.date' => 'Campo de Data de Término da Obra está inválido.',
        'end_date.regex' => 'Campo de Data de Término da Obra está inválido.',
        'art_number.max' => 'Campo Número da ART permite no máximo 100 caracteres.',
        'art_number.min' => 'Campo Número da ART deve conter no mínimo 3 caracteres.',
        'art_number.string' => 'Campo Número da ART está inválido.',
        'art_document.max' => 'Campo Número do Alvará permite no máximo 100 caracteres.',
        'art_document.min' => 'Campo Número do Alvará deve conter no mínimo 3 caracteres.',
        'art_document.string' => 'Campo Número do Alvará está inválido.',
        'engineer_name.max' => 'Campo Nome do Engenheiro da Obra permite no máximo 100 caracteres.',
        'engineer_name.min' => 'Campo Nome do Engenheiro da Obra deve conter no mínimo 3 caracteres.',
        'engineer_name.string' => 'Campo Nome do Engenheiro da Obra está inválido.',
        'engineer_crea.max' => 'Campo CREA do Engenheiro da Obra permite no máximo 100 caracteres.',
        'engineer_crea.min' => 'Campo CREA do Engenheiro da Obra deve conter no mínimo 3 caracteres.',
        'engineer_crea.string' => 'Campo CREA do Engenheiro da Obra está inválido.',
        'engineer_cpf.cpf' => 'Campo CPF do Engenheiro está inválido.',
        'engineer_cpf.string' => 'Campo CPF do Engenheiro está inválido.',
        'contract_id.required' => 'Campo Identificador de Proposta é obrigatório.',
        'contract_id.integer' => 'Campo Identificador de Proposta está inválido.',
    ];
}
