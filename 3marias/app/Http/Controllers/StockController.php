<?php

namespace App\Http\Controllers;

use App\Business\StockBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class StockController extends Controller implements APIController
{
    private $stockBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->stockBusiness = new StockBusiness();
        Logger::info("Iniciando o StockController em {$startTime}.");
    }

    /**
     * Gets all stocks.
     */
    public function index() {
        try {
            $stocks = $this->stockBusiness->getAll();
            return ResponseUtils::getResponse($stocks, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a stocks model.
     */
    public function store(Request $request) {
    }

    /**
     * Gets a stocks model by id.
     */
    public function show($id) {
        try {
            $stock = $this->stockBusiness->getById(id: $id, mergeFields: true);
            return ResponseUtils::getResponse($stock, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a stocks models by id.
     */
    public function destroy($id) {
        try {
            $stock = $this->stockBusiness->delete(id: $id);
            return ResponseUtils::getResponse($stock, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a stocks model.
     */
    public function update(Request $request, $id) {
        try {
            $stock = $this->stockBusiness->update(payload: $request->all(), id: $id);
            return ResponseUtils::getResponse($stock, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a stocks model.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

    /**
     * Shares products among stocks.
     */
    public function share(Request $request) {
        try {
            $stock = $this->stockBusiness->shareProductsAmongCostCenters(payload: $request->all());
            return ResponseUtils::getResponse($stock, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }
}
