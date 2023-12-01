<?php

namespace App\Models;

class Feedback extends BaseModel
{
    protected $table = "feedback";
    protected $fillable = ["id", "subject", "comment", "rating", "deleted", "created_at", "updated_at"];

    static $rules = [
        'subject' => 'required|max:255|min:3',
        'comment' => 'required|max:500|min:3',
        'rating' => 'required|numeric|in:1,2,3,4,5',
    ];

    static $rulesMessages = [
         'subject.required' => 'Campo Assunto é obrigatório.',
         'subject.max' => 'Campo Assunto permite no máximo 100 caracteres.',
         'subject.min' => 'Campo Assunto deve conter no mínimo 3 caracteres.',
         'comment.required' => 'Campo Comentário é obrigatório.',
         'comment.max' => 'Campo Comentário permite no máximo 500 caracteres.',
         'comment.min' => 'Campo Comentário deve conter no mínimo 3 caracteres.',
         'rating.required' => 'Campo Avaliação é obrigatório.',
         'rating.numeric' => 'Campo Avaliação deve ser um número inteiro.',
         'rating.in' => 'Campo Avaliação contém um valor inválido.'
    ];
}
