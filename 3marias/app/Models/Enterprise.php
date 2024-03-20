<?php

namespace App\Models;

class Enterprise extends BaseModel
{
    protected $table = "enterprises";
    protected $fillable = ["id", "name", "fantasy_name", "social_reason",
    "email", "bank", "bank_agency", "bank_account", "pix", 
    "cnpj", "creci", "phone", "state_registration", "municipal_registration",
    "address_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "fantasy_name", "social_reason",
    "email", "bank", "bank_agency", "bank_account", "pix", 
    "cnpj", "creci", "phone", "state_registration", "municipal_registration",
    "address_id", "deleted"];

    static $rules = [
        'name' => 'bail|required|string|max:255|min:3',
        'email' => 'required|email:strict|string',
        'fantasy_name' => 'bail|required|string|max:255|min:3',
        'social_reason' => 'bail|required|string|max:255|min:3',
        'cnpj' => 'required|cnpj|string|unique:enterprises',
        'creci' => 'bail|required|string|max:255|min:3',
        'phone' => 'required|string|celular_com_ddd|max:20|min:10',
        'state_registration' => 'bail|required|string|max:255|min:3',
        'municipal_registration' => 'bail|required|string|max:255|min:3',
        'bank' => 'required|string|max:100|min:3',
        'bank_agency' => 'required|string|max:10|min:3',
        'bank_account' => 'required|string|max:10|min:3',
        'pix' => 'required|string|max:100|min:3'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo da Empresa é obrigatório.',
        'name.string' => 'Campo Nome Completo da Empresa está inválido.',
        'name.max' => 'Campo Nome Completo da Empresa permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo da Empresa deve conter no mínimo 3 caracteres.',
        'email.required' => 'Campo Email da Empresa é obrigatório.',
        'email.string' => 'Campo Email da Empresa está inválido.',
        'email.email' => 'Campo Email da Empresa está inválido.',
        'fantasy_name.required' => 'Campo Nome Fantasia da Empresa é obrigatório.',
        'fantasy_name.max' => 'Campo Nome Fantasia da Empresa permite no máximo 255 caracteres.',
        'fantasy_name.min' => 'Campo Nome Fantasia da Empresa deve conter no mínimo 3 caracteres.',
        'fantasy_name.string' => 'Campo Nome Fantasia da Empresa está inválido.',
        'social_reason.required' => 'Campo Razão Social é obrigatório.',
        'social_reason.max' => 'Campo Razão Social permite no máximo 255 caracteres.',
        'social_reason.min' => 'Campo Razão Social deve conter no mínimo 3 caracteres.',
        'social_reason.string' => 'Campo Razão Social está inválido.',
        'cnpj.required' => 'Campo CNPJ é obrigatório.',
        'cnpj.cnpj' => 'Campo CNPJ é inválido.',
        'cnpj.string' => 'Campo CNPJ é inválido.',
        'cnpj.unique' => 'Campo CNPJ já existente na base de dados.',
        'creci.required' => 'Campo CRECI é obrigatório.',
        'creci.max' => 'Campo CRECI permite no máximo 255 caracteres.',
        'creci.min' => 'Campo CRECI deve conter no mínimo 3 caracteres.',
        'creci.string' => 'Campo CRECI está inválido.',
        'phone.required' => 'Campo Telefone é obrigatório.',
        'phone.max' => 'Campo Telefone permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone está inválido.',
        'phone.string' => 'Campo Telefone está inválido.',
        'state_registration.required' => 'Campo Inscrição Estadual é obrigatório.',
        'state_registration.max' => 'Campo Inscrição Estadual permite no máximo 255 caracteres.',
        'state_registration.min' => 'Campo Inscrição Estadual deve conter no mínimo 3 caracteres.',
        'state_registration.string' => 'Campo Inscrição Estadual está inválido.',
        'municipal_registration.required' => 'Campo Inscrição Municipal é obrigatório.',
        'municipal_registration.max' => 'Campo Inscrição Municipal permite no máximo 255 caracteres.',
        'municipal_registration.min' => 'Campo Inscrição Municipal deve conter no mínimo 3 caracteres.',
        'municipal_registration.string' => 'Campo Inscrição Municipal está inválido.',
        'bank.required' => 'Campo Banco é obrigatório.',
        'bank.string' => 'Campo Banco está inválido.',
        'bank.max' => 'Campo Banco permite no máximo 100 caracteres.',
        'bank.min' => 'Campo Banco deve conter no mínimo 3 caracteres.',
        'bank_agency.required' => 'Campo Agência é obrigatório.',
        'bank_agency.string' => 'Campo Agência está inválido.',
        'bank_agency.max' => 'Campo Agência permite no máximo 10 caracteres.',
        'bank_agency.min' => 'Campo Agência deve conter no mínimo 3 caracteres.',
        'bank_account.required' => 'Campo Conta do Banco é obrigatório.',
        'bank_account.string' => 'Campo Conta do Banco está inválido.',
        'bank_account.max' => 'Campo Conta do Banco permite no máximo 10 caracteres.',
        'bank_account.min' => 'Campo Conta do Banco deve conter no mínimo 3 caracteres.',
        'pix.required' => 'Campo Pix é obrigatório.',
        'pix.string' => 'Campo Pix está inválido.',
        'pix.max' => 'Campo Pix permite no máximo 100 caracteres.',
        'pix.min' => 'Campo Pix deve conter no mínimo 3 caracteres.',
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }
}
