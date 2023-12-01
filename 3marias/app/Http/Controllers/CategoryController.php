<?php

namespace App\Http\Controllers;

use App\Exceptions\EntityAlreadyExistsException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InputValidationException;
use App\Models\Category;
use App\Models\City;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\PathUtils;
use App\Utils\ResponseUtils;
use App\Utils\UpdateUtils;
use App\Utils\UploadUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class CategoryController extends Controller implements APIController
{
    private $categoryInstance;
    private $validator;

    public function __construct() {
        $this->categoryInstance = new Category();
        $this->validator = new ModelValidator(Category::$rules, Category::$rulesMessages);

        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o CategoryController em {$startTime}.");
    }

    /**
     * Gets categories by city id.
     */
    public function getByCity(Request $request, $idCity) {
        Logger::info("Buscando categorias por cidade.");

        Logger::info("Verificando se a cidade existe.");
        $city = (new City())->getById($idCity);
        if (is_null($city)){
            throw new EntityNotFoundException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "cidade"));
        }
        $data = $this->categoryInstance->getByCity($idCity);

        Logger::info("Encerrando a busca por categorias cidade.");
        return ResponseUtils::getResponse($data, 200);
    }

    /**
     * Gets all categories.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de categorias.");

        $data = $this->categoryInstance->getAll("name");
        $count = count($data);

        Logger::info("Recuperando $count categorias.");
        return ResponseUtils::getResponse($data, 200);
    }

    /**
     * Creates a new category.
     */
    public function store(Request $request) {
        Logger::info("Criando uma nova categoria.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $validation = $this->validator->validateWithImage($request);

        if (!is_null($validation)){
            throw new InputValidationException($validation);
        }

        // if there is ID field, it must be an update.
        if (isset($data["id"]) && !empty($data["id"])) {
            return $this->update(request: $request, id: $data["id"]);
        }

        Logger::info("Verificando se já existe alguma categoria com os dados informados.");
        $categoriesFound = $this->categoryInstance->getCategoryByName($data["name"]);
        if (count($categoriesFound) > 0){
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }

        Logger::info("Criando a nova categoria.");
        $filename = "category-" . time() . ".png";
        $filePath = PathUtils::getPathByFolder("category") . "/" . $filename;
        UploadUtils::uploadImage(request: $request, folder: "category", filePath: $filePath);

        $category = new Category($data);
        $category->deleted = false;
        $category->image = $filename;
        $category->save();

        Logger::info("Encerrando a criação de categoria.");
        return ResponseUtils::getResponse($category, 201);
    }

    /**
     * Deletes a single category.
     */
    public function destroy($id) {
        Logger::info("Iniciando a deleção da categoria {$id}.");
        Logger::info("Iniciando a validação dos dados informados.");
        $category = $this->validateCategoryId(id: $id);

        Logger::info("Deletando a categoria {$id}.");
        $category->deleted = true;
        $category->save();

        Logger::info("Encerrando a deleção de categoria.");
        return ResponseUtils::getResponse($category, 200);
    }

    /**
     * Updates a single category.
     */
    public function update(Request $request, $id) {
        Logger::info("Editando uma categoria.");

        Logger::info("Iniciando a validação dos dados informados.");
        $data = $request->all();
        $data["image"] = "default.png";
        $validation = $this->validator->validate($data);
        if (!is_null($validation)){
            throw new InputValidationException($validation);
        }

        $imgValidation = $this->validator->validateImage(request: $request, imageIsRequired: false);
        if ($imgValidation !== null) {
            throw new InputValidationException($imgValidation);
        }
        $this->validateCategoryId($id);

        Logger::info("Verificando se já existe alguma categoria com os dados informados.");
        $condition = [["name", "like", "%" . $data["name"] . "%"]];
        $categoriesFound = $this->categoryInstance->existsEntity(condition: $condition, id: $id);
        if ($categoriesFound){
            throw new EntityAlreadyExistsException(ErrorMessage::$ENTITY_EXISTS);
        }

        $category = (new Category())->getById($id);
        $filename = $category->image;
        $imgFile = $request->file("image");
        if (!is_null($imgFile)) {
            $filename = "category-" . time() . ".png";
            $filePath = PathUtils::getPathByFolder("category") . "/" . $filename;
            UploadUtils::uploadImage(request: $request, folder: "category", filePath: $filePath);
        }
        Logger::info("Atualizando a nova categoria.");
        $category->image = $filename;
        $category->name = $data["name"];
        $category->save();
        
        return ResponseUtils::getResponse($category, 200);
    }

    /**
     * Gets a single category.
     */
    public function show($id) {
        Logger::info("Iniciando a busca da categoria {$id}.");
        Logger::info("Iniciando a validação dos dados informados.");
        $category = $this->validateCategoryId(id: $id);

        Logger::info("Encerrando a busca da categoria {$id}.");
        return ResponseUtils::getResponse($category, 200);
    }

    /**
     * Creates a category.
     */
    public function create(Request $request) {
        $this->store(request: $request);
    }

    private function validateCategoryId(int $id) {
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "categoria"));
        }
        $category = (new Category())->getById(id: $id);
        if (is_null($category)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "categoria"));
        }

        return $category;
    }

}
