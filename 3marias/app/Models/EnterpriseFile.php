<?php

namespace App\Models;

class EnterpriseFile extends BaseModel
{
    protected $table = "enterprise_files";
    protected $fillable = ["id", "name",
    "description", "url", "enterprise_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "description",
    "url", "enterprise_id"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3|ends_with:.pdf',
        'description' => 'bail|required|max:255|min:3',
        'url' => 'bail|required|max:255|url',
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome do Arquivo é obrigatório.',
        'name.max' => 'Campo Nome do Arquivo permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome do Arquivo deve conter no mínimo 3 caracteres.',
        'name.ends_with' => 'Arquivo deve ser pdf.',
        'description.required' => 'Campo Descrição do Arquivo é obrigatório.',
        'description.max' => 'Campo Descrição do Arquivo permite no máximo 255 caracteres.',
        'description.min' => 'Campo Descrição do Arquivo deve conter no mínimo 3 caracteres.',
        'url.required' => 'Campo URL do arquivo é obrigatório.',
        'url.max' => 'Campo URL do arquivo permite no máximo 255 caracteres.',
        'url.url' => 'Campo URL do arquivo é inválido.',
    ];

    public function getByEnterprise(int $enterpriseId) {
        return (new EnterpriseFile())->where("deleted", false)
        ->where("enterprise_id", $enterpriseId)
        ->orderBy("name")
        ->get();
    }
}
