<?php

namespace App\Models;

use Carbon\Carbon;

class Client extends BaseModel
{
    protected $table = "clients";
    protected $fillable = ["id", "name", "rg", "rg_organ", "rg_date", "cpf", "state", "nationality", "ocupation", "email", 
    "phone", "birthdate", "address_id", "sex", "sex_dependent",
    "naturality", "person_service", "indication", "is_public_employee", "salary", "has_fgts", "has_many_buyers",
    "name_dependent", "rg_dependent", "rg_dependent_organ", "rg_dependent_date",
    "cpf_dependent", "nationality_dependent", "ocupation_dependent", "email_dependent", 
    "phone_dependent", "birthdate_dependent",
    "naturality_dependent", "salary_dependent", "is_public_employee_dependent",
    "has_fgts_dependent", "has_many_buyers_dependent",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "rg", "rg_organ", "rg_date", "cpf", "state", "nationality", "ocupation", "email", 
    "phone", "birthdate", "address_id", "sex", "sex_dependent",
    "naturality", "person_service", "indication", "is_public_employee", "salary", "has_fgts", "has_many_buyers",
    "name_dependent", "rg_dependent", "rg_dependent_organ", "rg_dependent_date",
    "cpf_dependent", "nationality_dependent", "ocupation_dependent", "email_dependent", 
    "phone_dependent", "birthdate_dependent",
    "naturality_dependent", "salary_dependent", "is_public_employee_dependent",
    "has_fgts_dependent", "has_many_buyers_dependent",];

    static $dependentFields = ["name_dependent", "rg_dependent", "rg_dependent_organ", "rg_dependent_date", 
    "cpf_dependent", "nationality_dependent", "ocupation_dependent", "email_dependent", 
    "phone_dependent", "birthdate_dependent", "sex_dependent",
    "naturality_dependent", "salary_dependent", "is_public_employee_dependent",
    "has_fgts_dependent", "has_many_buyers_dependent"];

