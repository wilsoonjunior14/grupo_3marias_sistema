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
        try {
            $accountants = $this->accountantBusiness->get(enterpriseId: 1);
            return ResponseUtils::getResponse($accountants, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a accountant.
     */
    public function store(Request $request) {
        try {
            $accountant = $this->accountantBusiness->create(request: $request);
            return ResponseUtils::getResponse($accountant, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a accountant by id.
     */
    public function show($id) {
        try {
            $accountant = $this->accountantBusiness->getById(id: $id);
            return ResponseUtils::getResponse($accountant, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a accountant by id.
     */
    public function destroy($id) {
        try {
            $accountant = $this->accountantBusiness->delete(id: $id);
            return ResponseUtils::getResponse($accountant, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a accountant.
     */
    public function update(Request $request, $id) {
        try {
            $accountantUpdated = $this->accountantBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($accountantUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a accountant.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
