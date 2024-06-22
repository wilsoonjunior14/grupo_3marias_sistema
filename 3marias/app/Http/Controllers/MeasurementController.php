<?php

namespace App\Http\Controllers;

use App\Business\MeasurementBusiness;
use App\Exceptions\InputValidationException;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class MeasurementController extends Controller implements APIController
{
    private $measurementBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->measurementBusiness = new MeasurementBusiness();
        Logger::info("Iniciando o MeasurementController em {$startTime}.");
    }

    /**
     * Gets all measurements.
     */
    public function getByBillReceiveId(int $billReceiveId) {
        try {
            $measurement = $this->measurementBusiness->getByReceiveId(billReceiveId: $billReceiveId);
            return ResponseUtils::getResponse($measurement, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets all measurements.
     */
    public function index() {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a measurement.
     */
    public function store(Request $request) {
        try {
            $measurement = $this->measurementBusiness->create(data: $request->all());
            return ResponseUtils::getResponse($measurement, 201);
        } catch (\App\Exceptions\AppException $e) {
            error_log($e);
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            error_log($e);
            var_dump($e);
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a measurement by id.
     */
    public function show($id) {
        try {
            $measurement = $this->measurementBusiness->getById(id: $id);
            return ResponseUtils::getResponse($measurement, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a measurement by id.
     */
    public function destroy($id) {
        try {
            $measurement = $this->measurementBusiness->delete(id: $id);
            return ResponseUtils::getResponse($measurement, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a measurement.
     */
    public function update(Request $request, $id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a measurement.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
