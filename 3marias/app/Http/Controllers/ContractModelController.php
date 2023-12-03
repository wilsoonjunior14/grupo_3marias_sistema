<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContractModel;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ContractModelController extends Controller implements APIController
{
    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o ContractModelController em {$startTime}.");
    }

    /**
     * Gets all contracts.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de países.");

        $contracts = (new ContractModel())->getAll("name");
        $amount = count($contracts);
        Logger::info("Foram recuperados {$amount} contratos.");

        Logger::info("Finalizando a recuperação de contratos.");
        return ResponseUtils::getResponse($contracts, 200);
    }

    /**
     * Creates a contracts model.
     */
    public function store(Request $request) {
    }

    /**
     * Gets a contracts model by id.
     */
    public function show($id) {
    }

    /**
     * Deletes a contracts models by id.
     */
    public function destroy($id) {
    }

    /**
     * Updates a contracts model.
     */
    public function update(Request $request, $id) {
    }

    /**
     * Creates a contracts model.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
