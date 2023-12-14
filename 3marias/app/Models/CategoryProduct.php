<?php

namespace App\Models;

class CategoryProduct extends BaseModel
{
    protected $table = "category_products";
    protected $fillable = ["id", "name", "category_products_father_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "category_products_father_id"];

    static $rules = [
        'name' => 'bail|required|max:255|min:3'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo Nome da Categoria do Produto é obrigatório.',
        'name.max' => 'Campo Nome da Categoria do Produto permite no máximo 255 caracteres.',
        'name.min' => 'Campo Nome da Categoria do Produto deve conter no mínimo 3 caracteres.'
    ];

    public function category_product(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(CategoryProduct::class, "id", "category_products_father_id");
    }

    public function getAll(string $orderBy) {
        return $this::where("deleted", false)
        ->with("category_product")
        ->orderBy($orderBy)
        ->get();
    }

    static function getByName(string $name) {
        return CategoryProduct::where("deleted", false)
        ->where("name", $name)
        ->get()
        ->first();
    }
}
