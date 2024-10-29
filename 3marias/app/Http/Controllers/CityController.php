<?php

namespace App\Http\Controllers;

use App\Business\CityBusiness;
use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\InputValidationException;
use App\Models\City;
use App\Models\Logger;
use App\Models\State;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class CityController extends Controller implements APIController
{

    private string $entity = "cidades";

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o CityController em {$startTime}.");
    }

    /**
     * Gets all cities.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de {$this->entity}.");

        $entities = (new City())->getAll("name");
        $amount = count($entities);
        Logger::info("Foram recuperados {$amount} {$this->entity}.");

        Logger::info("Finalizando a recuperação de {$this->entity}.");
        return ResponseUtils::getResponse($entities, 200);
    }

    /**
     * Gets all cities.
     */
    public function citiesuf() {
        $entities = (new CityBusiness())->getCitiesWithUF();

        $response = [];
        foreach ($entities as $entity) {
            $response[] = [
                "id" => $entity->id,
                "name" => $entity->name . "/" . $entity->state->acronym
            ];
        }
        return ResponseUtils::getResponse($response, 200);
    }

    /**
     * Creates a city.
     */
    public function store(Request $request) {
        Logger::info("Iniciando a criação de cidade.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $this->validateCityData(request: $request);
        
        Logger::info("Salvando a nova cidade.");
        $city = new City($data);
        $city->save();
        Logger::info("Finalizando a atualização de cidade.");
        return ResponseUtils::getResponse($city, 201);
    }

    /**
     * Gets a city by id.
     */
    public function show($id) {
        Logger::info("Iniciando a recuperação de cidade.");
        $city = $this->validateCityId(id: $id);
        Logger::info("Finalizando a recuperação de cidade.");
        return ResponseUtils::getResponse($city, 200);
    }

    /**
     * Deletes a city by id.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção da entidade {$id}.");
        $city = $this->validateCityId(id: $id);
        $city->deleted = true;
        $city->save();
        Logger::info("Finalizando a deleção da entidade {$id}.");
        return ResponseUtils::getResponse($city, 200);
    }

    /**
     * Updates a city.
     */
    public function update(Request $request, $id) {
        Logger::info("Iniciando a atualização de cidade.");
        $data = $request->all();

        Logger::info("Validando as informações fornecidas.");
        $city = $this->validateCityId(id: $id);
        $this->validateCityData(request: $request, id: $id);

        Logger::info("Atualizando os dados do cidade.");
        $cityUpdated = UpdateUtils
        ::processFieldsToBeUpdated($city, $data, City::$fieldsToBeUpdated);
        
        Logger::info("Salvando as atualizações.");
        $cityUpdated->save();
        Logger::info("Finalizando a atualização de cidade.");
        return ResponseUtils::getResponse($cityUpdated, 200);
    }

    /**
     * Creates a country.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

    /**
     * Validates the city data.
     */
    private function validateCityData(Request $request, int $id = null) {
        $data = $request->all();
        $validator = new ModelValidator(City::$rules, City::$rulesMessages);
        $cityValidation = $validator->validate($data);
        if (!is_null($cityValidation)) {
            throw new InputValidationException($cityValidation);
        }

        try {
            $state = (new State())->getById($data["state_id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new EntityAlreadyExistsException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "estado"));
        }

        $condition = [["name", "like", "%" . $data["name"] . "%"]];
        $existsCity = (new City())->existsEntity(condition: $condition, id: $id);
        if ($existsCity) {
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }
    }

    /**
     * Validates the city id.
     */
    private function validateCityId(int $id) : City {
        try {
            $city = (new CityBusiness())->getById($id, mergeFields: false);
        } catch (\Exception $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cidade"));
        }
        return $city;
    }
}
