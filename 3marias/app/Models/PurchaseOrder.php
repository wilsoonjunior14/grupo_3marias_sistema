<?php

namespace App\Models;

class PurchaseOrder extends BaseModel
{
    protected $table = "purchase_orders";
    protected $fillable = ["id", 
    "description", "date", "status", "partner_id", "cost_center_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["description", "date", "status", "partner_id", "cost_center_id"];

    static $rules = [
        'description' => 'required|max:100|min:3|string',
        'date' => 'required|date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'status' => 'required|in:0,1,2',
        'partner_id' => 'required|integer|gt:0',
        'cost_center_id' => 'required|integer|gt:0'
    ];

    static $rulesMessages = [
        'description.required' => 'Campo Descrição da Ordem de Compra é obrigatório.',
        'description.max' => 'Campo Descrição da Ordem de Compra permite no máximo 1000 caracteres.',
        'description.min' => 'Campo Descrição da Ordem de Compra deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição da Ordem de Compra está inválido.',
        'date.required' => 'Campo Data da Ordem de Compra é obrigatório.',
        'date.date' => 'Campo Data da Ordem de Compra está inválido.',
        'date.regex' => 'Campo Data da Ordem de Compra está inválido.',
        'partner_id.required' => 'Campo Identificador do Parceiro/Fornecedor é obrigatório.',
        'partner_id.integer' => 'Campo Identificador do Parceiro/Fornecedor está inválido.',
        'partner_id.gt' => 'Campo Identificador do Parceiro/Fornecedor está inválido.',
        'cost_center_id.required' => 'Campo Identificador do Centro de Custo é obrigatório.',
        'cost_center_id.integer' => 'Campo Identificador do Centro de Custo está inválido.',
        'cost_center_id.gt' => 'Campo Identificador do Centro de Custo está inválido.',
        'status.required' => 'Campo Status da Ordem de Compra é obrigatório.',
        'status.in' => 'Campo Status da Ordem de Compra está inválido.'
    ];

    public function withDescription($description) {
        $this->description = $description;// @phpstan-ignore-line
        return $this;
    }

    public function withDate($date) {
        $this->date = $date;// @phpstan-ignore-line
        return $this;
    }

    public function withPartnerId($partnerId) {
        $this->partner_id = $partnerId;// @phpstan-ignore-line
        return $this;
    }

    public function withCostCenterId($cost_center_id) {
        $this->cost_center_id = $cost_center_id;// @phpstan-ignore-line
        return $this;
    }

    public function withStatus($status) {
        $this->status = $status;// @phpstan-ignore-line
        return $this;
    }

    public function withProducts($products) {
        $this->products = $products;// @phpstan-ignore-line
        return $this;
    }
}
