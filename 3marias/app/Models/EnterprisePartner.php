<?php

namespace App\Models;

class EnterprisePartner extends BaseModel
{
    protected $table = "enterprise_partners";
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
        'name.required' => 'Campo Nome Completo do Sócio é obrigatório.',
        'name.max' => 'Campo Nome Completo do Sócio permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Sócio deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Sócio é obrigatório.',
        'phone.max' => 'Campo Telefone do Sócio permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Sócio deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Sócio está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
        'state.required' => 'Campo Estado Civil do Sócio é obrigatório.',
        'state.in' => 'Campo Estado Civil do Sócio é inválido.',
        'ocupation.required' => 'Campo Profissão do Sócio é obrigatório.',
        'ocupation.max' => 'Campo Profissão do Sócio permite no máximo 255 caracteres.',
        'ocupation.min' => 'Campo Profissão do Sócio deve conter no mínimo 3 caracteres.',
        'email.required' => 'Campo Email do Sócio é obrigatório.',
        'email.email' => 'Campo Email do Sócio está inválido.',
        'email.max' => 'Campo Email do Sócio permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Sócio deve conter no mínimo 3 caracteres.'
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
