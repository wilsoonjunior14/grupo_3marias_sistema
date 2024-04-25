<?php

namespace App\Models;

class Engineer extends BaseModel
{
    protected $table = "engineers";
    protected $fillable = ["id", "name", "email", "crea",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "email", "crea"];

    static $rules = [
        'name' => 'string|bail|required|max:255|min:3|regex:/^[A-Z a-z\s]+$/',
        'crea' => 'max:10|regex:/^\d+$/',
        'email' => 'email:strict|max:100|min:3|string'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome Completo do Engenheiro é obrigatório.',
        'name.max' => 'Campo Nome Completo do Engenheiro permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome Completo do Engenheiro deve conter no mínimo 3 caracteres.',
        'name.string' => 'Campo Nome Completo do Engenheiro está inválido.',
        'name.regex' => 'Campo Nome Completo do Engenheiro está inválido.',
        'crea.required' => 'Campo CREA do Engenheiro é obrigatório.',
        'crea.max' => 'Campo CREA do Engenheiro permite no máximo 10 caracteres.',
        'crea.regex' => 'Campo CREA do Engenheiro está inválido.',
        'email.required' => 'Campo Email do Engenheiro é obrigatório.',
        'email.email' => 'Campo Email do Engenheiro está inválido.',
        'email.max' => 'Campo Email do Engenheiro permite no máximo 100 caracteres.',
        'email.min' => 'Campo Email do Engenheiro deve conter no mínimo 3 caracteres.',
    ];

    public function withName($name) {
        $this->name = $name; // @phpstan-ignore-line
        return $this;
    }

    public function withEmail($email) {
        $this->email = $email; // @phpstan-ignore-line
        return $this;
    }

    public function withCrea($crea) {
        $this->crea = $crea; // @phpstan-ignore-line
        return $this;
    }
}
