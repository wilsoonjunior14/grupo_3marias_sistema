<?php

namespace App\Http\Controllers;

use App\Business\ServiceOrderBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ServiceOrderController extends Controller implements APIController
{
    private $serviceBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->serviceBusiness = new ServiceOrderBusiness();
        Logger::info("Iniciando o ServiceOrderController em {$startTime}.");
    }

    /**
     * Gets all serviceOrders.
     */
    public function index() {
        try {
            $serviceOrderOrders = $this->serviceBusiness->get();
            return ResponseUtils::getResponse($serviceOrderOrders, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a serviceOrder.
     */
    public function store(Request $request) {
        try {
            $serviceOrder = $this->serviceBusiness->create(request: $request);
            return ResponseUtils::getResponse($serviceOrder, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a service by id.
     */
    public function show($id) {
        try {
            $serviceOrder = $this->serviceBusiness->getById(id: $id);
            return ResponseUtils::getResponse($serviceOrder, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a service by id.
     */
    public function destroy($id) {
        try {
            $serviceOrder = $this->serviceBusiness->delete(id: $id);
            return ResponseUtils::getResponse($serviceOrder, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a service.
     */
    public function update(Request $request, $id) {
        try {
            $serviceOrder = $this->serviceBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($serviceOrder, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a service.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
