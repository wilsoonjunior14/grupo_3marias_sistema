<?php

namespace App\Models;

class ServiceOrder extends BaseModel
{
    protected $table = "service_orders";
    protected $fillable = ["id", 
    "description", "date", "status", "service_id", "quantity", "value", "cost_center_id",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["description", "date", "status", "service_id", "quantity", "value", "cost_center_id",];

    static $rules = [
        'description' => 'required|max:100|min:3|string',
        'quantity' => 'required|integer|gt:0',
        'value' => 'required|regex:/^[0-9]+(\.[0-9][0-9]?)?$/|gt:0',
        'service_id' => 'required|integer|gt:0|exists:services,id',
        'date' => 'required|date|regex:/^(\d){4}-(\d){2}-(\d{2})$/',
        'status' => 'required|in:0,1,2',
        'cost_center_id' => 'required|integer|gt:0|exists:cost_centers,id'
    ];

    static $rulesMessages = [
        'description.required' => 'Campo Descrição da Ordem de Serviço é obrigatório.',
        'description.max' => 'Campo Descrição da Ordem de Serviço permite no máximo 100 caracteres.',
        'description.min' => 'Campo Descrição da Ordem de Serviço deve conter no mínimo 3 caracteres.',
        'description.string' => 'Campo Descrição da Ordem de Serviço está inválido.',
        'quantity.required' => 'Campo Quantidade é obrigatório.',
        'quantity.integer' => 'Campo Quantidade está inválido.',
        'quantity.gt' => 'Campo Quantidade está inválido.',
        'value.required' => 'Campo Valor Unitário é obrigatório.',
        'value.regex' => 'Campo Valor Unitário está inválido.',
        'value.gt' => 'Campo Valor Unitário está inválido.',
        'service_id.required' => 'Campo Identificador do Serviço é obrigatório.',
        'service_id.integer' => 'Campo Identificador do Serviço está inválido.',
        'service_id.gt' => 'Campo Identificador do Serviço está inválido.',
        'service_id.exists' => 'Campo Identificador do Serviço não existe.',
        'date.required' => 'Campo Data da Ordem de Serviço é obrigatório.',
        'date.date' => 'Campo Data da Ordem de Serviço está inválido.',
        'date.regex' => 'Campo Data da Ordem de Serviço está inválido.',
        'cost_center_id.required' => 'Campo Identificador do Centro de Custo é obrigatório.',
        'cost_center_id.integer' => 'Campo Identificador do Centro de Custo está inválido.',
        'cost_center_id.gt' => 'Campo Identificador do Centro de Custo está inválido.',
        'cost_center_id.exists' => 'Campo Identificador do Centro de Custo não existe.',
        'status.required' => 'Campo Status da Ordem de Serviço é obrigatório.',
        'status.in' => 'Campo Status da Ordem de Serviço está inválido.'
    ];

    public function withDescription($description) {
        $this->description = $description;// @phpstan-ignore-line
        return $this;
    }

    public function withDate($date) {
        $this->date = $date;// @phpstan-ignore-line
        return $this;
    }

    public function withValue($value) {
        $this->value = $value;// @phpstan-ignore-line
        return $this;
    }

    public function withQuantity($quantity) {
        $this->quantity = $quantity;// @phpstan-ignore-line
        return $this;
    }

    public function withServiceId($serviceId) {
        $this->service_id = $serviceId;// @phpstan-ignore-line
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
}
