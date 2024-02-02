<?php

namespace App\Http\Controllers;

use App\Business\ContractBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ContractController extends Controller implements APIController
{
    private $contractBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->contractBusiness = new ContractBusiness();
        Logger::info("Iniciando o ContractController em {$startTime}.");
    }

    /**
     * Gets all contracts.
     */
    public function index() {
        $contracts = $this->contractBusiness->get();
        return ResponseUtils::getResponse($contracts, 200);
    }

    /**
     * Creates a contract.
     */
    public function store(Request $request) {
        $contract = $this->contractBusiness->create(request: $request);
        return ResponseUtils::getResponse($contract, 201);
    }

    /**
     * Gets a contract by id.
     */
    public function show($id) {
        $contract = $this->contractBusiness->getById(id: $id);
        return ResponseUtils::getResponse($contract, 200);
    }

    /**
     * Deletes a contract by id.
     */
    public function destroy($id) {
        $contract = $this->contractBusiness->delete(id: $id);
        return ResponseUtils::getResponse($contract, 200);
    }

    /**
     * Updates a contract.
     */
    public function update(Request $request, $id) {
        $contractUpdated = $this->contractBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($contractUpdated, 200);
    }

    /**
     * Creates a contract.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
