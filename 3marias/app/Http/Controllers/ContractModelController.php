<?php

namespace App\Http\Controllers;

use App\Business\ContractModelBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ContractModelController extends Controller implements APIController
{
    private $contractModelBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->contractModelBusiness = new ContractModelBusiness();
        Logger::info("Iniciando o ContractModelController em {$startTime}.");
    }

    /**
     * Gets all models.
     */
    public function index() {
        $models = $this->contractModelBusiness->get();
        return ResponseUtils::getResponse($models, 200);
    }

    /**
     * Creates a model.
     */
    public function store(Request $request) {
        $model = $this->contractModelBusiness->create(request: $request);
        return ResponseUtils::getResponse($model, 201);
    }

    /**
     * Gets a model by id.
     */
    public function show($id) {
        $model = $this->contractModelBusiness->getById(id: $id);
        return ResponseUtils::getResponse($model, 200);
    }

    /**
     * Deletes a model by id.
     */
    public function destroy($id) {
        $model = $this->contractModelBusiness->delete(id: $id);
        return ResponseUtils::getResponse($model, 200);
    }

    /**
     * Updates a model.
     */
    public function update(Request $request, $id) {
        $modelUpdated = $this->contractModelBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($modelUpdated, 200);
    }

    /**
     * Creates a model.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
