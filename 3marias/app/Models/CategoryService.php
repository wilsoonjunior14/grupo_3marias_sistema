<?php

namespace App\Models;

class CategoryService extends BaseModel
{
    protected $table = "category_services";
    protected $fillable = ["id", "name", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome da Categoria do Serviço é obrigatório.',
        'name.max' => 'Campo Nome da Categoria do Serviço permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome da Categoria do Serviço deve conter no mínimo 3 caracteres.'
    ];

    static function getByName(string $name) {
        return CategoryService::where("deleted", false)
        ->where("name", $name)
        ->get()
        ->first();
    }
}
