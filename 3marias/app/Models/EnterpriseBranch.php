<?php

namespace App\Models;

class EnterpriseBranch extends BaseModel
{
    protected $table = "enterprise_branches";
    protected $fillable = ["id", "name",
    "cnpj", "phone", "enterprise_id",
    "address_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "cnpj", "phone", 
    "enterprise_id", "address_id", "deleted"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'cnpj' => 'required|cnpj|unique:enterprise_branches',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'enterprise_id' => 'required',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo da Empresa é obrigatório.',
        'name.max' => 'Campo Nome Completo da Empresa permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo da Empresa deve conter no mínimo 3 caracteres.',
        'cnpj.required' => 'Campo CNPJ é obrigatório.',
        'cnpj.cnpj' => 'Campo CNPJ é inválido.',
        'cnpj.unique' => 'Campo CNPJ já existente na base de dados.',
        'phone.required' => 'Campo Telefone é obrigatório.',
        'phone.max' => 'Campo Telefone permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }

    public function getByEnterprise(int $enterpriseId) {
        return $this::where("deleted", false)
        ->with("address")
        ->where("enterprise_id", $enterpriseId)
        ->orderBy("name")
        ->get();
    }
}
