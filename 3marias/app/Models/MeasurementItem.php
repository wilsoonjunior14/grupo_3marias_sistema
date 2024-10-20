<?php

namespace App\Models;

class MeasurementItem extends BaseModel
{
    protected $table = "measurement_items";
    protected $fillable = ["id", "service", "deleted", "created_at", "updated_at"];

    static $rules = [
        'service' => 'bail|required|string|max:255|min:3'
    ];

    static $rulesMessages = [
         'service.required' => 'Campo descrição é obrigatório.',
         'service.string' => 'Campo descrição deve conter caracteres.',
         'service.max' => 'Campo descrição permite no máximo 100 caracteres.',
         'service.min' => 'Campo descrição deve conter no mínimo 3 caracteres.'
    ];
}
