<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class StockController extends Controller implements APIController
{
    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o StockController em {$startTime}.");
    }

    /**
     * Gets all contracts.
     */
    public function index() {
        Logger::info("Iniciando a recuperação de estoques.");

        $stocks = (new Stock())->getAll("name");
        $amount = count($stocks);
        Logger::info("Foram recuperados {$amount} estoques.");

        Logger::info("Finalizando a recuperação de estoques.");
        return ResponseUtils::getResponse($stocks, 200);
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
