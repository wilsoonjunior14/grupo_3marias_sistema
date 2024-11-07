<?php

namespace App\Models;

class Broker extends BaseModel
{
    protected $table = "brokers";
    protected $fillable = ["id", "name", "cpf", "phone", "email",
    "address_id", "creci",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["id", "name", "cpf", "phone", "email"];

    static $rules = [
        'name' => 'bail|string|required|max:255|min:3',
        'email' => 'email:strict|string|max:100|min:3',
        'creci' => 'bail|required|string|max:255|min:3',
        'phone' => 'celular_com_ddd|max:20|min:10|string',
        'cpf' => 'cpf',
        'address_id' => 'integer|exists:addresses,id'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Corretor é obrigatório.',
        'name.max' => 'Campo Nome Completo do Corretor permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Corretor deve conter no mínimo 3 caracteres.',
        'name.string' => 'Campo Nome Completo do Corretor está inválido.',
        'cpf.cpf' => 'Campo CPF está inválido.',
        'email.required' => 'Campo Email do Corretor é obrigatório.',
        'email.email' => 'Campo Email do Corretor está inválido.',
        'email.max' => 'Campo Email do Corretor permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Corretor deve conter no mínimo 3 caracteres.',
        'creci.required' => 'Campo CRECI do Corretor é obrigatório.',
        'creci.string' => 'Campo CRECI do Corretor está inválido.',
        'creci.max' => 'Campo CRECI do Corretor permite no máximo 255 caracteres.',
        'creci.min' => 'Campo CRECI do Corretor deve conter no mínimo 3 caracteres.',
        'phone.required' => 'Campo Telefone do Corretor é obrigatório.',
        'phone.string' => 'Campo Telefone do Corretor está com tipo inválido.',
        'phone.max' => 'Campo Telefone do Corretor permite no máximo 20 caracteres.',
        'phone.min' => 'Campo Telefone do Corretor deve conter no mínimo 10 caracteres.',
        'phone.celular_com_ddd' => 'Campo Telefone do Corretor está inválido.',
        'address_id.integer' => 'Campo Endereço do Corretor está inválido.',
        'address_id.exists' => 'Campo Endereço do Corretor não foi criado corretamente.'
    ];

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Address::class, "id", "address_id");
    }
}
