<?php

namespace App\Http\Controllers;

use App\Business\UserBusiness;
use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InputValidationException;
use App\Exceptions\InvalidValueException;
use App\Models\Address;
use App\Models\Category;
use App\Models\City;
use App\Models\Enterprise;
use App\Models\LinkedSystem;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\PathUtils;
use App\Utils\ResponseUtils;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class EnterpriseController extends Controller implements APIController
{
    private $enterpriseInstance;
    private $linkedSystemInstance;
    private $addressValidator;
    private $enterpriseValidator;
    private $linkedSystemValidator;

    public function __construct() {
        $this->enterpriseInstance = new Enterprise();
        $this->linkedSystemInstance = new LinkedSystem();
        $this->addressValidator = new ModelValidator(Address::$rules, Address::$rulesMessages);
        $this->enterpriseValidator = new ModelValidator(Enterprise::$rules, Enterprise::$rulesMessages);
        $this->linkedSystemValidator = new ModelValidator(LinkedSystem::$rules, LinkedSystem::$rulesMessages);

        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o EnterpriseController em {$startTime}.");
    }

    /**
     * Gets all enterprises.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de empresas.");

        $enterprises = $this->enterpriseInstance->getAll();
        $count = count($enterprises);

        Logger::info("Recuperando {$count} empresas.");
        return ResponseUtils::getResponse($enterprises, 200);
    }

    /**
     * Gets an enterprise.
     */
    public function show($id) {
        Logger::info("Iniciando a recuperação da empresa por id {$id}.");

        $enterprises = $this->enterpriseInstance->getById($id);
        if ($enterprises === null) {
            throw new InputValidationException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Empresa {$id} encontrada com sucesso.");
        return ResponseUtils::getResponse($enterprises, 200);
    }

    /**
     * Searches an enterprise.
     */
    public function search(Request $request) {
        Logger::info("Iniciando a busca por empresas.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        if (!isset($data["city_id"]) || empty($data["city_id"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "cidade"));
        }

        if (!isset($data["category_id"]) || empty($data["category_id"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria"));
        }

        Logger::info("Efetuando a busca por empresas.");
        $searchString = isset($data["search"]) ? $data["search"] : "";
        $data = $this->enterpriseInstance
            ->getEnterprisesByCityAndCategory($data["city_id"], $data["category_id"], $searchString);

        return ResponseUtils::getResponse($data, 200);
    }

    /**
     * Creates an enterprise.
     */
    public function store(Request $request) {
        Logger::info("Iniciando a criação de empresa.");
        $data = $request->all();
        $this->validateEnterpriseData($data);

        Logger::info("Criando a nova empresa.");
        $enterprise = new Enterprise($data);
        $enterprise->image = "default.png";
        if (!empty($_FILES) && isset($_FILES) 
            && isset($_FILES["image"]) && !empty($_FILES["image"])
            && !empty($_FILES["image"]["name"])
            ) {
            
                $filename = "enterprise-" . time() . ".png";
                $filePath = PathUtils::getPathByFolder("enterprise") . "/" . $filename;
                move_uploaded_file($_FILES["image"]["tmp_name"], $filePath);
                $enterprise->image = $filename;
        }
        $enterprise->save();

        $address = new Address($data);
        $address->enterprise_id = $enterprise->id;
        $address->save();

        if (isset($data["linkedSystems"]) && !empty($data["linkedSystems"])) {
            $linkedSystems = $data["linkedSystems"];
            foreach ($linkedSystems as $item) {
                $linkedSystem = new LinkedSystem($item);
                $linkedSystem->enterprise_id = $enterprise->id;
                $linkedSystem->save();
            }
        }

        Logger::info("Criando um usuário para a empresa.");
        $user = [
            "name" => $enterprise->name,
            "email" => $enterprise->email,
            "phoneNumber" => $enterprise->phone,
            "group_id" => 3,
            "password" => $data["password"],
            "active" => false
        ];
        (new UserBusiness())->create(data: $user);


        Logger::info("Encerrando a criação da nova empresa.");
        return ResponseUtils::getResponse($enterprise, 201);
    }

    /**
     * Updates an enterprise.
     */
    public function update(Request $request, $id) {
        Logger::info("Iniciando a atualização de empresa.");
        $data = $request->all();
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "empresa"));
        }

        if (!isset($data["address_id"]) || empty($data["address_id"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_PROVIDED, "endereço"));
        }
        if ($data["address_id"] <= 0){
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "endereço"));
        }
        $this->validateEnterpriseData(data: $request->all(), update: true);

        Logger::info("Recuperando a empresa {$id}.");
        $enterprise = (new Enterprise())->getById($id);
        if ($enterprise === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        Logger::info("Recuperando o endereço {$data['address_id']}.");
        $address = (new Address())->getById($data["address_id"]);
        if ($address === null) {
            throw new EntityNotFoundException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "endereço"));
        }

        Logger::info("Verificando se já existe alguma empresa com os dados informados.");
        $enterprisesFound = $this->enterpriseInstance->getByNameOrEmail(
            name: $data["name"], email: $data["email"], enterpriseId: $id
        );
        if (count($enterprisesFound) > 0){
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }

        Logger::info("Alterando os dados da empresa: " . $id . " .");
        $enterpriseUpdated = UpdateUtils::
        processFieldsToBeUpdated($enterprise, $data, Enterprise::$fieldsToBeUpdated);
        $enterpriseUpdated->save();
        
        Logger::info("Alterando os dados do endereço: " . $data['address_id'] . " .");
        $addressUpdated = UpdateUtils::
        processFieldsToBeUpdated($address, $data, Address::$fieldsToBeUpdated);
        $addressUpdated->save();

        if (isset($data["linkedSystems"]) && !empty($data["linkedSystems"])) {
            Logger::info("Removendo todas as redes sociais da empresa.");
            $lsInstance = new LinkedSystem();
            $lsInstance->deleteAllByEnterprise(enterpriseId: $id);

            Logger::info("Adicionando redes sociais na empresa.");
            $lsInstance->saveLinkedSystems(linkedSystems: $data["linkedSystems"], enterpriseId: $id);
        }

        $enterprise = (new Enterprise())->getById($id);
        Logger::info("Concluindo alteração da empresa {$id}.");
        return ResponseUtils::getResponse($enterprise, 200);
    }

    /**
     * Validates the enterprise data.
     */
    private function validateEnterpriseData($data, bool $update = false) {
        Logger::info("Iniciando a validação dos dados informados.");
        $validation = $this->enterpriseValidator->validateEnterprise($data, isUpdate: $update);
        if ($validation !== null){
          throw new InputValidationException($validation);
        }

        $addressValidation = $this->addressValidator->validate($data);
        if ($addressValidation !== null){
          throw new InputValidationException($addressValidation);
        }

        if (isset($data["linkedSystems"]) && !empty($data["linkedSystems"])) {
            $linkedSystems = $data["linkedSystems"];
            foreach ($linkedSystems as $linkedSystem) {
                $linkedSystemValidation = $this->linkedSystemValidator->validate($linkedSystem);
                if ($linkedSystemValidation !== null) {
                    throw new InputValidationException($linkedSystemValidation);
                }
            }
        }

        Logger::info("Verificando se a categoria existe.");
        $category = (new Category())->getById($data["category_id"]);
        if ($category === null){
            throw new EntityNotFoundException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria"));
        }

        Logger::info("Verificando se a cidade existe.");
        $city = (new City())->getById($data["city_id"]);
        if ($city === null){
            throw new EntityNotFoundException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "cidade"));
        }
    }

    /**
     * Deletes an enterprise.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção da empresa: " . $id . " .");

        if ($id <= 0) {
            throw new InvalidValueException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "empresa"));
        }

        Logger::info("Recuperando a empresa: " . $id . " .");
        $enterprise = $this->enterpriseInstance->getById($id);

        if ($enterprise === null) {
            throw new EntityNotFoundException(ErrorMessage::$ENTITY_NOT_FOUND);
        }

        $enterprise->deleted = true;
        Logger::info("Salvando alterações na empresa: " . $id . " .");
        $enterprise->save();

        // TODO: delete logically the users associated.

        Logger::info("Encerrando a deleção da empresa: " . $id . " .");
        return ResponseUtils::getResponse($enterprise, 200);
    }

    /**
     * Creates a enterprise.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
