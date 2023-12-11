<?php

namespace App\Http\Controllers;

use App\Business\AccountantBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class AccountantController extends Controller implements APIController
{
    private $accountantBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->accountantBusiness = new AccountantBusiness();
        Logger::info("Iniciando o AccountantController em {$startTime}.");
    }

    /**
     * Gets all accountants.
     */
    public function index() {
        $accountants = $this->accountantBusiness->get(enterpriseId: 1);
        return ResponseUtils::getResponse($accountants, 200);
    }

    /**
     * Creates a accountant.
     */
    public function store(Request $request) {
        $accountant = $this->accountantBusiness->create(request: $request);
        return ResponseUtils::getResponse($accountant, 201);
    }

    /**
     * Gets a accountant by id.
     */
    public function show($id) {
        $accountant = $this->accountantBusiness->getById(id: $id);
        return ResponseUtils::getResponse($accountant, 200);
    }

    /**
     * Deletes a accountant by id.
     */
    public function destroy($id) {
        $accountant = $this->accountantBusiness->delete(id: $id);
        return ResponseUtils::getResponse($accountant, 200);
    }

    /**
     * Updates a accountant.
     */
    public function update(Request $request, $id) {
        $accountantUpdated = $this->accountantBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($accountantUpdated, 200);
    }

    /**
     * Creates a accountant.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
