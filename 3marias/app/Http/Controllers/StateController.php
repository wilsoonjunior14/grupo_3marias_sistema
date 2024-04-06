<?php

namespace App\Http\Controllers;

use App\Business\StateBusiness;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InvalidValueException;
use App\Models\Country;
use App\Models\Logger;
use App\Models\State;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class StateController extends Controller implements APIController
{

    public $stateBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o StateController em {$startTime}.");
        $this->stateBusiness = new StateBusiness();
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
        $states = $this->stateBusiness->getAll();
        return ResponseUtils::getResponse($states, 200);
    }

    /**
     * Creates a state.
     */
    public function store(Request $request) {
        $state = $this->stateBusiness->create(request: $request);
        return ResponseUtils::getResponse($state, 201);
    } 

    /**
     * Gets a state by id.
     */
    public function show($id) {
        $state = $this->stateBusiness->getById(id: $id);
        return ResponseUtils::getResponse($state, 200);
    }

    /**
     * Deletes a states by id.
     */
    public function destroy($id) {
        $state = $this->stateBusiness->delete(id: $id);
        return ResponseUtils::getResponse($state, 200);
    }

    /**
     * Updates a state.
     */
    public function update(Request $request, $id) {
        $state = $this->stateBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($state, 200);
    }

    /**
     * Creates a state.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
