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
        'name' => 'bail|required|string|max:255|min:3',
        'cnpj' => 'required|cnpj',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'enterprise_id' => 'required|integer|gt:0|exists:enterprises,id',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo da Empresa é obrigatório.',
        'name.string' => 'Campo Nome Completo da Empresa está inválido.',
        'name.max' => 'Campo Nome Completo da Empresa permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo da Empresa deve conter no mínimo 3 caracteres.',
        'cnpj.required' => 'Campo CNPJ é obrigatório.',
        'cnpj.cnpj' => 'Campo CNPJ é inválido.',
        'phone.required' => 'Campo Telefone é obrigatório.',
        'phone.max' => 'Campo Telefone permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
        'enterprise_id.integer' => 'Campo de Identificador de Empresa está inválido.',
        'enterprise_id.gt' => 'Campo de Identificador de Empresa está inválido.',
        'enterprise_id.exists' => 'Campo de Identificador de Empresa está inválido.',
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }

    public function getByEnterprise(int $enterpriseId) {
        return (new EnterpriseBranch())->where("deleted", false)
        ->with("address")
        ->where("enterprise_id", $enterpriseId)
        ->orderBy("name")
        ->get();
    }

    public function withName($name) {
        $this->name = $name; // @phpstan-ignore-line
        return $this;
    }

    public function withCnpj($cnpj) {
        $this->cnpj = $cnpj; // @phpstan-ignore-line
        return $this;
    }

    public function withPhone($phone) {
        $this->phone = $phone; // @phpstan-ignore-line
        return $this;
    }

    public function withEnterpriseId($id) {
        $this->enterprise_id = $id; // @phpstan-ignore-line
        return $this;
    }

    public function withAddress($address) {
        $this->address = $address; // @phpstan-ignore-line
        return $this;
    }

    public function withNeighborhood($neighborhood) {
        $this->neighborhood = $neighborhood; // @phpstan-ignore-line
        return $this;
    }

    public function withCityId($city_id) {
        $this->city_id = $city_id; // @phpstan-ignore-line
        return $this;
    }

    public function withZipCode($zipcode) {
        $this->zipcode = $zipcode; // @phpstan-ignore-line
        return $this;
    }
}
