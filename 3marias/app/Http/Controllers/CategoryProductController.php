<?php

namespace App\Http\Controllers;

use App\Business\CategoryProductBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class CategoryProductController extends Controller implements APIController
{
    private $categoryProductBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->categoryProductBusiness = new CategoryProductBusiness();
        Logger::info("Iniciando o CategoryProductController em {$startTime}.");
    }

    /**
     * Gets all contracts.
     */
    public function index() {
        try {
            $categories = $this->categoryProductBusiness->get();
            return ResponseUtils::getResponse($categories, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a category.
     */
    public function store(Request $request) {
        try {
            $category = $this->categoryProductBusiness->create(request: $request);
            return ResponseUtils::getResponse($category, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a category by id.
     */
    public function show($id) {
        try {
            $category = $this->categoryProductBusiness->getById(id: $id);
            return ResponseUtils::getResponse($category, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a category by id.
     */
    public function destroy($id) {
        try {
            $category = $this->categoryProductBusiness->delete(id: $id);
            return ResponseUtils::getResponse($category, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a category.
     */
    public function update(Request $request, $id) {
        try {
            $categoryUpdated = $this->categoryProductBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($categoryUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a category.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
