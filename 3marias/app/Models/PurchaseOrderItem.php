<?php

namespace App\Models;

class PurchaseOrderItem extends BaseModel
{
    protected $table = "purchase_order_items";
    protected $fillable = ["id", 
    "quantity", "value", "product_id", "purchase_order_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["quantity", "value", "product_id", "purchase_order_id"];

    static $rules = [
        'quantity' => 'required|integer',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/',
        'product_id' => 'required|integer',
        'purchase_order_id' => 'required|integer'
    ];

    static $rulesMessages = [
        'quantity.required' => 'Campo Quantidade é obrigatório.',
        'quantity.integer' => 'Campo Quantidade está inválido.',
        'value.required' => 'Campo Valor Unitário é obrigatório.',
        'value.regex' => 'Campo Valor Unitário está inválido.',
        'product_id.required' => 'Campo Identificador do Produto é obrigatório.',
        'product_id.integer' => 'Campo Identificador do Produto está inválido.',
        'purchase_order_id.required' => 'Campo Identificador da Ordem de Compra é obrigatório.',
        'purchase_order_id.integer' => 'Campo Identificador da Ordem de Compra está inválido.',
    ];

    public function getByPurchaseOrder(int $id) {
        return $this::where("deleted", false)
        ->where("purchase_order_id", $id)
        ->get();
    }
}
