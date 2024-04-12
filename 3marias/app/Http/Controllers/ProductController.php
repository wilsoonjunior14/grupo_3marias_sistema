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
        try {
            $products = $this->productBusiness->get();
            return ResponseUtils::getResponse($products, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a product.
     */
    public function store(Request $request) {
        try {
            $product = $this->productBusiness->create(request: $request);
            return ResponseUtils::getResponse($product, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a product by id.
     */
    public function show($id) {
        try {
            $product = $this->productBusiness->getById(id: $id);
            return ResponseUtils::getResponse($product, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a product by id.
     */
    public function destroy($id) {
        try {
            $product = $this->productBusiness->delete(id: $id);
            return ResponseUtils::getResponse($product, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a product.
     */
    public function update(Request $request, $id) {
        try {
            $product = $this->productBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($product, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a product.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
