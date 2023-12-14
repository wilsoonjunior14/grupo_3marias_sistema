<?php

namespace App\Http\Controllers;

use App\Business\EnterpriseOwnerBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class EnterpriseOwnerController extends Controller implements APIController
{
    private $enterpriseOwnerBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterpriseOwnerBusiness = new EnterpriseOwnerBusiness();
        Logger::info("Iniciando o EnterpriseOwnerController em {$startTime}.");
    }

    /**
     * Gets all EnterpriseOwners.
     */
    public function index() {
        $enterpriseOwners = $this->enterpriseOwnerBusiness->get(enterpriseId: 1);
        return ResponseUtils::getResponse($enterpriseOwners, 200);
    }

    /**
     * Creates a EnterpriseOwner.
     */
    public function store(Request $request) {
        $entepriseOwner = $this->enterpriseOwnerBusiness->create(request: $request);
        return ResponseUtils::getResponse($entepriseOwner, 201);
    }

    /**
     * Gets a EnterpriseOwner by id.
     */
    public function show($id) {
        $entepriseOwner = $this->enterpriseOwnerBusiness->getById(id: $id);
        return ResponseUtils::getResponse($entepriseOwner, 200);
    }

    /**
     * Deletes a EnterpriseOwner by id.
     */
    public function destroy($id) {
        $entepriseOwner = $this->enterpriseOwnerBusiness->delete(id: $id);
        return ResponseUtils::getResponse($entepriseOwner, 200);
    }

    /**
     * Updates a EnterpriseOwner.
     */
    public function update(Request $request, $id) {
        $entepriseOwnerUpdated = $this->enterpriseOwnerBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($entepriseOwnerUpdated, 200);
    }

    /**
     * Creates a EnterpriseOwner.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
