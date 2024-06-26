<?php

namespace App\Http\Controllers;

use App\Business\CategoryServiceBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class CategoryServiceController extends Controller implements APIController
{
    private $categoryServiceBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->categoryServiceBusiness = new CategoryServiceBusiness();
        Logger::info("Iniciando o CategoryServiceController em {$startTime}.");
    }

    /**
     * Gets all categories.
     */
    public function index() {
        try {
            $categories = $this->categoryServiceBusiness->get();
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
            $category = $this->categoryServiceBusiness->create(request: $request);
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
            $category = $this->categoryServiceBusiness->getById(id: $id);
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
            $category = $this->categoryServiceBusiness->delete(id: $id);
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
            $categoryUpdated = $this->categoryServiceBusiness->update(id: $id, request: $request);
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
