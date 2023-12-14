<?php

namespace App\Models;

class EnterpriseOwner extends BaseModel
{
    protected $table = "enterprise_owners";
    protected $fillable = ["id", "name",
    "email", "ocupation", "state",
    "phone", "address_id", "enterprise_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name",
    "email", "ocupation", "state",
    "phone", "address_id", "enterprise_id"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'enterprise_id' => 'required',
        'state' => 'required|in:Solteiro,Casado,Divorciado,Viúvo',
        'ocupation' => 'bail|required|max:255|min:3',
        'email' => 'required|email:strict|max:100|min:3',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Representante Legal é obrigatório.',
        'name.max' => 'Campo Nome Completo do Representante Legal permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Representante Legal deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Representante Legal é obrigatório.',
        'phone.max' => 'Campo Telefone do Representante Legal permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Representante Legal deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Representante Legal está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
        'state.required' => 'Campo Estado Civil do Representante Legal é obrigatório.',
        'state.in' => 'Campo Estado Civil do Representante Legal é inválido.',
        'ocupation.required' => 'Campo Profissão do Representante Legal é obrigatório.',
        'ocupation.max' => 'Campo Profissão do Representante Legal permite no máximo 255 caracteres.',
        'ocupation.min' => 'Campo Profissão do Representante Legal deve conter no mínimo 3 caracteres.',
        'email.required' => 'Campo Email do Representante Legal é obrigatório.',
        'email.email' => 'Campo Email do Representante Legal está inválido.',
        'email.max' => 'Campo Email do Representante Legal permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Representante Legal deve conter no mínimo 3 caracteres.'
    ];

    public function address() {
        return $this->hasOne(Address::class, "id", "address_id")->where("deleted", false);
    }

    public function getByEnterprise(int $enterpriseId) {
        return $this::where("deleted", false)
        ->with("address")
        ->where("enterprise_id", $enterpriseId)
        ->orderBy("name")
        ->get();
    }

}
