<?php

namespace App\Models;

class Category extends BaseModel
{

    protected $table = "categories";
    protected $fillable = ["id", "name", "image", "deleted", "created_at", "updated_at"];

    static $rules = [
        'name' => 'required|max:100|min:3',
        'image' => 'required'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo nome é obrigatório.',
        'name.max' => 'Campo nome permite no máximo 100 caracteres.',
        'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'image.required' => 'Campo de imagem é obrigatório.'
    ];

    public function enterprises() {
        return $this->hasMany(Enterprise::class, "category_id", "id")->where("deleted", false);
    }

    public function getCategoryByName($name) {
        return Category::where("deleted", false)
        ->where("name", $name)
        ->get();
    }

    public function getByCity($idCity) {
        $categories = Category::where("deleted", false)
        ->with("enterprises", function($query) use ($idCity) {
            $query->whereHas("addresses", function($queryAddress) use ($idCity) {
                $queryAddress->where("city_id", $idCity);
            });
        })
        ->orderBy("name")
        ->get();

        $response = [];
        foreach ($categories as $category) {
            $category->amount_enterprises = count($category->enterprises);
            
            if ($category->amount_enterprises > 0) {
                unset($category->enterprises);
                $response[] = $category;
            }
        }

        return $response;
    }
}
