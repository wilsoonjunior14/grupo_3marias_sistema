<?php

namespace App\Models;

class Country extends BaseModel
{
    protected $table = "countries";
    protected $fillable = ["id", "name", "acronym", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "acronym"];

    static $rules = [
        'name' => 'required|max:100|min:3|regex:/^[\pL\s]+$/u',
        'acronym' => 'required|size:3|alpha'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo nome é obrigatório.',
        'name.max' => 'Campo nome permite no máximo 100 caracteres.',
        'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'name.regex' => 'Campo nome deve conter somente letras.',
        'acronym.required' => 'Campo sigla é obrigatório.',
        'acronym.size' => 'Campo sigla deve conter 3 caracteres.',
        'acronym.alpha' => 'Campo sigla deve conter somente letras.',
    ];
}
