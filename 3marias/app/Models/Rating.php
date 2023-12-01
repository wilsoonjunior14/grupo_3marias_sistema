<?php

namespace App\Models;

class Rating extends BaseModel
{
    protected $table = "ratings";
    protected $fillable = ["id", "value", "description", "enterprise_id", "user_id",
     "deleted", "created_at", "updated_at"];

    static $rules = [
        'value' => 'required|numeric|in:1,2,3,4,5',
        'description' => 'string|max:500|min:3',
        'enterprise_id' => 'required|numeric',
        'user_id' => 'required|numeric'
    ];

    static $rulesMessages = [
         'value.required' => 'Campo valor da avaliação é obrigatório.',
         'value.numeric' => 'Campo valor da avaliação deve conter números.',
         'value.in' => 'Campo valor da avaliação contém valores não permitidos.',
         'description.string' => 'Campo descrição deve conter caracteres.',
         'description.max' => 'Campo valor da avaliação deve conter no máximo 500 caracteres.',
         'description.min' => 'Campo valor da avaliação deve conter no mínimo 3 caracteres.',
         'enterprise_id' => 'Campo de identificação da empresa é obrigatório.',
         'user_id' => 'Campo de identificação de usuário é obrigatório.'
    ];
}
