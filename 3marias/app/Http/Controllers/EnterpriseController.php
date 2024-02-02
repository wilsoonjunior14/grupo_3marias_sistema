<?php

namespace App\Http\Controllers;

use App\Business\EnterpriseBusiness;
use App\Exceptions\MethodNotImplementedYet;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use Exception;

class EnterpriseController extends Controller implements APIController
{
    private $enterpriseBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterpriseBusiness = new EnterpriseBusiness();
        Logger::info("Iniciando o EnterpriseController em {$startTime}.");
    }

    /**
     * Gets all enterprises.
     */
    public function index() {
        throw new MethodNotImplementedYet("Route Not Implemented.");
    }

    /**
     * Creates a enterprise.
     */
    public function store(Request $request) {
        $enterprise = $this->enterpriseBusiness->create(request: $request);
        return ResponseUtils::getResponse($enterprise, 201);
    }

    /**
     * Gets a enterprise by id.
     */
    public function show($id) {
        $enterprise = $this->enterpriseBusiness->getById(id: $id);
        return ResponseUtils::getResponse($enterprise, 200);
    }

    /**
     * Deletes a enterprise by id.
     */
    public function destroy($id) {
        throw new MethodNotImplementedYet("Route not Implemented.");
    }

    /**
     * Updates a enterprise.
     */
    public function update(Request $request, $id) {
        $enterpriseUpdated = $this->enterpriseBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($enterpriseUpdated, 200);
    }

    /**
     * Creates a enterprise.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
