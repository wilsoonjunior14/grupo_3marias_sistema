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
        try {
            $enterpriseOwners = $this->enterpriseOwnerBusiness->get(enterpriseId: 1);
            return ResponseUtils::getResponse($enterpriseOwners, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a EnterpriseOwner.
     */
    public function store(Request $request) {
        try {
            $entepriseOwner = $this->enterpriseOwnerBusiness->create(request: $request);
            return ResponseUtils::getResponse($entepriseOwner, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a EnterpriseOwner by id.
     */
    public function show($id) {
        try {
            $entepriseOwner = $this->enterpriseOwnerBusiness->getById(id: $id);
            return ResponseUtils::getResponse($entepriseOwner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            error_log($e);
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a EnterpriseOwner by id.
     */
    public function destroy($id) {
        try {
            $entepriseOwner = $this->enterpriseOwnerBusiness->delete(id: $id);
            return ResponseUtils::getResponse($entepriseOwner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a EnterpriseOwner.
     */
    public function update(Request $request, $id) {
        try {
            $entepriseOwnerUpdated = $this->enterpriseOwnerBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($entepriseOwnerUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a EnterpriseOwner.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
