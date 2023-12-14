<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\CategoryService;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class CategoryServiceBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de categorias de serviços.");
        $categories = (new CategoryService())->getAll("name");
        $amount = count($categories);
        Logger::info("Foram recuperados {$amount} categorias de serviços.");
        Logger::info("Finalizando a recuperação de categorias de serviços.");
        return $categories;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de categoria $id.");
        $category = (new CategoryService())->getById($id);
        if (is_null($category)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Categoria de Serviço"));
        }
        Logger::info("Finalizando a recuperação de categorye $id.");
        return $category;
    }

    public function delete(int $id) {
        $category = $this->getById(id: $id);
        Logger::info("Deletando o de categorye $id.");
        $category->deleted = true;
        $category->save();
        return $category;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de categoria de serviço.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $categoryValidator = new ModelValidator(CategoryService::$rules, CategoryService::$rulesMessages);
        $categoryValidator->validate(data: $data);
        $this->existsEntity(name: $data["name"]);
        
        Logger::info("Salvando a nova categoria de serviço.");
        $category = new CategoryService($data);
        $category->save();
        Logger::info("Finalizando a atualização de categoria de serviço.");
        return $category;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do categoria de serviço.");
        $category = (new CategoryService())->getById($id);
        $categoryUpdated = UpdateUtils::processFieldsToBeUpdated($category, $request->all(), CategoryService::$fieldsToBeUpdated);
        $data = $request->all();

        Logger::info("Validando as informações do categoria de serviço.");
        $categoryValidator = new ModelValidator(CategoryService::$rules, CategoryService::$rulesMessages);
        $categoryValidator->validate(data: $data);
        $this->existsEntity(name: $categoryUpdated["name"], id: $id);

        Logger::info("Atualizando as informações do categoria de serviço.");
        $categoryUpdated->save();
        return $this->getById(id: $categoryUpdated->id);
    }

    private function existsEntity(string $name, int $id = null) {
        $condition = [["name", "=", $name]];
        $exists = (new CategoryService())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Categoria", "Categorias de serviços"));
        }
        return $exists;
    }

    public function getByName(string $name) {
        $category = (new CategoryService())->getByName(name: $name);
        if (is_null($category)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria do Serviço"));
        }
        return $category;
    }

}