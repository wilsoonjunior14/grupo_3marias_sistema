<?php

namespace App\Models;

class Project extends BaseModel
{
    protected $table = "projects";
    protected $fillable = ["id", "name", "description", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "description"];

    static $rules = [
        'name' => 'bail|required|string|max:255|min:3',
        'description' => 'required|string|max:1000|min:3',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome do Projeto é obrigatório.',
        'name.max' => 'Campo Nome do Projeto permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome do Projeto deve conter no mínimo 3 caracteres.',
        'name.string' => 'Campo Nome do Projeto está inválido.',
        'description.required' => 'Campo Descrição do Projeto é obrigatório.',
        'description.max' => 'Campo Descrição do Projeto permite no máximo 1000 caracteres.',
        'description.min' => 'Campo Descrição do Projeto deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição do Projeto está inválido.',
    ];
}
