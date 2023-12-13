<?php

namespace App\Http\Controllers;

use App\Business\ProductBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ProductController extends Controller implements APIController
{
    private $productBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->productBusiness = new ProductBusiness();
        Logger::info("Iniciando o ProductController em {$startTime}.");
    }

    /**
     * Gets all products.
     */
    public function index() {
        $products = $this->productBusiness->get();
        return ResponseUtils::getResponse($products, 200);
    }

    /**
     * Creates a product.
     */
    public function store(Request $request) {
        $product = $this->productBusiness->create(request: $request);
        return ResponseUtils::getResponse($product, 201);
    }

    /**
     * Gets a product by id.
     */
    public function show($id) {
        $product = $this->productBusiness->getById(id: $id);
        return ResponseUtils::getResponse($product, 200);
    }

    /**
     * Deletes a product by id.
     */
    public function destroy($id) {
        $product = $this->productBusiness->delete(id: $id);
        return ResponseUtils::getResponse($product, 200);
    }

    /**
     * Updates a product.
     */
    public function update(Request $request, $id) {
        $product = $this->productBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($product, 200);
    }

    /**
     * Creates a product.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
