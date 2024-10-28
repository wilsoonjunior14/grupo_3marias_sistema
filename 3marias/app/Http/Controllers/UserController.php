<?php

namespace App\Http\Controllers;

use App\Business\UserBusiness;
use Illuminate\Routing\Controller as BaseController;
use App\Exceptions\InputValidationException;
use App\Exceptions\InvalidValueException;
use App\Exceptions\EntityNotFoundException;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GroupRole;
use App\Models\Logger;
use App\Utils\EmailUtils;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;

class UserController extends BaseController implements APIController
{

    private $userInstance = null;
    private $groupRoleInstance = null;

    public function __construct() {
        $this->userInstance = new User();
        $this->groupRoleInstance = new GroupRole();
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o UserController em {$startTime}.");
    }

    /**
     * Searches users by name or email
     */
    public function search(Request $request) {
       Logger::info("Iniciando a busca por usuários.");
       $data = $request->all();

       $users = $this->userInstance->search($data);
       $amountUsers = count($users);

       Logger::info("Recuperando " . $amountUsers . " usuários.");
       Logger::info("Encerrando a busca por usuários.");
       return ResponseUtils::getResponse($users, 200);
    }

    /**
     * Gets all users.
     */
    public function index() {
        Logger::info("Iniciando a busca de usuários.");

        $users = $this->userInstance->getUsers();

        Logger::info("Recuperando um total de " . count($users) . " usuários.");
        Logger::info("Encerrando a busca de usuários.");

        return ResponseUtils::getResponse($users, 200);
    }

