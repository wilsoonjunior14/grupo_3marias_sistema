<?php

namespace App\Models;

class Address extends BaseModel
{
    protected $table = "addresses";
    protected $fillable = ["id", "address", "number", "complement", "city_id", "zipcode",
    "neighborhood", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["address", "number", "complement", 
    "city_id", "zipcode", "neighborhood"];

    static $rules = [
        'address' => 'required|max:100|min:3|string',
        'neighborhood' => 'required|max:100|min:2|string',
        'complement' => 'max:255',
        'number' => 'integer',
        'city_id' => 'required|integer|regex:/^\d+$/',
        'zipcode' => 'required|regex:/^\d{5}-\d{3}$/|string'
    ];

    static $rulesMessages = [
        'address.required' => 'Campo endereço é obrigatório.',
        'address.string' => 'Campo endereço está inválido.',
        'address.max' => 'Campo endereço permite no máximo 100 caracteres.',
        'address.min' => 'Campo endereço deve conter no mínimo 2 caracteres.',
        'neighborhood.required' => 'Campo bairro é obrigatório.',
        'neighborhood.string' => 'Campo bairro está inválido.',
        'neighborhood.max' => 'Campo bairro permite no máximo 100 caracteres.',
        'neighborhood.min' => 'Campo bairro deve conter no mínimo 2 caracteres.',
        'number.integer' => 'Campo de número deve ser um número inteiro.',
        'complement.max' => 'Campo complemento permite no máximo 255 caracteres.',
        'city_id.required' => 'Campo identificador de cidade é obrigatório.',
        'city_id.integer' => 'Campo identificador de cidade deve ser um número inteiro.',
        'city_id.regex' => 'Campo identificador de cidade está inválido. Este campo deve ser um número inteiro.',
        'zipcode.required' => 'Campo de cep é obrigatório.',
        'zipcode.regex' => 'Campo de cep é inválido.',
        'zipcode.string' => 'Campo de cep está inválido.'
    ];

    public function city() {
        return $this->hasOne(City::class, "id", "city_id")->where("deleted", false);
    }
}
