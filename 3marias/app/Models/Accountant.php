<?php

namespace App\Models;

class Accountant extends BaseModel
{
    protected $table = "accountants";
    protected $fillable = ["id", "name",
    "phone", "enterprise_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name",
    "phone", "enterprise_id"];

    static $rules = [
        'name' => 'bail|required|string|max:255|min:3',
        'phone' => 'required|celular_com_ddd|string|max:20|min:10',
        'enterprise_id' => 'required|integer',
        'address_id' => 'integer'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Contador é obrigatório.',
        'name.max' => 'Campo Nome Completo do Contador permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Contador deve conter no mínimo 3 caracteres.',
        'name.string' => 'Campo Nome Completo do Contador está inválido.',
        'phone.required' => 'Campo Telefone do Contador é obrigatório.',
        'phone.max' => 'Campo Telefone do Contador permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Contador deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Contador está inválido.',
        'phone.string' => 'Campo Telefone do Contador está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
        'enterprise_id.integer' => 'Campo Identificador de Empresa está inválido.',
        'address_id.integer' => 'Campo Identificador de Endereço está inválido.',
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
