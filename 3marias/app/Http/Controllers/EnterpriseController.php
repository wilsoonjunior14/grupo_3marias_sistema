<?php

namespace App\Http\Controllers;

use App\Business\EnterpriseBusiness;
use App\Exceptions\MethodNotImplementedYet;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use Exception;

class EnterpriseController extends Controller implements APIController
{
    private $enterpriseBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterpriseBusiness = new EnterpriseBusiness();
        Logger::info("Iniciando o EnterpriseController em {$startTime}.");
    }

    /**
     * Gets all enterprises.
     */
    public function index() {
        try {
            $enterprises = $this->enterpriseBusiness->get();
            return ResponseUtils::getResponse($enterprises, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a enterprise.
     */
    public function store(Request $request) {
        try {
            $enterprise = $this->enterpriseBusiness->create(request: $request);
            return ResponseUtils::getResponse($enterprise, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a enterprise by id.
     */
    public function show($id) {
        try {
            $enterprise = $this->enterpriseBusiness->getById(id: $id, mergeFields: true);
            return ResponseUtils::getResponse($enterprise, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a enterprise by id.
     */
    public function destroy($id) {
        throw new MethodNotImplementedYet("Route not Implemented.");
    }

    /**
     * Updates a enterprise.
     */
    public function update(Request $request, $id) {
        try {
            $enterpriseUpdated = $this->enterpriseBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($enterpriseUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a enterprise.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
