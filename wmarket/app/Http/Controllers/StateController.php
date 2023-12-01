<?php

namespace App\Http\Controllers;

use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InputValidationException;
use App\Exceptions\InvalidValueException;
use App\Models\Country;
use App\Models\Logger;
use App\Models\State;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class StateController extends Controller implements APIController
{

    private string $entity = "estados";

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o StateController em {$startTime}.");
    }

    /**
     * Gets states by country id provided.
     */
    public function getByCountry($idCountry) {
        Logger::info("Iniciando a recuperação de estados.");

        Logger::info("Iniciando a validação do id do país.");
        if ($idCountry <= 0) {
            throw new InvalidValueException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "país"));
        }

        $country = (new Country())->getById($idCountry);
        if ($country === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        $data = (new State())->getByCountry($idCountry);
        $count = count($data);
        Logger::info("Recuperando $count estados.");
        return ResponseUtils::getResponse($data, 200);
    }

    /**
     * Gets all states.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de {$this->entity}.");
        $states = (new State())->getAll("name");
        $amount = count($states);
        Logger::info("Foram recuperados {$amount} {$this->entity}.");
        Logger::info("Finalizando a recuperação de {$this->entity}.");
        return ResponseUtils::getResponse($states, 200);
    }

    /**
     * Creates a state.
     */
    public function store(Request $request) {
        Logger::info("Iniciando a criação da entidade.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $this->validateStateData(request: $request);
        
        Logger::info("Salvando o nova entidade.");
        $state = new State($data);
        $state->save();
        Logger::info("Finalizando a atualização da entidade.");
        return ResponseUtils::getResponse($state, 201);
    } 

    /**
     * Gets a state by id.
     */
    public function show($id) {
        Logger::info("Iniciando a recuperação da entidade {$id}.");
        $state = $this->validateStateId(id: $id);
        Logger::info("Finalizando a recuperação da entidade {$id}.");
        return ResponseUtils::getResponse($state, 200);
    }

    /**
     * Deletes a states by id.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção da entidade {$id}.");
        $state = $this->validateStateId(id: $id);
        $state->deleted = true;
        $state->save();
        Logger::info("Finalizando a deleção da entidade {$id}.");
        return ResponseUtils::getResponse($state, 200);
    }

    /**
     * Updates a state.
     */
    public function update(Request $request, $id) {
        Logger::info("Iniciando a atualização da entidade {$id}.");
        $data = $request->all();

        Logger::info("Validando as informações fornecidas.");
        $state = $this->validateStateId(id: $id);
        $this->validateStateData(request: $request, id: $id);

        Logger::info("Atualizando os dados da entidade {$id}.");
        $stateUpdated = UpdateUtils
        ::processFieldsToBeUpdated($state, $data, State::$fieldsToBeUpdated);
        
        Logger::info("Salvando as atualizações.");
        $stateUpdated->save();
        Logger::info("Finalizando a atualização de país.");
        return ResponseUtils::getResponse($stateUpdated, 200);
    }

    /**
     * Creates a state.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

    /**
     * Validates the state data.
     */
    private function validateStateData(Request $request, int $id = null) {
        $data = $request->all();
        $validator = new ModelValidator(State::$rules, State::$rulesMessages);
        $stateValidation = $validator->validate($data);
        if ($stateValidation !== null) {
            throw new InputValidationException($stateValidation);
        }

        $condition = [["name", "like", "%" . $data["name"] . "%"]];
        $existsState = (new State())->existsEntity(condition: $condition, id: $id);
        if ($existsState) {
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }
    }

    /**
     * Validates the state id.
     */
    private function validateStateId(int $id) : State {
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, $this->entity));
        }
        Logger::info("Recuperando a entidade: {$id}.");
        $state = (new State)->getById($id);
        if ($state === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }
        return $state;
    }
}
