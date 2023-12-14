<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\Product;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ProductBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de produtos.");
        $products = (new Product())->getAll("product");
        $amount = count($products);
        Logger::info("Foram recuperados {$amount} produtos.");
        Logger::info("Finalizando a recuperação de produtos.");
        return $products;
    }

    public function getById(int $id, bool $merge = false) {
        Logger::info("Iniciando a recuperação de produto $id.");
        $product = (new Product())->getById($id);
        Logger::info("Finalizando a recuperação de produto $id.");
        return $product;
    }

    public function delete(int $id) {
        $product = $this->getById(id: $id);
        Logger::info("Deletando o de product $id.");
        $product->deleted = true;
        $product->save();
        return $product;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de produto.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $category = (new CategoryProductBusiness())->getByName(name: $data["category_product_name"]);
        $data["category_product_id"] = $category->id;
        $productValidator = new ModelValidator(Product::$rules, Product::$rulesMessages);
        $productValidator->validate(data: $data);
        $this->existsEntity(name: $data["product"]);
        (new CategoryProductBusiness())->getById(id: $data["category_product_id"]);
        
        Logger::info("Salvando a nova produto.");
        $product = new Product($data);
        $product->save();
        Logger::info("Finalizando a atualização de produto.");
        return $product;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do produto.");
        $product = (new Product())->getById($id);
        $productUpdated = UpdateUtils::processFieldsToBeUpdated($product, $request->all(), Product::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do produto.");
        $category = (new CategoryProductBusiness())->getByName(name: $data["category_product_name"]);
        $data["category_product_id"] = $category->id;
        $productValidator = new ModelValidator(Product::$rules, Product::$rulesMessages);
        $productValidator->validate(data: $request->all());
        $this->existsEntity(name: $productUpdated["product"], id: $id);
        (new CategoryProductBusiness())->getById(id: $productUpdated["category_product_id"]);

        Logger::info("Atualizando as informações do produto.");
        $productUpdated->save();
        return $this->getById(id: $productUpdated->id);
    }

    private function existsEntity(string $name, int $id = null) {
        $condition = [["product", "like", "%" . $name . "%"]];
        $exists = (new Product())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Produto", "Produtos"));
        }
        return $exists;
    }

}