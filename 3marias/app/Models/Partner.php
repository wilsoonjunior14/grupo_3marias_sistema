<?php

namespace App\Models;

class Partner extends BaseModel 
{
    protected $table = "partners";
    protected $fillable = ["id", "fantasy_name", "partner_type", "cnpj",
    "social_reason", "phone", "email", "website", "observation",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["fantasy_name", "partner_type", "cnpj",
    "social_reason", "phone", "email", "email", "website", "observation"];

    static $rules = [
        'fantasy_name' => 'bail|string|required|max:255|min:3',
        'partner_type' => 'required|string|in:Física,Jurídica',
        'email' => 'email:strict|string|max:100|min:3',
        'phone' => 'celular_com_ddd|max:20|min:10|string',
        'social_reason' => 'bail|max:255|min:3|string',
        'cnpj' => 'cnpj|required',
        'website' => 'bail|max:255|url|string',
        'observation' => 'bail|max:500|min:3|string',
    ];

    static $rulesMessages = [
        'fantasy_name.required' => 'Campo Nome Fantasia do Parceiro/Fornecedor é obrigatório.',
        'fantasy_name.string' => 'Campo Nome Fantasia do Parceiro/Fornecedor está inválido.',
        'fantasy_name.max' => 'Campo Nome Fantasia do Parceiro/Fornecedor permite no máximo 255 caracteres.',
        'fantasy_name.min' => 'Campo Nome Fantasia do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'partner_type.required' => 'Campo Tipo de Parceiro/Fornecedor é obrigatório.',
        'partner_type.string' => 'Campo Tipo de Parceiro/Fornecedor está inválido.',
        'partner_type.in' => 'Campo Tipo de Parceiro/Fornecedor é inválido.',
        'cnpj.required' => 'Campo CNPJ do Parceiro/Fornecedor é obrigatório.',
        'cnpj.cnpj' => 'Campo CNPJ do Parceiro/Fornecedor é inválido.',
        'email.required' => 'Campo Email do Parceiro/Fornecedor é obrigatório.',
        'email.string' => 'Campo Email do Parceiro/Fornecedor está inválido.',
        'email.email' => 'Campo Email do Parceiro/Fornecedor está inválido.',
        'email.max' => 'Campo Email do Parceiro/Fornecedor permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Parceiro/Fornecedor é obrigatório.',
        'phone.max' => 'Campo Telefone do Parceiro/Fornecedor permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Parceiro/Fornecedor deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Parceiro/Fornecedor está inválido.',
        'phone.string' => 'Campo Telefone do Parceiro/Fornecedor está inválido.',
        'website.max' => 'Campo Website permite no máximo 255 caracteres.',
        'website.url' => 'Campo Website é inválido.',
        'website.string' => 'Campo Website está inválido.',
        'social_reason.max' => 'Campo Razão Social do Parceiro/Fornecedor permite no máximo 255 caracteres.',
        'social_reason.min' => 'Campo Razão Social do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'social_reason.string' => 'Campo Razão Social do Parceiro/Fornecedor está inválido.',
        'observation.max' => 'Campo Nome Completo do Parceiro/Fornecedor permite no máximo 500 caracteres.',
        'observation.min' => 'Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'observation.string' => 'Campo Nome Completo do Parceiro/Fornecedor está inválido.'
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }
}
