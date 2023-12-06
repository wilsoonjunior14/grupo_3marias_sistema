<?php

namespace App\Models;

class Client extends BaseModel
{
    protected $table = "clients";
    protected $fillable = ["id", "name", "rg", "cpf", "state", "nationality", "ocupation", "email", "phoneNumber", "birthdate",
    "name_dependent", "rg_dependent", "cpf_dependent", "nationality_dependent", "ocupation_dependent",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "rg", "cpf", "state", "nationality", "ocupation",
    "name_dependent", "rg_dependent", "cpf_dependent",
    "nationality_dependent", "ocupation_dependent", "deleted"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'rg' => 'required',
        'cpf' => 'required',
        'state' => 'required|in:Solteiro,Casado,Divorciado,Viúvo',
        'nationality' => 'bail|required|max:255|min:3',
        'ocupation' => 'bail|required|max:255|min:3',
        'email' => 'email:strict|max:100|min:3',
        'phoneNumber' => 'celular_com_ddd|max:20|min:10',
        'name_dependent' => 'bail|required|max:255|min:3',
        'rg_dependent' => 'required',
        'cpf_dependent' => 'required',
        'nationality_dependent' => 'bail|required|max:255|min:3',
        'ocupation_dependent' => 'bail|required|max:255|min:3',
        'email_dependent' => 'email:strict|max:100|min:3',
        'phoneNumber_dependent' => 'celular_com_ddd|max:20|min:10',
        'birthdate_dependent' => 'date'
    ];

    static $rulesMessages = [
        'address.required' => 'Campo endereço é obrigatório.',
        'address.max' => 'Campo endereço permite no máximo 100 caracteres.',
        'address.min' => 'Campo endereço deve conter no mínimo 3 caracteres.',
        'neighborhood.required' => 'Campo bairro é obrigatório.',
        'neighborhood.max' => 'Campo bairro permite no máximo 100 caracteres.',
        'neighborhood.min' => 'Campo bairro deve conter no mínimo 3 caracteres.',
    ];
}