    /**
     * Deletes an user.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção do usuário: " . $id . " .");
        try {
            $userObj = $this->userInstance->getUserById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "usuário"));
        }

        $userObj->deleted = true;
        Logger::info("Salvando alterações no usuário: " . $id . " .");
        $userObj->save();
        Logger::info("Encerrando a deleção do usuário: " . $id . " .");
        return ResponseUtils::getResponse($userObj, 200);
    }

    /**
     * Gets an user by id.
     */
    public function show($id) {
        Logger::info("Iniciando a busca pelo usuário: " . $id . " .");
        try {
            $user = $this->userInstance->getUserById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "usuário"));
        }
        Logger::info("Encerrando a busca pelo usuário: " . $id . " .");
        return ResponseUtils::getResponse($user, 200);
    }

    /**
     * Creates an user.
     */
    public function create(Request $request){
       Logger::info("Iniciando a criação do usuário.");

       Logger::info("Iniciando a validação dos dados informados.");
       $data = $request->all();
       if (!isset($data["id"])) {
        $user = (new UserBusiness())->create(data: $data);
       } else {
        return $this->update(request: $request, id: $data["id"]);
       }

       Logger::info("Encerrando a criação do usuário.");
       return ResponseUtils::getResponse($user, 201);
    }

    /**
     * Updates an user.
     */
    public function update(Request $request, $id) {
        Logger::info("Iniciando a alteração do usuário.");

        Logger::info("Iniciando a validação dos dados informados.");
        try {
            $userObj = $this->userInstance->getUserById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "usuário"));
        }

        $data = $request->all();
        $validation = User::validateUserData($data);

        if ($validation !== null){
           throw new InputValidationException($validation);
        }

        Logger::info("Alterando o usuário: " . $id . " .");
        $userObj = UpdateUtils::processFieldsToBeUpdated($userObj, $data, User::$fieldsToBeUpdated);
        $userObj->save();

        Logger::info("Encerrando a alteração do usuário.");
        return ResponseUtils::getResponse($userObj, 200);
    }

    public function login(Request $request) {
        Logger::info("Iniciando o login do usuário.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        if (!isset($data["email"]) || empty($data["email"]) || !isset($data["password"]) || empty($data["password"])) {
            throw new InvalidValueException(config("messages.input_validation.email_password_invalid"));
        }

        Logger::info("Recuperação de usuários pelo email informado.");
        $users = $this->userInstance->getUserLogin($data["email"]);

        if (count($users) == 0){
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Capturando o primeiro usuário encontrado.");
        $user = $users[0];

        if (!$user->active) { 
            throw new InvalidValueException(config("messages.general.inactive_user"));
        }

        if (strcmp($user->password, $data["password"]) !== 0) {
            throw new InvalidValueException(config("messages.input_validation.password_invalid"));
        }

        Logger::info("Criando novo token para o usuário.");
        $token = $user->createToken('auth_token')->plainTextToken;

        Logger::info("Capturando as permissões do usuário.");
        $response = [
           "access_token" => $token,
           "type" => "Bearer",
           "user" => $user
        ];

        Logger::info("Encerrando o login do usuário.");
        return ResponseUtils::getResponse($response, 200);
    }

    public function isAuthorized(Request $request) {
        return ResponseUtils::getResponse($request->user(), 200);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return ResponseUtils::getResponse(["status" => true], 200);
    }

    /**
     * Creates a user.
     */
    public function store(Request $request) {}

    /**
     * Recovery the user password by email provided.
     */
    public function recoveryPassword(Request $request) {
        Logger::info("Iniciando a recuperação de senha.");
        Logger::info("Validando dados informados.");
        $data = $request->all();
        $rules = [
            'email' => 'required|email:strict'
        ];
    
        $rulesMessages = [
             'email.required' => 'Campo email é obrigatório.',
             'email.email' => 'Campo email está inválido.'
        ];

        $validator = new ModelValidator($rules, $rulesMessages);
        $validationResults = $validator->validate($data);
        if ($validationResults !== null) {
            throw new InputValidationException($validationResults);
        }

        Logger::info("Recuperando o usuário pelo email.");
        $user = (new User())->getUserByEmail($data["email"]);

        if ($user === null || count($user) === 0) {
            throw new InputValidationException("Email não existe no sistema.");
        }
        $user = $user[0];
        
        Logger::info("Criando credenciais para recuperação de senha.");
        $currentTime = date("Y-m-d H:i:s");
        $futureTime = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($currentTime)));
        $payload = [
            "id" => $user["id"],
            "email" => $user["email"],
            "expiration" => $futureTime,
            "code" => time()
        ];
        $token = base64_encode(json_encode($payload));

        Logger::info("Salvando entidade usuário com informações de recuperação de senha.");
        $user->recovery_password_token = $token;
        $user->recovery_password_expiration = $futureTime;
        $user->save();

        Logger::info("Enviando um email para recuperação da senha.");
        EmailUtils::sendRecoveryPasswordEmail(user: $user);

        Logger::info("Finalizando a recuperação de senha.");
        $payload = [
            "message" => "Ótimo! Enviamos mais informações para seu email para recuperação da senha."
        ];
        return ResponseUtils::getResponse($payload, 200);
    }

    /**
     * Changes a user password.
     */
    public function resetPasswordByToken(Request $request) {
        Logger::info("Iniciando a alteração da senha.");

        Logger::info("Validando os dados informados.");
        $data = $request->all();
        $rules = [
            'id' => 'required',
            'password' => 'required|min:3'
        ];
    
        $rulesMessages = [
            'id.required' => 'Campo id do usuário é obrigatório.',
            'password.required' => 'Campo senha é obrigatório.',
            'password.min' => 'Campo senha deve conter no mínimo 3 caracteres.',
        ];

        $validator = new ModelValidator($rules, $rulesMessages);
        $validationResults = $validator->validate($data);
        if ($validationResults !== null) {
            throw new InputValidationException($validationResults);
        }

        try {
            $user = $this->userInstance->getUserById($data["id"]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "usuário"));
        }

        Logger::info("Validando o token do usuário.");
        $currentTimestamp = strtotime(date('Y-m-d H:i:s'));
        $tokenTimestamp = strtotime($user["recovery_password_expiration"]);

        if ($currentTimestamp > $tokenTimestamp) {
            throw new InputValidationException("Infelizmente o tempo para resetar a senha expirou. Solicite a recuperação de senha e tente novamente.");
        }

        Logger::info("Alterando a senha do usuário {$user['id']}.");
        $user->recovery_password_expiration = null;
        $user->recovery_password_token = null;
        $user->password = $data["password"];
        $user->save();

        $payload = [
            "message" => "Senha alterada com sucesso!"
        ];
        Logger::info("Finalizando a alteração de senha.");
        return ResponseUtils::getResponse($payload, 200);
    }
}
