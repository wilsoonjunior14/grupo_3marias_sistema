<?php

namespace App\Models;

class StockItem extends BaseModel
{
    protected $table = "cost_center_items";
    protected $fillable = ["id", 
    "quantity", "value", "product_id", "cost_center_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["quantity", "value", "product_id", "cost_center_id"];

    static $rules = [
        'quantity' => 'required|integer|gt:0',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|gt:0',
        'product_id' => 'required|integer|gt:0',
        'cost_center_id' => 'required|integer|gt:0'
    ];

    static $rulesMessages = [
        'quantity.required' => 'Campo Quantidade é obrigatório.',
        'quantity.integer' => 'Campo Quantidade está inválido.',
        'quantity.gt' => 'Campo Quantidade está inválido.',
        'value.required' => 'Campo Valor Unitário é obrigatório.',
        'value.regex' => 'Campo Valor Unitário está inválido.',
        'value.gt' => 'Campo Valor Unitário está inválido.',
        'product_id.required' => 'Campo Identificador do Produto é obrigatório.',
        'product_id.integer' => 'Campo Identificador do Produto está inválido.',
        'product_id.gt' => 'Campo Identificador do Produto está inválido.',
        'cost_center_id.required' => 'Campo Identificador do Centro de Custo é obrigatório.',
        'cost_center_id.integer' => 'Campo Identificador do Centro de Custo está inválido.',
        'cost_center_id.gt' => 'Campo Identificador do Centro de Custo está inválido.',
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(Product::class, "id", "product_id");
    }

    public function getItemsByStock(int $id) {
        return $this::where("deleted", false)
        ->where("cost_center_id", $id)
        ->with("product")
        ->get();
    }
}
