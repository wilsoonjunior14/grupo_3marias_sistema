<?php

namespace App\Models;

class CategoryService extends BaseModel
{
    protected $table = "category_services";
    protected $fillable = ["id", "name", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name"];

    static $rules = [
        'name' => 'bail|string|required|max:255|min:3|regex:/^[\pL\s]+$/u'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome da Categoria do Serviço é obrigatório.',
        'name.string' => 'Campo Nome da Categoria do Serviço está inválido.',
        'name.max' => 'Campo Nome da Categoria do Serviço permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome da Categoria do Serviço deve conter no mínimo 3 caracteres.',
        'name.regex' => 'Campo Nome da Categoria do Serviço deve conter somente letras.'
    ];

    static function getByName(string $name) {
        return (new CategoryService())->where("deleted", false)
        ->where("name", $name)
        ->get()
        ->first();
    }

    public function withName($name) {
        $this->name = $name; // @phpstan-ignore-line
        return $this;
    }
}
