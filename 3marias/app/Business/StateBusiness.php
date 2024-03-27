<?php

namespace App\Business;

use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InputValidationException;
use App\Models\State;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class StateBusiness {

    public function getAll() {
        Logger::info("Iniciando a recuperação de estado.");
        $states = (new State())->getAll("name");
        $amount = count($states);
        Logger::info("Foram recuperados {$amount} estado.");
        Logger::info("Finalizando a recuperação de estado.");
        return $states;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação da entidade.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $this->validateStateData(request: $request);
        
        Logger::info("Salvando o nova entidade.");
        $state = new State($data);
        $state->save();
        Logger::info("Finalizando a atualização da entidade.");
    }

    public function getById(int $id) {
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Estado"));
        }
        Logger::info("Recuperando a entidade: {$id}.");
        $state = (new State)->getById($id);
        if ($state === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }
        return $state;
    }

    public function delete(int $id) {
        Logger::info("Iniciando a deleção da entidade {$id}.");
        $state = $this->getById(id: $id);
        $state->deleted = true;
        $state->save();
        Logger::info("Finalizando a deleção da entidade {$id}.");
    }

    public function update(int $id, Request $request) {
        Logger::info("Iniciando a atualização da entidade {$id}.");
        $data = $request->all();

        Logger::info("Validando as informações fornecidas.");
        $state = $this->getById(id: $id);
        $this->validateStateData(request: $request, id: $id);

        Logger::info("Atualizando os dados da entidade {$id}.");
        $stateUpdated = UpdateUtils::processFieldsToBeUpdated($state, $data, State::$fieldsToBeUpdated);
        
        Logger::info("Salvando as atualizações.");
        $stateUpdated->save();
        Logger::info("Finalizando a atualização de país.");
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

}