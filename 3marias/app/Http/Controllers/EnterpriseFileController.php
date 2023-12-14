<?php

namespace App\Http\Controllers;

use App\Business\EnterpriseFileBusiness;
use App\Exceptions\MethodNotImplementedYet;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class EnterpriseFileController extends Controller implements APIController
{
    private $enterpriseFileBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterpriseFileBusiness = new EnterpriseFileBusiness();
        Logger::info("Iniciando o EnterpriseFileController em {$startTime}.");
    }

    /**
     * Gets all EnterpriseFiles.
     */
    public function index() {
        throw new MethodNotImplementedYet("Route not implemented.");
    }

    /**
     * Creates a EnterpriseFile.
     */
    public function store(Request $request) {
        $entepriseFile = $this->enterpriseFileBusiness->create(request: $request);
        return ResponseUtils::getResponse($entepriseFile, 201);
    }

    /**
     * Gets a EnterpriseFile by id.
     */
    public function show($id) {
        throw new MethodNotImplementedYet("Route not implemented.");
    }

    /**
     * Deletes a EnterpriseFile by id.
     */
    public function destroy($id) {
        $entepriseFile = $this->enterpriseFileBusiness->delete(id: $id);
        return ResponseUtils::getResponse($entepriseFile, 200);
    }

    /**
     * Updates a EnterpriseFile.
     */
    public function update(Request $request, $id) {
        throw new MethodNotImplementedYet("Route not implemented.");
    }

    /**
     * Creates a EnterpriseFile.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
