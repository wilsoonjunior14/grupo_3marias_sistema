<?php

namespace App\Models;

class DocumentType extends BaseModel
{
    protected $table = "document_types";
    protected $fillable = ["id", "name", 
    "description", "has_validation",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name",
    "description", "has_validation"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3',
        'description' => 'bail|required|max:255|min:3'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome do Documento é obrigatório.',
        'name.max' => 'Campo Nome do Documento permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome do Documento deve conter no mínimo 3 caracteres.',
        'description.required' => 'Campo Descrição do Documento é obrigatório.',
        'description.max' => 'Campo Descrição do Documento permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Documento deve conter no mínimo 3 caracteres.',
    ];

}
