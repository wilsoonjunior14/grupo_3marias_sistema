<?php

namespace App\Models;

class Enterprise extends BaseModel
{
    protected $table = "enterprises";
    protected $fillable = ["id", "name", "fantasy_name", "social_reason", 
    "cnpj", "creci", "phone", "state_registration", "municipal_registration",
    "address_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "fantasy_name", "social_reason", 
    "cnpj", "creci", "phone", "state_registration", "municipal_registration",
    "address_id", "deleted"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'fantasy_name' => 'bail|required|max:255|min:3',
        'social_reason' => 'bail|required|max:255|min:3',
        'cnpj' => 'required|cnpj|unique:enterprises',
        'creci' => 'bail|required|max:255|min:3',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'state_registration' => 'bail|required|max:255|min:3',
        'municipal_registration' => 'bail|required|max:255|min:3'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo da Empresa é obrigatório.',
        'name.max' => 'Campo Nome Completo da Empresa permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo da Empresa deve conter no mínimo 3 caracteres.',
        'fantasy_name.required' => 'Campo Nome Fantasia da Empresa é obrigatório.',
        'fantasy_name.max' => 'Campo Nome Fantasia da Empresa permite no máximo 255 caracteres.',
        'fantasy_name.min' => 'Campo Nome Fantasia da Empresa deve conter no mínimo 3 caracteres.',
        'social_reason.required' => 'Campo Razão Social é obrigatório.',
        'social_reason.max' => 'Campo Razão Social permite no máximo 255 caracteres.',
        'social_reason.min' => 'Campo Razão Social deve conter no mínimo 3 caracteres.',
        'cnpj.required' => 'Campo CNPJ é obrigatório.',
        'cnpj.cnpj' => 'Campo CNPJ é inválido.',
        'cnpj.unique' => 'Campo CNPJ já existente na base de dados.',
        'creci.required' => 'Campo CRECI é obrigatório.',
        'creci.max' => 'Campo CRECI permite no máximo 255 caracteres.',
        'creci.min' => 'Campo CRECI deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone é obrigatório.',
        'phone.max' => 'Campo Telefone permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone está inválido.',
        'state_registration.required' => 'Campo Inscrição Estadual é obrigatório.',
        'state_registration.max' => 'Campo Inscrição Estadual permite no máximo 255 caracteres.',
        'state_registration.min' => 'Campo Inscrição Estadual deve conter no mínimo 3 caracteres.',
        'social_reason.required' => 'Campo Inscrição Municipal é obrigatório.',
        'social_reason.max' => 'Campo Inscrição Municipal permite no máximo 255 caracteres.',
        'social_reason.min' => 'Campo Inscrição Municipal deve conter no mínimo 3 caracteres.'
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }
}
