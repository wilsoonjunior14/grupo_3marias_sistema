<?php

namespace App\Http\Controllers;

use App\Business\GroupBusiness;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\GroupRole;
use App\Models\Logger;
use App\Exceptions\InputValidationException;
use App\Exceptions\EntityNotFoundException;
use App\Models\Group;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use App\Validation\ModelValidator;

class RoleController extends Controller implements APIController
{
    private $roleInstance;
    private $groupRoleInstance;
    private $roleValidator;
    private $groupRoleValidator;
    private $groupBusiness;

    public function __construct(){
        $this->roleInstance = new Role();
        $this->groupRoleInstance = new GroupRole();
        $this->roleValidator = new ModelValidator(Role::$rules, Role::$rulesMessages);
        $this->groupRoleValidator = new ModelValidator(GroupRole::$rules, GroupRole::$rulesMessages);
        $this->groupBusiness = new GroupBusiness();

        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o RoleController em {$startTime}.");
    }

    /**
     * Searches roles by request data.
     */
    public function search(Request $request) {
        Logger::info("Iniciando a busca por permissões.");

        $roles = $this->roleInstance->search($request->all());
        $amountRoles = count($roles);

        Logger::info("Recuperando um total de " . $amountRoles . " permissões.");
        Logger::info("Encerrando a busca por permissões.");
        return ResponseUtils::getResponse($roles, 200);
    }

    /**
     * Gets the all roles.
     */
    public function index(){
        Logger::info("Iniciando a busca por permissões.");

        $roles = $this->roleInstance->getAll("description");
        $amountRoles = count($roles);

        Logger::info("Recuperando um total de " . $amountRoles . " permissões.");
        Logger::info("Encerrando a busca por permissões.");
        return ResponseUtils::getResponse($roles, 200);
    }

    /**
     * Gets the specific role.
     */
    public function show($id){
        Logger::info("Iniciando a busca pela permissão: {$id}.");

        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "permissão"));
        }

        Logger::info("Recuperando a permissão: {$id}.");
        $role = $this->roleInstance->getById($id);

        if ($role === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Encerrando a busca pela permissão: {$id}.");
        return ResponseUtils::getResponse($role, 200);
    }

    /**
     * Deletes a role.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção da permissão: {$id}.");

        if ($id <= 0) {
           throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "permissão"));
        }

        Logger::info("Recuperando a permissão: {$id}.");
        $role = $this->roleInstance->getById($id);

        if ($role === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Alterando a permissão: {$id}.");
        $role->updated_at = date('Y-m-d H:i:s');
        $role->deleted = true;
        $role->save();

        Logger::info("Encerrando a deleção da permissão: {$id}.");
        return ResponseUtils::getResponse($role, 200);
    }

    /**
     * Creates a role.
     */
    public function store(Request $request) {
        Logger::info("Iniciando a criação da permissão.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->roleValidator->validate($data);

        if ($validation !== null){
            throw new InputValidationException($validation);
        }

        Logger::info("Criando a nova permissão.");
        $roleObj = new Role($data);
        $roleObj->created_at = date('Y-m-d H:i:s');
        $roleObj->updated_at = date('Y-m-d H:i:s');
        $roleObj->deleted = false;
        $roleObj->save();

        Logger::info("Buscando o grupo majoritário.");
        $groups = (new Group())->getGroupByName("Desenvolvedor");
        if (count($groups) > 0) {
            Logger::info("Adicionando a permissão ao grupo majoritário.");
            $groupRole = new GroupRole();
            $groupRole->group_id = $groups[0]->id;
            $groupRole->role_id = $roleObj->id;
            $groupRole->save();
        }

        Logger::info("Encerrando a criação da nova permissão.");
        return ResponseUtils::getResponse($roleObj, 201);
    }

    /**
     * Updates a role.
     */
    public function update(Request $request, $id){
        Logger::info("Iniciando a alteração da permissão: {$id}.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->roleValidator->validate($data);

        if ($validation !== null){
            throw new InputValidationException($validation);
        }

        if (!isset($id) || empty($id)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_PROVIDED, "permissão"));
        }

        Logger::info("Recuperando a permissão: {$id}.");
        $roleObj = $this->roleInstance->getById($id);

        if ($roleObj === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Alterando a permissão: {$id}.");
        $roleObj->updated_at = date('Y-m-d H:i:s');
        $roleObj->description = $data["description"];
        $roleObj->endpoint = $data["endpoint"];
        $roleObj->request_type = $data["request_type"];
        $roleObj->save();

        Logger::info("Encerrando a alteração da permissão.");
        return ResponseUtils::getResponse($roleObj, 200);
    }

    public function addRoleToGroup(Request $request) {
        Logger::info("Iniciando a criação de uma nova permissão para um grupo.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->groupRoleValidator->validateGroupRole($data);

        if ($validation !== null){
           throw new InputValidationException($validation);
        }
        $this->groupBusiness->checkRoleExists($data);

        Logger::info("Criando uma nova permissão para o grupo.");
        $groupRoleObj = new GroupRole($data);
        $groupRoleObj->save();

        Logger::info("Encerrando a criação de uma nova permissão para um grupo.");
        return ResponseUtils::getResponse($groupRoleObj, 201);
    }

    public function removeRoleToGroup($id) {
        Logger::info("Iniciando a deleção de permissão de um grupo.");

        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "permissão/grupo"));
        }

        Logger::info("Recuperando o grupo de permissões: {$id}.");
        $groupRoleObj = $this->groupRoleInstance->getById($id);

        if ($groupRoleObj === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Deletando a permissão do grupo.");
        $groupRoleObj->deleted = true;
        $groupRoleObj->save();

        Logger::info("Encerrando a deleção de permissão do grupo.");
        return ResponseUtils::getResponse($groupRoleObj, 200);
    }

    /**
     * Creates a role.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
