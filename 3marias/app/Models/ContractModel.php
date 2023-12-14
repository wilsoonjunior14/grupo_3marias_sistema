<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractModel extends BaseModel
{
    protected $table = "contract_models";
    protected $fillable = ["id", "name", "type", "content", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "type", "content"];
    static $rules = [
        'name' => 'required|max:100|min:3|regex:/^[\pL\s]+$/u',
        'type' => 'required|max:100|min:3|regex:/^[\pL\s]+$/u',
        'content' => 'required|min:3'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome do Modelo de Contrato é obrigatório.',
        'name.max' => 'Campo Nome do Modelo de Contrato permite no máximo 100 caracteres.',
        'name.min' => 'Campo Nome do Modelo de Contrato deve conter no mínimo 3 caracteres.',
        'name.regex' => 'Campo Nome do Modelo de Contrato deve conter somente letras.',
        'type.required' => 'Campo Tipo do Modelo de Contrato é obrigatório.',
        'type.max' => 'Campo Tipo do Modelo de Contrato permite no máximo 100 caracteres.',
        'type.min' => 'Campo Tipo do Modelo de Contrato deve conter no mínimo 3 caracteres.',
        'type.regex' => 'Campo Tipo do Modelo de Contrato deve conter somente letras.',
        'content.required' => 'Campo Conteúdo do Contrato é obrigatório.',
        'content.min' => 'Campo Conteúdo do Contrato deve conter no mínimo 3 caracteres.',
    ];
}
