<?php

namespace App\Models;

class EnterpriseOwner extends BaseModel
{
    protected $table = "enterprise_owners";
    protected $fillable = ["id", "name",
    "email", "ocupation", "state", "cpf", "naturality", "nationality",
    "rg", "rg_date", "rg_organ",
    "phone", "address_id", "enterprise_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name",
    "email", "ocupation", "state", "cpf", "naturality", "nationality",
    "rg", "rg_date", "rg_organ", "phone", "address_id", "enterprise_id",];

    static $rules = [
        'name' => 'bail|required|string|max:255|min:3',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'enterprise_id' => 'required|integer|gt:0|exists:enterprises,id',
        'state' => 'required|in:Solteiro,Casado,Divorciado,Viúvo',
        'ocupation' => 'bail|required|string|max:255|min:3',
        'email' => 'required|email:strict|max:100|min:3',
        'nationality' => 'bail|max:255|min:3|regex:/^([a-zA-Z])+$/',
        'naturality' => 'bail|max:255|min:3|regex:/^([a-zA-Z])+$/',
        'rg' => 'min:13|max:14|regex:/^\d+$/',
        'rg_organ' => 'min:3|max:11|regex:/^([a-zA-Z]){3}\/([a-zA-Z]){2}$/',
        'rg_date' => 'date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'cpf' => 'required|cpf',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Representante Legal é obrigatório.',
        'name.max' => 'Campo Nome Completo do Representante Legal permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Representante Legal deve conter no mínimo 3 caracteres.',
        'name.string' => 'Campo Nome Completo do Representante Legal está inválido.',
        'phone.required' => 'Campo Telefone do Representante Legal é obrigatório.',
        'phone.max' => 'Campo Telefone do Representante Legal permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Representante Legal deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Representante Legal está inválido.',
        'enterprise_id.required' => 'Campo de Identificador de Empresa é obrigatório.',
        'enterprise_id.integer' => 'Campo de Identificador de Empresa está inválido.',
        'enterprise_id.gt' => 'Campo de Identificador de Empresa está inválido.',
        'enterprise_id.exists' => 'Campo de Identificador de Empresa não existe.',
        'state.required' => 'Campo Estado Civil do Representante Legal é obrigatório.',
        'state.in' => 'Campo Estado Civil do Representante Legal é inválido.',
        'ocupation.required' => 'Campo Profissão do Representante Legal é obrigatório.',
        'ocupation.max' => 'Campo Profissão do Representante Legal permite no máximo 255 caracteres.',
        'ocupation.min' => 'Campo Profissão do Representante Legal deve conter no mínimo 3 caracteres.',
        'ocupation.string' => 'Campo Profissão do Representante Legal está inválido.',
        'email.required' => 'Campo Email do Representante Legal é obrigatório.',
        'email.email' => 'Campo Email do Representante Legal está inválido.',
        'email.max' => 'Campo Email do Representante Legal permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Representante Legal deve conter no mínimo 3 caracteres.',
        'rg.max' => 'Campo RG do Representante Legal permite no máximo 14 caracteres.',
        'rg.min' => 'Campo RG do Representante Legal deve conter no mínimo 13 caracteres.',
        'rg.regex' => 'Campo RG do Representante Legal está inválido.',
        'rg_organ.max' => 'Campo Órgão RG do Representante Legal permite no máximo 11 caracteres.',
        'rg_organ.min' => 'Campo Órgão RG do Representante Legal deve conter no mínimo 3 caracteres.',
        'rg_organ.regex' => 'Campo Órgão RG do Representante Legal está inválido.',
        'rg_date.date' => 'Campo de Data de Emissão do RG do Representante Legal é inválido.',
        'rg_date.regex' => 'Campo de Data de Emissão do RG do Representante Legal está inválido.',
        'cpf.required' => 'Campo CPF do Representante Legal é obrigatório.',
        'cpf.cpf' => 'Campo CPF é inválido.',
        'cpf.unique' => 'Campo CPF já existente na base de dados.',
        'nationality.required' => 'Campo Nacionalidade do Representante Legal é obrigatório.',
        'nationality.max' => 'Campo Nacionalidade do Representante Legal permite no máximo 255 caracteres.',
        'nationality.min' => 'Campo Nacionalidade do Representante Legal deve conter no mínimo 3 caracteres.',
        'nationality.regex' => 'Campo Nacionalidade do Representante Legal está inválido.',
        'naturality.required' => 'Campo Naturalidade do Representante Legal é obrigatório.',
        'naturality.max' => 'Campo Naturalidade do Representante Legal permite no máximo 255 caracteres.',
        'naturality.min' => 'Campo Naturalidade do Representante Legal deve conter no mínimo 3 caracteres.',
        'naturality.regex' => 'Campo Naturalidade do Representante Legal está inválido.',
    ];

    public function address() {
        return $this->hasOne(Address::class, "id", "address_id")->where("deleted", false);
    }

    public function getByEnterprise(int $enterpriseId) {
        return (new EnterpriseOwner())->where("deleted", false)
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
        $this->phone = $phone; // @phpstan-ignore-line
        return $this;
    }

    public function withEnterpriseId($enterprise_id) {
        $this->enterprise_id = $enterprise_id; // @phpstan-ignore-line
        return $this;
    }

    public function withAddressId($address_id) {
        $this->address_id = $address_id; // @phpstan-ignore-line
        return $this;
    }

    public function withState($state) {
        $this->state = $state; // @phpstan-ignore-line
        return $this;
    }

    public function withOcupation($ocupation) {
        $this->ocupation = $ocupation; // @phpstan-ignore-line
        return $this;
    }

    public function withEmail($email) {
        $this->email = $email; // @phpstan-ignore-line
        return $this;
    }

    public function withCPF($cpf) {
        $this->cpf = $cpf; // @phpstan-ignore-line
        return $this;
    }

    public function withNationality($nationality) {
        $this->nationality = $nationality; // @phpstan-ignore-line
        return $this;
    }

    public function withNaturality($naturality) {
        $this->naturality = $naturality; // @phpstan-ignore-line
        return $this;
    }

    public function withRg($rg) {
        $this->rg = $rg; // @phpstan-ignore-line
        return $this;
    }

    public function withRgOrgan($rg_organ) {
        $this->rg_organ = $rg_organ; // @phpstan-ignore-line
        return $this;
    }

    public function withRGDate($rg_date) {
        $this->rg_date = $rg_date; // @phpstan-ignore-line
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

    public function withNumber($number) {
        $this->number = $number; // @phpstan-ignore-line
        return $this;
    }

    public function withComplement($complement) {
        $this->complement = $complement; // @phpstan-ignore-line
        return $this;
    }

}
