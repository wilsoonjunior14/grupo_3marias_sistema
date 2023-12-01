<?php

namespace App\Models;

class Enterprise extends BaseModel
{
    protected $table = "enterprises";
    protected $fillable = ["id", "name", "image", "description", 
    "email", "phone", "status", "category_id", "activeDate", "inactiveDate",
    "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "description", "email", 
    "phone", "status", "category_id", "deleted", "inactiveDate"];

    static $rules = [
        'name' => 'required|max:100|min:3',
        'image' => 'required',
        'description' => 'required|max:255|min:3',
        'email' => 'email:strict|required',
        'phone' => 'required|celular_com_ddd|max:20|min:10',
        'status' => 'required|in:waiting,active,inactive',
        'category_id' => 'required'
    ];

    static $rulesMessages = [ 
        'name.required' => 'Campo nome é obrigatório.',
        'name.max' => 'Campo nome permite no máximo 100 caracteres.',
        'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'image.required' => 'Campo de imagem é obrigatório.',
        'description.required' => 'Campo descrição é obrigatório.',
        'description.max' => 'Campo descrição permite no máximo 255 caracteres.',
        'description.min' => 'Campo descrição deve conter no mínimo 3 caracteres.',
        'email.required' => 'Campo email é obrigatório.',
        'email.email' => 'Campo email está inválido.',
        'phone.required' => 'Campo telefone é obrigatório.',
        'phone.max' => 'Campo telefone permite no máximo 20 caracteres.',
        'phone.min' => 'Campo telefone deve conter no mínimo 10 caracteres.',
        'phoneNumber.celular_com_ddd' => 'Campo telefone está inválido.',
        'status.required' => 'Campo status é obrigatório.',
        'status.in' => 'Campo status com valor inválido.',
        'category_id.required' => 'Campo identificador de categoria é obrigatório.',
    ];

    public function category() {
        return $this->hasOne(Category::class, "id", "category_id")->where("deleted", false);
    }

    public function addresses() {
        return $this->hasMany(Address::class, "enterprise_id", "id")->where("deleted", false);
    }

    public function linkedSystems() {
        return $this->hasMany(LinkedSystem::class, "enterprise_id", "id")->where("deleted", false);
    }

    public function getByNameOrEmail(string $name, string $email, int $enterpriseId = null) {
        if ($enterpriseId === null) {
            return Enterprise
            ::where("deleted", false)
            ->where("name", $name)
            ->orWhere("email", $email)
            ->get();
        } else {
            return Enterprise
            ::where("deleted", false)
            ->where("id", "!=", $enterpriseId)
            ->where("name", $name)
            ->orWhere("email", $email)
            ->get();
        }   
    }

    public function getAll(string $orderBy = "name") {
        return Enterprise::where("deleted", false)
        ->with("addresses.city")
        ->with("category")
        ->with("linkedSystems")
        ->orderBy($orderBy)
        ->get();
    }

    public function getById($id) {
        return Enterprise::where("deleted", false)
        ->where("id", $id)
        ->with("addresses.city")
        ->with("linkedSystems")
        ->with("category")
        ->get()
        ->first();
    }

    public function getEnterprisesByCityAndCategory($idCity, $idCategory, $search = "") {
        if (!empty($search)) {
            $queryByNameOrDescription = Enterprise::where("deleted", false)
                ->where("category_id", $idCategory)
                ->where("name", "like", "%" . $search . "%")
                ->orWhere("description", "like", "%" . $search . "%")
                ->whereHas("addresses", function($query) use ($idCity, $search) {
                    $query
                    ->where("city_id", $idCity);
                });
            
            if (count($queryByNameOrDescription->get()) > 0) {
                return $queryByNameOrDescription
                ->with("addresses")
                ->with("linkedSystems")
                ->get();
            }

            return $this->searchWithAddressKeywords($idCity, $idCategory, $search);
        } else {
            return $this->searchWithoutKeywords($idCity, $idCategory);
        }
    }

    private function searchWithoutKeywords(int $idCity, int $idCategory) {
        return Enterprise::where("deleted", false)
        ->where("category_id", $idCategory)
        ->whereHas("addresses", function($query) use ($idCity) {
            $query
            ->where("city_id", $idCity);
        })
        ->with("addresses")
        ->with("linkedSystems")
        ->get();
    }

    private function searchWithAddressKeywords(int $idCity, int $idCategory, string $search) {
        return Enterprise::where("deleted", false)
        ->where("category_id", $idCategory)
        ->whereHas("addresses", function($addressQuery) use ($idCity, $search) {
                $addressQuery
                ->where("city_id", $idCity)
                ->where("address", "like", "%" . $search . "%")
                ->orWhere("neighborhood", "like", "%" . $search . "%")
                ->orWhere("zipCode", "like", "%" . $search . "%");
        })
        ->with("addresses")
        ->with("linkedSystems")
        ->get();
    }
}
