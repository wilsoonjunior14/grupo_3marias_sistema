<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Logger;
use App\Exceptions\InputValidationException;
use App\Exceptions\InvalidValueException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityAlreadyExistsException;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use App\Validation\ModelValidator;

class GroupController extends Controller implements APIController
{
    private $groupInstance;
    private $validator;

    public function __construct(){
        $this->groupInstance = new Group();
        $this->validator = new ModelValidator(Group::$rules, Group::$rulesMessages);

        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o GroupController em {$startTime}.");
    }

    /**
     * Gets all groups.
     */
    public function index() {
        Logger::info("Iniciando a busca por grupos.");

        $groups = $this->groupInstance->getAll("description");
        Logger::info("Recuperando um total de " . count($groups) . " grupos.");

        Logger::info("Encerrando a busca por grupos.");
        return ResponseUtils::getResponse($groups, 200);
    }

    public function search(Request $request) {
        Logger::info("Iniciando a busca por grupos.");
        $data = $request->all();

        $groups = $this->groupInstance->getGroupByName($data["description"]);
        Logger::info("Recuperando um total de " . count($groups) . " grupos.");

        Logger::info("Encerrando a busca por grupos.");
        return ResponseUtils::getResponse($groups, 200);
    }

    /**
     * Gets a group by id.
     */
    public function show($id) {
        Logger::info("Iniciando a busca pelo grupo: {$id}.");
        if ($id <= 0) {
            throw new InvalidValueException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "grupo"));
        }
        Logger::info("Recuperando o grupo pelo id {$id}.");
        $group = $this->groupInstance->getGroupById($id);

        if ($group === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }
        Logger::info("Encerrando a busca pelo grupo {$id}.");
        return ResponseUtils::getResponse($group, 200);
    }

    /**
     * Deletes a group.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção do grupo {$id}.");

        if ($id <= 0) {
           throw new InvalidValueException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "grupo"));
        }

        Logger::info("Recuperando o grupo pelo id: {$id}.");
        $group = $this->groupInstance->getById($id);

        if ($group === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Deletando o grupo: {$id}.");
        $group->deleted = true;
        $group->updated_at = date('Y-m-d H:i:s');
        $group->save();

        Logger::info("Encerrando a deleção do grupo: {$id}.");
        return ResponseUtils::getResponse($group, 200);
    }

    /**
     * Updates a group.
     */
    public function update(Request $request, $id){
        Logger::info("Iniciando a alteração do grupo: {$id}.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->validator->validate($data);

        if ($validation !== null) {
           throw new InputValidationException($validation);
        }

        if (!isset($id) || empty($id)) {
            throw new InvalidValueException(sprintf(ErrorMessage::$ID_NOT_PROVIDED, "grupo"));
        }

        Logger::info("Recuperando o grupo: {$id}");
        $groupObj = $this->groupInstance->getById($id);

        if ($groupObj === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Alterando o grupo {$id}.");
        $groupObj->updated_at = date('Y-m-d H:i:s');
        $groupObj->description = $data["description"];
        $groupObj->save();

        Logger::info("Encerrando a alteração do grupo: {$id}");
        return ResponseUtils::getResponse($groupObj, 200);
    }

    /**
     * Creates a group.
     */
    public function store(Request $request){
        Logger::info("Iniciando a criação de um novo grupo.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->validator->validate($data);

        if ($validation !== null){
            throw new InputValidationException($validation);
        }

        Logger::info("Verificando se já existe algum grupo com os dados informados.");
        $existingGroups = $this->groupInstance->getGroupByName($data["description"]);
        if (count($existingGroups) > 0){
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }

        Logger::info("Criando o novo grupo.");
        $groupObj = new Group($data);
        $groupObj->deleted = false;
        $groupObj->save();

        Logger::info("Encerrando a criação do grupo.");
        return ResponseUtils::getResponse($groupObj, 201);
    }

    /**
     * Creates a group.
     */
    public function create(Request $request) {}

}
