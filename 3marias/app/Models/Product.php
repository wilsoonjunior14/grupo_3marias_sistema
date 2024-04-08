<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = "products";
    protected $fillable = ["id", "product", "category_product_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["product", "category_product_id"];

    static $rules = [
        'product' => 'bail|required|string|max:255|min:3',
        'category_product_id' => 'required|integer',
    ];

    static $rulesMessages = [
        'product.required' => 'Campo Nome do Produto é obrigatório.',
        'product.max' => 'Campo Nome do Produto permite no máximo 255 caracteres.',
        'product.min' => 'Campo Nome do Produto deve conter no mínimo 3 caracteres.',
        'product.string' => 'Campo Nome do Produto está inválido.',
        'category_product_id.required' => 'Campo Identificador da Categoria do Produto é obrigatório.',
        'category_product_id.integer' => 'Campo Identificador da Categoria do Produto está inválido.'
    ];

    public function category_product(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(CategoryProduct::class, "id", "category_product_id");
    }

    public function getAll(string $orderBy) {
        return (new Product())->where("deleted", false)
        ->with("category_product")
        ->orderBy($orderBy)
        ->get();
    }
}
