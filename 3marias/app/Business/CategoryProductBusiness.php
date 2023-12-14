<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\CategoryProduct;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class CategoryProductBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de categorias de produtos.");
        $categories = (new CategoryProduct())->getAll("name");
        $amount = count($categories);
        Logger::info("Foram recuperados {$amount} categorias de produtos.");
        Logger::info("Finalizando a recuperação de categorias de produtos.");
        return $categories;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de categorye $id.");
        $category = (new CategoryProduct())->getById($id);
        if (is_null($category)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Categoria de Produto"));
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
        Logger::info("Iniciando a criação de categoria de produto.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        if (isset($data["category_products_father_id"]) && !empty($data["category_products_father_id"])) {
            $category = $this->getByName(name: $data["category_products_father_id"]);
            $data["category_products_father_id"] = $category->id;
        }
        $categoryValidator = new ModelValidator(CategoryProduct::$rules, CategoryProduct::$rulesMessages);
        $categoryValidator->validate(data: $data);
        $this->existsEntity(name: $data["name"]);
        
        Logger::info("Salvando a nova categoria de produto.");
        $category = new CategoryProduct($data);
        $category->save();
        Logger::info("Finalizando a atualização de categoria de produto.");
        return $category;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do categoria de produto.");
        $category = (new CategoryProduct())->getById($id);
        $categoryUpdated = UpdateUtils::processFieldsToBeUpdated($category, $request->all(), CategoryProduct::$fieldsToBeUpdated);
        $data = $request->all();

        Logger::info("Validando as informações do categoria de produto.");
        if (isset($data["category_products_father_id"]) && !empty($data["category_products_father_id"])) {
            $category = $this->getByName(name: $data["category_products_father_id"]);
            $data["category_products_father_id"] = $category->id;
        }
        $categoryValidator = new ModelValidator(CategoryProduct::$rules, CategoryProduct::$rulesMessages);
        $categoryValidator->validate(data: $data);
        $this->existsEntity(name: $categoryUpdated["name"], id: $id);
        $categoryUpdated["category_products_father_id"] = $data["category_products_father_id"];

        Logger::info("Atualizando as informações do categoria de produto.");
        $categoryUpdated->save();
        return $this->getById(id: $categoryUpdated->id);
    }

    private function existsEntity(string $name, int $id = null) {
        $condition = [["name", "=", $name]];
        $exists = (new CategoryProduct())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Categoria", "Categorias de Produtos"));
        }
        return $exists;
    }

    public function getByName(string $name) {
        $category = (new CategoryProduct())->getByName(name: $name);
        if (is_null($category)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Categoria Associada"));
        }
        return $category;
    }

}