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
        $categories = $this->categoryProductBusiness->get();
        return ResponseUtils::getResponse($categories, 200);
    }

    /**
     * Creates a category.
     */
    public function store(Request $request) {
        $category = $this->categoryProductBusiness->create(request: $request);
        return ResponseUtils::getResponse($category, 201);
    }

    /**
     * Gets a category by id.
     */
    public function show($id) {
        $category = $this->categoryProductBusiness->getById(id: $id);
        return ResponseUtils::getResponse($category, 200);
    }

    /**
     * Deletes a category by id.
     */
    public function destroy($id) {
        $category = $this->categoryProductBusiness->delete(id: $id);
        return ResponseUtils::getResponse($category, 200);
    }

    /**
     * Updates a category.
     */
    public function update(Request $request, $id) {
        $categoryUpdated = $this->categoryProductBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($categoryUpdated, 200);
    }

    /**
     * Creates a category.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
