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
        'name' => 'bail|required|max:255|min:3|string',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'enterprise_id' => 'required|integer|gt:0|exists:enterprises,id',
        'state' => 'required|in:Solteiro,Casado,Divorciado,Viúvo',
        'ocupation' => 'bail|required|string|max:255|min:3',
        'email' => 'required|email:strict|max:100|min:3',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Sócio é obrigatório.',
        'name.string' => 'Campo Nome Completo do Sócio está inválido.',
        'name.max' => 'Campo Nome Completo do Sócio permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Sócio deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Sócio é obrigatório.',
        'phone.max' => 'Campo Telefone do Sócio permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Sócio deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Sócio está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
        'enterprise_id.integer' => 'Campo de Identificador de Empresa está inválido.',
        'enterprise_id.gt' => 'Campo de Identificador de Empresa está inválido.',
        'enterprise_id.exists' => 'Campo de Identificador de Empresa não existe.',
        'state.required' => 'Campo Estado Civil do Sócio é obrigatório.',
        'state.in' => 'Campo Estado Civil do Sócio é inválido.',
        'ocupation.required' => 'Campo Profissão do Sócio é obrigatório.',
        'ocupation.max' => 'Campo Profissão do Sócio permite no máximo 255 caracteres.',
        'ocupation.min' => 'Campo Profissão do Sócio deve conter no mínimo 3 caracteres.',
        'ocupation.string' => 'Campo Profissão do Sócio está inválido.',
        'email.required' => 'Campo Email do Sócio é obrigatório.',
        'email.email' => 'Campo Email do Sócio está inválido.',
        'email.max' => 'Campo Email do Sócio permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Sócio deve conter no mínimo 3 caracteres.'
    ];

    public function address() {
        return $this->hasOne(Address::class, "id", "address_id")->where("deleted", false);
    }

    public function getByEnterprise(int $enterpriseId) {
        return (new EnterprisePartner())->where("deleted", false)
        ->with("address")
        ->where("enterprise_id", $enterpriseId)
        ->orderBy("name")
        ->get();
    }

    public function withName($name) {
        $this->name = $name; // @phpstan-ignore-line
        return $this;
    }

    public function withPhone($phone) {
        $this->phone = $phone;// @phpstan-ignore-line
        return $this;
    }

    public function withEnterpriseId($enterprise_id) {
        $this->enterprise_id = $enterprise_id;// @phpstan-ignore-line
        return $this;
    }

    public function withState($state) {
        $this->state = $state;// @phpstan-ignore-line
        return $this;
    }

    public function withOcupation($ocupation) {
        $this->ocupation = $ocupation;// @phpstan-ignore-line
        return $this;
    }

    public function withEmail($email) {
        $this->email = $email;// @phpstan-ignore-line
        return $this;
    }

    public function withAddress($address) {
        $this->address = $address;// @phpstan-ignore-line
        return $this;
    }

    public function withNeighborhood($neighborhood) {
        $this->neighborhood = $neighborhood;// @phpstan-ignore-line
        return $this;
    }

    public function withCityId($city_id) {
        $this->city_id = $city_id;// @phpstan-ignore-line
        return $this;
    }

    public function withZipCode($zipcode) {
        $this->zipcode = $zipcode;// @phpstan-ignore-line
        return $this;
    }

    public function withNumber($number) {
        $this->number = $number;// @phpstan-ignore-line
        return $this;
    }

    public function withComplement($complement) {
        $this->complement = $complement;// @phpstan-ignore-line
        return $this;
    }

}
