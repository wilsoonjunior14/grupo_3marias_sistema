<?php

namespace App\Http\Controllers;

use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\InputValidationException;
use App\Models\Country;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class CountryController extends Controller implements APIController
{
    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o CountryController em {$startTime}.");
    }

    /**
     * Gets all countries.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de países.");

        $countries = (new Country())->getAll("name");
        $amount = count($countries);
        Logger::info("Foram recuperados {$amount} países.");

        Logger::info("Finalizando a recuperação de países.");
        return ResponseUtils::getResponse($countries, 200);
    }

    /**
     * Creates a country.
     */
    public function store(Request $request) {
        Logger::info("Iniciando a criação de país.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $this->validateCountryData(request: $request);
        
        Logger::info("Salvando o novo país.");
        $country = new Country($data); 
        $country->deleted = false;
        $country->save();
        Logger::info("Finalizando a atualização de país.");
        return ResponseUtils::getResponse($country, 201);
    }

    /**
     * Gets a country by id.
     */
    public function show($id) {
        Logger::info("Iniciando a recuperação de país.");
        $country = $this->validateCountryId(id: $id);
        Logger::info("Finalizando a recuperação de país.");
        return ResponseUtils::getResponse($country, 200);
    }

    /**
     * Deletes a countries by id.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção de país.");
        $country = $this->validateCountryId(id: $id);
        $country->deleted = true;
        $country->save();
        Logger::info("Finalizando a deleção de país.");
        return ResponseUtils::getResponse($country, 200);
    }

    /**
     * Updates a country.
     */
    public function update(Request $request, $id) {
        Logger::info("Iniciando a atualização de país.");
        $data = $request->all();

        Logger::info("Validando as informações fornecidas.");
        $country = $this->validateCountryId(id: $id);
        $this->validateCountryData(request: $request, id: $id);

        Logger::info("Atualizando os dados do país.");
        $countryUpdated = UpdateUtils
        ::processFieldsToBeUpdated($country, $data, Country::$fieldsToBeUpdated);
        
        Logger::info("Salvando as atualizações.");
        $countryUpdated->save();
        Logger::info("Finalizando a atualização de país.");
        return ResponseUtils::getResponse($countryUpdated, 200);
    }

    /**
     * Creates a country.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

    /**
     * Validates the country data.
     */
    private function validateCountryData(Request $request, int $id = null) {
        $data = $request->all();
        $validator = new ModelValidator(Country::$rules, Country::$rulesMessages);
        $countryValidation = $validator->validate($data);
        if ($countryValidation !== null) {
            throw new InputValidationException($countryValidation);
        }

        $condition = [["name", "like", "%" . $data["name"] . "%"]];
        $existsCountry = (new Country())->existsEntity(condition: $condition, id: $id);
        if ($existsCountry) {
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }
    }

    /**
     * Validates the country id.
     */
    private function validateCountryId(int $id) : Country {
        try {
            $country = (new Country)->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "país"));
        }
        return $country;
    }
}
