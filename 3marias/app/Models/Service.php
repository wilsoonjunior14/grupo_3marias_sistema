<?php

namespace App\Models;

class Service extends BaseModel
{
    protected $table = "services";
    protected $fillable = ["id", "service", "category_service_id", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["service", "category_service_id"];

    static $rules = [
        'service' => 'bail|required|max:255|min:3',
        'category_service_id' => 'required',
    ];

    static $rulesMessages = [
        'service.required' => 'Campo Nome do Serviço é obrigatório.',
        'service.max' => 'Campo Nome do Serviço permite no máximo 255 caracteres.',
        'service.min' => 'Campo Nome do Serviço deve conter no mínimo 3 caracteres.',
        'category_service_id.required' => 'Campo ID da Categoria do Serviço é obrigatório.'
    ];

    public function category_service(): \Illuminate\Database\Eloquent\Relations\HasOne {
        return $this->hasOne(CategoryService::class, "id", "category_service_id");
    }

    public function getAll(string $orderBy) {
        return $this::where("deleted", false)
        ->with("category_service")
        ->orderBy($orderBy)
        ->get();
    }
}
