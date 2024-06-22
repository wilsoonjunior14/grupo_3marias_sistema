<?php

namespace App\Models;

class City extends BaseModel
{
    protected $table = "cities";
    protected $fillable = ["id", "name", "state_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "state_id"];
    static $rules = [
        'name' => 'required|max:100|min:3|regex:/^[\pL\s]+$/u',
        'state_id' => 'required'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo nome é obrigatório.',
        'name.max' => 'Campo nome permite no máximo 100 caracteres.',
        'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'name.regex' => 'Campo nome deve conter somente letras.',
        'state_id.required' => 'Campo identificador de país é obrigatório.'
    ];
}
