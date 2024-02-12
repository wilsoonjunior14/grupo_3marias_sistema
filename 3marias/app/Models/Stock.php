<?php

namespace App\Models;

class Stock extends BaseModel
{
    protected $table = "cost_centers";
    protected $fillable = ["id", "name", "status", "contract_id", "deleted"];

    static $rules = [
            'name' => 'required|max:255|min:3',
            'status' => 'required|in:Ativo,Desativado',
            'contract_id' => 'required'
    ];

    static $rulesMessages = [
         'name.required' => 'Campo Nome do Estoque é obrigatório.',
         'name.max' => 'Campo Nome do Estoque permite no máximo 255 caracteres.',
         'name.min' => 'Campo Nome do Estoque deve conter no mínimo 3 caracteres.',
         'status.required' => 'Campo Status é obrigatório.',
         'status.in' => 'Campo Status contém um valor inválido.',
         'contract_id.required' => 'Campo identificador de contrato é obrigatório.',
    ];

    static $fieldsToBeUpdated = ["name", "status"];
}
