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
        'fantasy_name' => 'bail|required|max:255|min:3',
        'partner_type' => 'required|in:Física,Jurídica',
        'email' => 'email:strict|max:100|min:3',
        'phone' => 'celular_com_ddd|max:20|min:10',
        'social_reason' => 'bail|max:255|min:3',
        'cnpj' => 'cnpj',
        'website' => 'bail|max:255|url',
        'observation' => 'bail|max:500|min:3',
    ];

    static $rulesMessages = [
        'fantasy_name.required' => 'Campo Nome Fantasia do Parceiro/Fornecedor é obrigatório.',
        'fantasy_name.max' => 'Campo Nome Fantasia do Parceiro/Fornecedor permite no máximo 255 caracteres.',
        'fantasy_name.min' => 'Campo Nome Fantasia do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'partner_type.required' => 'Campo Tipo de Parceiro/Fornecedor é obrigatório.',
        'partner_type.in' => 'Campo Tipo de Parceiro/Fornecedor é inválido.',
        'email.required' => 'Campo Email do Parceiro/Fornecedor é obrigatório.',
        'email.email' => 'Campo Email do Parceiro/Fornecedor está inválido.',
        'email.max' => 'Campo Email do Parceiro/Fornecedor permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Parceiro/Fornecedor é obrigatório.',
        'phone.max' => 'Campo Telefone do Parceiro/Fornecedor permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Parceiro/Fornecedor deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Parceiro/Fornecedor está inválido.',
        'url.max' => 'Campo Website permite no máximo 255 caracteres.',
        'url.url' => 'Campo Website é inválido.',
        'social_reason.max' => 'Campo Nome Completo do Parceiro/Fornecedor permite no máximo 255 caracteres.',
        'social_reason.min' => 'Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'observation.max' => 'Campo Nome Completo do Parceiro/Fornecedor permite no máximo 500 caracteres.',
        'observation.min' => 'Campo Nome Completo do Parceiro/Fornecedor deve conter no mínimo 3 caracteres.',
        'cnpj.cnpj' => 'Campo CNPJ do Parceiro/Fornecedor é inválido.'
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }
}
