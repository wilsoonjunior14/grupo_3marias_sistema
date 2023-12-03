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
        'content' => 'required|min:3|max:16383'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo nome é obrigatório.',
        'name.max' => 'Campo nome permite no máximo 100 caracteres.',
        'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'name.regex' => 'Campo nome deve conter somente letras.',
        'type.required' => 'Campo nome é obrigatório.',
        'type.max' => 'Campo nome permite no máximo 100 caracteres.',
        'type.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'type.regex' => 'Campo nome deve conter somente letras.',
        'content.required' => 'Campo conteúdo é obrigatório.',
        'content.max' => 'Campo conteúdo permite no máximo 16383 caracteres.',
        'content.min' => 'Campo conteúdo deve conter no mínimo 3 caracteres.',
    ];
}
