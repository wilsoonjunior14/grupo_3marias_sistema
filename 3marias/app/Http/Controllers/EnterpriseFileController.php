<?php

namespace App\Http\Controllers;

use App\Business\EnterpriseFileBusiness;
use App\Exceptions\MethodNotImplementedYet;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class EnterpriseFileController extends Controller implements APIController
{
    private $enterpriseFileBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterpriseFileBusiness = new EnterpriseFileBusiness();
        Logger::info("Iniciando o EnterpriseFileController em {$startTime}.");
    }

    /**
     * Gets all EnterpriseFiles.
     */
    public function index() {
        throw new MethodNotImplementedYet("Route not implemented.");
    }

    /**
     * Creates a EnterpriseFile.
     */
    public function store(Request $request) {
        try {
            $entepriseFile = $this->enterpriseFileBusiness->create(request: $request);
            return ResponseUtils::getResponse($entepriseFile, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a EnterpriseFile by id.
     */
    public function show($id) {
        try {
            $entepriseFile = $this->enterpriseFileBusiness->getById(id: $id);
            return ResponseUtils::getResponse($entepriseFile, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a EnterpriseFile by id.
     */
    public function destroy($id) {
        try {
            $entepriseFile = $this->enterpriseFileBusiness->delete(id: $id);
            return ResponseUtils::getResponse($entepriseFile, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a EnterpriseFile.
     */
    public function update(Request $request, $id) {
        throw new MethodNotImplementedYet("Route not implemented.");
    }

    /**
     * Creates a EnterpriseFile.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
