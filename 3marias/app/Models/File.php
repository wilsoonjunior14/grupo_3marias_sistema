<?php

namespace App\Models;

class File extends BaseModel
{
    protected $table = "files";
    protected $fillable = ["id", "description",
    "filename", "client_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "description",
    "url", "enterprise_id"];

    static $rules = [
        'filename' => 'bail|required|max:255|min:3|ends_with:.pdf',
        'description' => 'bail|required|max:255|min:3'
    ];

    static $rulesMessages = [
        'filename.required' => 'Campo Nome do Arquivo é obrigatório.',
        'filename.max' => 'Campo Nome do Arquivo permite no máximo 255 caracteres.',
        'filename.min' => 'Campo Nome do Arquivo deve conter no mínimo 3 caracteres.',
        'filename.ends_with' => 'Arquivo deve ser pdf.',
        'description.required' => 'Campo Descrição do Arquivo é obrigatório.',
        'description.max' => 'Campo Descrição do Arquivo permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Arquivo deve conter no mínimo 3 caracteres.'
    ];

    static function getByClientId(int $clientId) {
        return (new File())->where("deleted", false)
        ->where("client_id", $clientId)
        ->orderBy("description")
        ->get();
    }
}
