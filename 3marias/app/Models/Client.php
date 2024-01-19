<?php

namespace App\Models;

class Client extends BaseModel
{
    protected $table = "clients";
    protected $fillable = ["id", "name", "rg", "rg_organ", "rg_date", "cpf", "state", "nationality", "ocupation", "email", 
    "phone", "birthdate", "address_id",
    "name_dependent", "rg_dependent", "cpf_dependent", "nationality_dependent", "ocupation_dependent", "email_dependent", 
    "phone_dependent", "birthdate_dependent",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "rg", "rg_organ", "rg_date", "cpf", "state", "nationality", "ocupation", "email", 
    "phone", "birthdate", "address_id",
    "name_dependent", "rg_dependent", "cpf_dependent", "nationality_dependent", "ocupation_dependent", "email_dependent", 
    "phone_dependent", "birthdate_dependent"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'rg' => 'required|min:13|max:14',
        'rg_organ' => 'required|min:3|max:11',
        'rg_date' => 'required|date',
        'cpf' => 'required|cpf|unique:clients',
        'state' => 'required|in:Solteiro,Casado,Divorciado,Viúvo',
        'nationality' => 'bail|required|max:255|min:3',
        'ocupation' => 'bail|required|max:255|min:3',
        'email' => 'required|email:strict|max:100|min:3',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'birthdate' => 'date',
        'name_dependent' => 'bail|max:255|min:3',
        'rg_dependent' => 'min:13|max:14|different:rg',
        'rg_dependent_organ' => 'min:3|max:11',
        'rg_dependent_date' => 'date',
        'cpf_dependent' => 'cpf|unique:clients|different:cpf',
        'nationality_dependent' => 'bail|max:255|min:3',
        'ocupation_dependent' => 'bail|max:255|min:3',
        'email_dependent' => 'email:strict|max:100|min:3|different:email',
        'phone_dependent' => 'celular_com_ddd|max:20|min:10|different:phone',
        'birthdate_dependent' => 'date'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Cliente é obrigatório.',
        'name.max' => 'Campo Nome Completo do Cliente permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Cliente deve conter no mínimo 3 caracteres.',
        'rg.required' => 'Campo RG do Cliente é obrigatório.',
        'rg.max' => 'Campo RG do Cliente permite no máximo 14 caracteres.',
        'rg.min' => 'Campo RG do Cliente deve conter no mínimo 13 caracteres.',
        'rg_organ.required' => 'Campo Órgão do RG do Cliente é obrigatório.',
        'rg_organ.max' => 'Campo Órgão RG do Cliente permite no máximo 11 caracteres.',
        'rg_organ.min' => 'Campo Órgão RG do Cliente deve conter no mínimo 3 caracteres.',
        'rg_date.required' => 'Campo Órgão do RG do Cliente é obrigatório.',
        'rg_date.date' => 'Campo de Data de Emissão do RG do Cliente é inválido.',
        'cpf.required' => 'Campo CPF do Cliente é obrigatório.',
        'cpf.cpf' => 'Campo CPF é inválido.',
        'cpf.unique' => 'Campo CPF já existente na base de dados.',
        'state.required' => 'Campo Estado Civil do Cliente é obrigatório.',
        'state.in' => 'Campo Estado Civil do Cliente é inválido.',
        'nationality.required' => 'Campo Nacionalidade do Cliente é obrigatório.',
        'nationality.max' => 'Campo Nacionalidade do Cliente permite no máximo 255 caracteres.',
        'nationality.min' => 'Campo Nacionalidade do Cliente deve conter no mínimo 3 caracteres.',
        'ocupation.required' => 'Campo Profissão do Cliente é obrigatório.',
        'ocupation.max' => 'Campo Profissão do Cliente permite no máximo 255 caracteres.',
        'ocupation.min' => 'Campo Profissão do Cliente deve conter no mínimo 3 caracteres.',
        'email.required' => 'Campo Email do Cliente é obrigatório.',
        'email.email' => 'Campo Email do Cliente está inválido.',
        'email.max' => 'Campo Email do Cliente permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Cliente deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Cliente é obrigatório.',
        'phone.max' => 'Campo Telefone do Cliente permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Cliente deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Cliente está inválido.',
        'birthdate.date' => 'Campo de Data de Nascimento do Cliente é inválido.',
        'name_dependent.required' => 'Campo Nome Completo do Cônjugue é obrigatório.',
        'name_dependent.max' => 'Campo Nome Completo do Cônjugue permite no máximo 255 caracteres.',
        'name_dependent.min' => 'Campo Nome Completo do Cônjugue deve conter no mínimo 3 caracteres.',
        'rg_dependent.required' => 'Campo RG do Cônjugue é obrigatório.',
        'rg_dependent.max' => 'Campo RG do Cônjugue permite no máximo 14 caracteres.',
        'rg_dependent.min' => 'Campo RG do Cônjugue deve conter no mínimo 13 caracteres.',
        'rg_dependent.different' => 'Campo RG do Cônjugue deve ser diferente do RG do Cliente.',
        'rg_dependent_organ.required' => 'Campo Órgão do RG do Cônjugue é obrigatório.',
        'rg_dependent_organ.max' => 'Campo Órgão RG do Cônjugue permite no máximo 11 caracteres.',
        'rg_dependent_organ.min' => 'Campo Órgão RG do Cônjugue deve conter no mínimo 3 caracteres.',
        'rg_dependent_date.required' => 'Campo Órgão do RG do Cônjugue é obrigatório.',
        'rg_dependent_date.date' => 'Campo de Data de Emissão do RG do Cônjugue é inválido.',
        'cpf_dependent.required' => 'Campo CPF do Cônjugue é obrigatório.',
        'cpf_dependent.cpf' => 'Campo CPF do Cônjugue é inválido.',
        'cpf_dependent.unique' => 'Campo CPF do Cônjugue já existente na base de dados.',
        'cpf_dependent.different' => 'Campo CPF do Cônjugue deve ser diferente do CPF do Cliente.',
        'nationality_dependent.required' => 'Campo Nacionalidade do Cônjugue é obrigatório.',
        'nationality_dependent.max' => 'Campo Nacionalidade do Cônjugue permite no máximo 255 caracteres.',
        'nationality_dependent.min' => 'Campo Nacionalidade do Cônjugue deve conter no mínimo 3 caracteres.',
        'ocupation_dependent.required' => 'Campo Profissão do Cônjugue é obrigatório.',
        'ocupation_dependent.max' => 'Campo Profissão do Cônjugue permite no máximo 255 caracteres.',
        'ocupation_dependent.min' => 'Campo Profissão do Cônjugue deve conter no mínimo 3 caracteres.',
        'email_dependent.email' => 'Campo Email do Cônjugue está inválido.',
        'email_dependent.max' => 'Campo Email do Cônjugue permite no máximo 100 caracteres.',
        'email_dependent.min' => 'Campo Email do Cônjugue deve conter no mínimo 3 caracteres.',
        'email_dependent.different' => 'Campo Email do Cônjugue deve ser diferente do Email do Cliente.',
        'phone_dependent.max' => 'Campo Telefone do Cônjugue permite no máximo 20 caracteres.',
        'phone_dependent.min' => 'Campo Telefone do Cônjugue deve conter no mínimo 10 caracteres.',
        'phone_dependent.celular_com_ddd' => 'Campo Telefone do Cônjugue está inválido.',
        'phone_dependent.different' => 'Campo Telefone do Cônjugue deve ser diferente do Telefone do Cliente.',
        'birthdate_dependent.date' => 'Campo de Data de Nascimento do Cônjugue é inválido.'
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }

    public function getByNameAndCPF(string $name, string $cpf) {
        return $this::where("deleted", false)
        ->where("name", "like", "%" . $name . "%")
        ->where("cpf", "like", "%" . $cpf . "%")
        ->get();
    }
}
