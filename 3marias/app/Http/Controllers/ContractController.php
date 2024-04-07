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
        try {
            $contracts = $this->contractBusiness->get();
            return ResponseUtils::getResponse($contracts, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a contract.
     */
    public function store(Request $request) {
        try {
            $contract = $this->contractBusiness->create(request: $request);
            return ResponseUtils::getResponse($contract, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a contract by id.
     */
    public function show($id) {
        try {
            $contract = $this->contractBusiness->getById(id: $id);
            return ResponseUtils::getResponse($contract, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a contract by id.
     */
    public function destroy($id) {
        try {
            $contract = $this->contractBusiness->delete(id: $id);
            return ResponseUtils::getResponse($contract, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a contract.
     */
    public function update(Request $request, $id) {
        try {
            $contractUpdated = $this->contractBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($contractUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a contract.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
