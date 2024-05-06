<?php

namespace App\Http\Controllers;

use App\Business\EngineerBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class EngineerController extends Controller implements APIController
{
    private $engineerBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->engineerBusiness = new EngineerBusiness();
        Logger::info("Iniciando o EngineerController em {$startTime}.");
    }

    /**
     * Gets all engineers.
     */
    public function index() {
        try {
            $engineers = $this->engineerBusiness->get();
            return ResponseUtils::getResponse($engineers, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a engineer.
     */
    public function store(Request $request) {
        try {
            $engineer = $this->engineerBusiness->create(request: $request);
            return ResponseUtils::getResponse($engineer, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a engineer by id.
     */
    public function show($id) {
        try {
            $engineer = $this->engineerBusiness->getById(id: $id);
            return ResponseUtils::getResponse($engineer, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a engineer by id.
     */
    public function destroy($id) {
        try {
            $engineer = $this->engineerBusiness->delete(id: $id);
            return ResponseUtils::getResponse($engineer, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a engineer.
     */
    public function update(Request $request, $id) {
        try {
            $engineer = $this->engineerBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($engineer, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a engineer.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
