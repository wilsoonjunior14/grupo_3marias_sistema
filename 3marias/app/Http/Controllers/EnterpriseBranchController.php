<?php

namespace App\Http\Controllers;

use App\Business\EnterpriseBranchBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class EnterpriseBranchController extends Controller implements APIController
{
    private $enterpriseBranchBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterpriseBranchBusiness = new EnterpriseBranchBusiness();
        Logger::info("Iniciando o EnterpriseBranchController em {$startTime}.");
    }

    /**
     * Gets all EnterpriseBranches.
     */
    public function index() {
        try {
            $enterpriseBranches = $this->enterpriseBranchBusiness->get(enterpriseId: 1);
            return ResponseUtils::getResponse($enterpriseBranches, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a EnterpriseBranch.
     */
    public function store(Request $request) {
        try {
            $enterpriseBranch = $this->enterpriseBranchBusiness->create(request: $request);
            return ResponseUtils::getResponse($enterpriseBranch, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a EnterpriseBranch by id.
     */
    public function show($id) {
        try {
            $enterpriseBranch = $this->enterpriseBranchBusiness->getById(id: $id);
            return ResponseUtils::getResponse($enterpriseBranch, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a EnterpriseBranch by id.
     */
    public function destroy($id) {
        try {
            $enterpriseBranch = $this->enterpriseBranchBusiness->delete(id: $id);
            return ResponseUtils::getResponse($enterpriseBranch, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a EnterpriseBranch.
     */
    public function update(Request $request, $id) {
        try {
            $enterpriseBranchUpdated = $this->enterpriseBranchBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($enterpriseBranchUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a EnterpriseBranch.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