    static $rules = [
        'name' => 'string|bail|required|max:255|min:3',
        'rg' => 'min:13|max:14|regex:/^\d+$/',
        'rg_organ' => 'min:3|max:11|regex:/^([a-zA-Z]){3}\/([a-zA-Z]){2}$/',
        'rg_date' => 'date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'cpf' => 'required|cpf|unique:clients',
        'state' => 'in:Solteiro,Casado,Divorciado,Viúvo',
        'sex' => 'in:Masculino,Feminino,Outro',
        'nationality' => 'bail|max:255|min:3|regex:/^([a-zA-Z])+$/',
        'naturality' => 'bail|max:255|min:3|string',
        'ocupation' => 'string|bail|max:255|min:3',
        'email' => 'email:strict|max:100|min:3|string',
        'phone' => 'celular_com_ddd|max:20|min:10|string',
        'birthdate' => 'date|string|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'person_service' => 'bail|max:255|min:3|regex:/^([a-zA-Z])+$/|string',
        'indication' => 'bail|max:255|min:3',
        'salary' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'is_public_employee' => 'string|max:3',
        'has_fgts' => 'string|max:3',
        'has_many_buyers' => 'string|max:3',
        'name_dependent' => 'bail|max:255|min:3|string',
        'rg_dependent' => 'min:13|max:14|different:rg|string',
        'rg_dependent_organ' => 'min:3|max:11|regex:/^([a-zA-Z]){3}\/([a-zA-Z]){2}$/',
        'rg_dependent_date' => 'date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'cpf_dependent' => 'cpf|unique:clients|different:cpf|string',
        'nationality_dependent' => 'bail|max:255|min:3|regex:/^([a-zA-Z])+$/',
        'ocupation_dependent' => 'bail|max:255|min:3|string',
        'sex_dependent' => 'in:Masculino,Feminino,Outro',
        'email_dependent' => 'email:strict|max:100|min:3|different:email',
        'phone_dependent' => 'celular_com_ddd|max:20|min:10|different:phone',
        'birthdate_dependent' => 'date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'naturality_dependent' => 'bail|max:255|min:3',
        'salary_dependent' => 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'is_public_employee_dependent' => 'string|max:3',
        'has_fgts_dependent' => 'string|max:3',
        'has_many_buyers_dependent' => 'string|max:3',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Cliente é obrigatório.',
        'name.max' => 'Campo Nome Completo do Cliente permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Cliente deve conter no mínimo 3 caracteres.',
        'name.string' => 'Campo Nome Completo do Cliente está inválido.',
        'rg.required' => 'Campo RG do Cliente é obrigatório.',
        'rg.max' => 'Campo RG do Cliente permite no máximo 14 caracteres.',
        'rg.min' => 'Campo RG do Cliente deve conter no mínimo 13 caracteres.',
        'rg.regex' => 'Campo RG do Cliente está inválido.',
        'rg_organ.required' => 'Campo Órgão do RG do Cliente é obrigatório.',
        'rg_organ.max' => 'Campo Órgão RG do Cliente permite no máximo 11 caracteres.',
        'rg_organ.min' => 'Campo Órgão RG do Cliente deve conter no mínimo 3 caracteres.',
        'rg_organ.regex' => 'Campo Órgão RG do Cliente está inválido.',
        'rg_date.required' => 'Campo Data de Emissão do RG do Cliente é obrigatório.',
        'rg_date.date' => 'Campo de Data de Emissão do RG do Cliente é inválido.',
        'rg_date.regex' => 'Campo de Data de Emissão do RG do Cliente está inválido.',
        'cpf.required' => 'Campo CPF do Cliente é obrigatório.',
        'cpf.cpf' => 'Campo CPF é inválido.',
        'cpf.unique' => 'Campo CPF já existente na base de dados.',
        'state.required' => 'Campo Estado Civil do Cliente é obrigatório.',
        'state.in' => 'Campo Estado Civil do Cliente é inválido.',
        'sex.required' => 'Campo Sexo do Cliente é obrigatório.',
        'sex.in' => 'Campo Sexo do Cliente é inválido.',
        'nationality.required' => 'Campo Nacionalidade do Cliente é obrigatório.',
        'nationality.max' => 'Campo Nacionalidade do Cliente permite no máximo 255 caracteres.',
        'nationality.min' => 'Campo Nacionalidade do Cliente deve conter no mínimo 3 caracteres.',
        'nationality.regex' => 'Campo Nacionalidade do Cliente está inválido.',
        'naturality.required' => 'Campo Naturalidade do Cliente é obrigatório.',
        'naturality.max' => 'Campo Naturalidade do Cliente permite no máximo 255 caracteres.',
        'naturality.min' => 'Campo Naturalidade do Cliente deve conter no mínimo 3 caracteres.',
        'naturality.regex' => 'Campo Naturalidade do Cliente está inválido.',
        'naturality.string' => 'Campo Naturalidade do Cliente está inválido.',
        'person_service.max' => 'Campo Atendimento permite no máximo 255 caracteres.',
        'person_service.min' => 'Campo Atendimento deve conter no mínimo 3 caracteres.',
        'person_service.regex' => 'Campo Atendimento está inválido.',
        'person_service.string' => 'Campo Atendimento está com formato inválido.',
        'salary.regex' => 'Campo Renda Bruta do Cliente está inválido.',
        'indication.max' => 'Campo Indicação permite no máximo 255 caracteres.',
        'indication.min' => 'Campo Indicação deve conter no mínimo 3 caracteres.',
        'is_public_employee.string' => 'Campo Informando se Cliente é Funcionário Público está inválido.',
        'is_public_employee.max' => 'Campo Informando se Cliente é Funcionário Público permite no máximo 3 caracteres.',
        'has_fgts.string' => 'Campo Informando se Cliente tem Saldo de FGTS está inválido.',
        'has_fgts.max' => 'Campo Informando se Cliente tem Saldo de FGTS permite no máximo 3 caracteres.',
        'has_many_buyers.string' => 'Campo Informando se Cliente tem mais outros compradores ou dependentes está inválido.',
        'has_many_buyers.max' => 'Campo Informando se Cliente tem mais outros compradores ou dependentes permite no máximo 3 caracteres.',
        'ocupation.required' => 'Campo Profissão do Cliente é obrigatório.',
        'ocupation.string' => 'Campo Profissão do Cliente está inválido.',
        'ocupation.max' => 'Campo Profissão do Cliente permite no máximo 255 caracteres.',
        'ocupation.min' => 'Campo Profissão do Cliente deve conter no mínimo 3 caracteres.',
        'email.required' => 'Campo Email do Cliente é obrigatório.',
        'email.email' => 'Campo Email do Cliente está inválido.',
        'email.max' => 'Campo Email do Cliente permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Cliente deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Cliente é obrigatório.',
        'phone.string' => 'Campo Telefone do Cliente está com tipo inválido.',
        'phone.max' => 'Campo Telefone do Cliente permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Cliente deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Cliente está inválido.',
        'birthdate.date' => 'Campo de Data de Nascimento do Cliente é inválido.',
        'birthdate.string' => 'Campo de Data de Nascimento do Cliente está inválido.',
        'birthdate.regex' => 'Campo de Data de Nascimento do Cliente está inválido.',
        'name_dependent.required' => 'Campo Nome Completo do Cônjugue é obrigatório.',
        'name_dependent.max' => 'Campo Nome Completo do Cônjugue permite no máximo 255 caracteres.',
        'name_dependent.min' => 'Campo Nome Completo do Cônjugue deve conter no mínimo 3 caracteres.',
        'name_dependent.string' => 'Campo Nome Completo do Cônjugue está inválido.',
        'rg_dependent.required' => 'Campo RG do Cônjugue é obrigatório.',
        'rg_dependent.max' => 'Campo RG do Cônjugue permite no máximo 14 caracteres.',
        'rg_dependent.min' => 'Campo RG do Cônjugue deve conter no mínimo 13 caracteres.',
        'rg_dependent.different' => 'Campo RG do Cônjugue deve ser diferente do RG do Cliente.',
        'rg_dependent.string' => 'Campo RG do Cônjugue está inválido.',
        'rg_dependent_organ.required' => 'Campo Órgão do RG do Cônjugue é obrigatório.',
        'rg_dependent_organ.max' => 'Campo Órgão RG do Cônjugue permite no máximo 11 caracteres.',
        'rg_dependent_organ.min' => 'Campo Órgão RG do Cônjugue deve conter no mínimo 3 caracteres.',
        'rg_dependent_organ.regex' => 'Campo Órgão RG do Cônjugue está inválido.',
        'rg_dependent_date.required' => 'Campo Órgão do RG do Cônjugue é obrigatório.',
        'rg_dependent_date.date' => 'Campo de Data de Emissão do RG do Cônjugue é inválido.',
        'rg_dependent_date.regex' => 'Campo de Data de Emissão do RG do Cônjugue está inválido.',
        'cpf_dependent.required' => 'Campo CPF do Cônjugue é obrigatório.',
        'cpf_dependent.cpf' => 'Campo CPF do Cônjugue é inválido.',
        'cpf_dependent.unique' => 'Campo CPF do Cônjugue já existente na base de dados.',
        'cpf_dependent.different' => 'Campo CPF do Cônjugue deve ser diferente do CPF do Cliente.',
        'cpf_dependent.string' => 'Campo CPF do Cônjugue está inválido.',
        'salary_dependent.regex' => 'Campo Renda Bruta do Cônjugue está inválido.',
        'nationality_dependent.required' => 'Campo Nacionalidade do Cônjugue é obrigatório.',
        'nationality_dependent.max' => 'Campo Nacionalidade do Cônjugue permite no máximo 255 caracteres.',
        'nationality_dependent.min' => 'Campo Nacionalidade do Cônjugue deve conter no mínimo 3 caracteres.',
        'ocupation_dependent.required' => 'Campo Profissão do Cônjugue é obrigatório.',
        'ocupation_dependent.max' => 'Campo Profissão do Cônjugue permite no máximo 255 caracteres.',
        'ocupation_dependent.min' => 'Campo Profissão do Cônjugue deve conter no mínimo 3 caracteres.',
        'ocupation_dependent.string' => 'Campo Profissão do Cônjugue está inválido.',
        'email_dependent.email' => 'Campo Email do Cônjugue está inválido.',
        'email_dependent.max' => 'Campo Email do Cônjugue permite no máximo 100 caracteres.',
        'email_dependent.min' => 'Campo Email do Cônjugue deve conter no mínimo 3 caracteres.',
        'email_dependent.different' => 'Campo Email do Cônjugue deve ser diferente do Email do Cliente.',
        'phone_dependent.max' => 'Campo Telefone do Cônjugue permite no máximo 20 caracteres.',
        'phone_dependent.min' => 'Campo Telefone do Cônjugue deve conter no mínimo 10 caracteres.',
        'phone_dependent.celular_com_ddd' => 'Campo Telefone do Cônjugue está inválido.',
        'phone_dependent.different' => 'Campo Telefone do Cônjugue deve ser diferente do Telefone do Cliente.',
        'birthdate_dependent.date' => 'Campo de Data de Nascimento do Cônjugue é inválido.',
        'birthdate_dependent.regex' => 'Campo de Data de Emissão do RG do Cônjugue está inválido.',
        'birthdate_dependent.string' => 'Campo de Data de Emissão do RG do Cônjugue está inválido.',
        'naturality_dependent.max' => 'Campo Naturalidade do Cônjugue permite no máximo 255 caracteres.',
        'naturality_dependent.min' => 'Campo Naturalidade do Cônjugue deve conter no mínimo 3 caracteres.',
        'naturality_dependent.regex' => 'Campo Naturalidade do Cônjugue está inválido.',
        'sex_dependent.in' => 'Campo Sexo do Cônjugue é inválido.',
        'is_public_employee_dependent.string' => 'Campo Informando se Cônjugue é Funcionário Público está inválido.',
        'is_public_employee_dependent.max' => 'Campo Informando se Cônjugue é Funcionário Público permite no máximo 3 caracteres.',
        'has_fgts_dependent.string' => 'Campo Informando se Cônjugue tem Saldo de FGTS está inválido.',
        'has_fgts_dependent.max' => 'Campo Informando se Cônjugue tem Saldo de FGTS permite no máximo 3 caracteres.',
        'has_many_buyers_dependent.string' => 'Campo Informando se Cônjugue tem mais outros compradores ou dependentes está inválido.',
        'has_many_buyers_dependent.max' => 'Campo Informando se Cônjugue tem mais outros compradores ou dependentes permite no máximo 3 caracteres.',
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(File::class, "id", "client_id")->where("deleted", false)->orderBy("description");
    }

    public function getClients(string $name, array $fields) {
        return (new Client())
        ->select($fields)
        ->where("deleted", false)
        ->with("files")
        ->orderBy("name")
        ->get();
    }

    public function getByNameAndCPF(string $name, string $cpf) {
        return (new Client())->where("deleted", false)
        ->where("name", "like", "%" . $name . "%")
        ->where("cpf", "like", "%" . $cpf . "%")
        ->get();
    }

    public function getClientsBirthdate() {
        $month = Carbon::now()->format('m');

        return (new Client())
        ->select("name", "phone", "birthdate")
        ->where("deleted", false)
        ->whereNotNull("birthdate")
        ->whereMonth("birthdate", $month)
        ->orderBy("name")
        ->get();
    }

    public function withName($name) {
        $this->name = $name; // @phpstan-ignore-line
        return $this;
    }

    public function withCPF($cpf) {
        $this->cpf = $cpf; // @phpstan-ignore-line
        return $this;
    }

    public function withPhone($phone) {
        $this->phone = $phone; // @phpstan-ignore-line
        return $this;
    }

    public function withBirthdate($birthdate) {
        $this->birthdate = $birthdate; // @phpstan-ignore-line
        return $this;
    }
}
