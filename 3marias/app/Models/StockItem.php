<?php

namespace App\Models;

use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;

class StockItem extends BaseModel
{
    protected $table = "cost_center_items";
    protected $fillable = ["id", 
    "quantity", "value", "product_id", "cost_center_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["quantity", "value", "product_id", "cost_center_id"];

    static $rules = [
        'quantity' => 'required|integer',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'product_id' => 'required|integer',
        'cost_center_id' => 'required|integer'
    ];

    static $rulesMessages = [
        'quantity.required' => 'Campo Quantidade é obrigatório.',
        'quantity.integer' => 'Campo Quantidade está inválido.',
        'value.required' => 'Campo Valor Unitário é obrigatório.',
        'value.regex' => 'Campo Valor Unitário está inválido.',
        'product_id.required' => 'Campo Identificador do Produto é obrigatório.',
        'product_id.integer' => 'Campo Identificador do Produto está inválido.',
        'cost_center_id.required' => 'Campo Identificador do Centro de Custo é obrigatório.',
        'cost_center_id.integer' => 'Campo Identificador do Centro de Custo está inválido.',
    ];

    public function getItemsByStock(int $id) {
        return $this::where("deleted", false)
        ->where("cost_center_id", $id)
        ->get();
    }
}
