<?php

namespace App\Http\Controllers;

use App\Business\BrokerBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;

class BrokerController extends Controller implements APIController
{
    private $brokerBO;

    public function __construct() {
        $this->brokerBO = new BrokerBusiness();
    }

    public function index() {
        try {
            $brokers = $this->brokerBO->get();
            return ResponseUtils::getResponse($brokers, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    public function store(Request $request) {
        try {
            $broker = $this->brokerBO->create(request: $request);
            return ResponseUtils::getResponse($broker, 201);
        } catch (\App\Exceptions\AppException $e) {
            error_log($e);
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            error_log($e);
            return ResponseUtils::getErrorResponse($e);
        }
    }

    public function show($id) {
        try {
            $broker = $this->brokerBO->getById(id: $id);
            return ResponseUtils::getResponse($broker, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    public function destroy($id) {
        try {
            $broker = $this->brokerBO->delete(id: $id);
            return ResponseUtils::getResponse($broker, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    public function update(Request $request, $id) {
        try {
            $broker = $this->brokerBO->update(id: $id, request: $request);
            return ResponseUtils::getResponse($broker, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }
    
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
