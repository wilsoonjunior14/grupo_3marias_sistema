<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = "products";
    protected $fillable = ["id", "product", "category_product_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["product", "category_product_id"];

    static $rules = [
        'product' => 'bail|required|max:255|min:3',
        'category_product_id' => 'required',
    ];

    static $rulesMessages = [
        'product.required' => 'Campo Nome do Produto é obrigatório.',
        'product.max' => 'Campo Nome do Produto permite no máximo 255 caracteres.',
        'product.min' => 'Campo Nome do Produto deve conter no mínimo 3 caracteres.',
        'category_product_id.required' => 'Campo ID da Categoria do Produto é obrigatório.'
    ];

    public function category_product(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(CategoryProduct::class, "id", "category_product_id");
    }

    public function getAll(string $orderBy) {
        return $this::where("deleted", false)
        ->with("category_product")
        ->orderBy($orderBy)
        ->get();
    }
}
