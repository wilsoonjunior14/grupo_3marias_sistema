<?php

namespace App\Models;

class State extends BaseModel
{
    protected $table = "states";
    protected $fillable = ["id", "name", "country_id", "acronym", "deleted", "created_at", "updated_at"];

    static $fieldsToBeUpdated = ["name", "country_id", "acronym"];
    static $rules = [
        'name' => 'required|max:100|min:3|regex:/^[\pL\s]+$/u',
        'acronym' => 'required|max:2|alpha',
        'country_id' => 'required'
    ];

    static $rulesMessages = [
        'name.required' => 'Campo nome é obrigatório.',
        'name.max' => 'Campo nome permite no máximo 100 caracteres.',
        'name.min' => 'Campo nome deve conter no mínimo 3 caracteres.',
        'name.regex' => 'Campo nome deve conter somente letras.',
        'acronym.required' => 'Campo sigla é obrigatório.',
        'acronym.alpha' => 'Campo sigla deve conter somente letras.',
        'acronym.max' => 'Campo sigla permite no máximo 2 caracteres.',
        'country_id.required' => 'Campo identificador de país é obrigatório.'
    ];

    public function cities() {
        return $this->hasMany(City::class, "state_id", "id")
        ->where("deleted", false)
        ->orderBy("name");
    }

    public function getByCountry(int $idCountry) {
        return State::where("deleted", false)
        ->where("country_id", $idCountry)
        ->with("cities")
        ->orderBy("name")
        ->get();
    }
}
