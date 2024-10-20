<?php

namespace App\Http\Controllers;

use App\Business\MeasurementConfigurationBusiness;
use App\Exceptions\InputValidationException;
use App\Exceptions\MethodNotImplementedYet;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class MeasurementConfigurationController extends Controller implements APIController
{
    private $measurementConfigBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->measurementConfigBusiness = new MeasurementConfigurationBusiness();
        Logger::info("Iniciando o MeasurementConfigurationController em {$startTime}.");
    }

    /**
     * Gets all configs.
     */
    public function index() {
        throw new MethodNotImplementedYet(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a config.
     */
    public function store(Request $request) {
        try {
            $config = $this->measurementConfigBusiness->create(data: $request->all());
            return ResponseUtils::getResponse($config, 201);
        } catch (\App\Exceptions\AppException $e) {
            error_log($e);
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            error_log($e);
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a config by id.
     */
    public function show($id) {
        throw new MethodNotImplementedYet(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Deletes a config by id.
     */
    public function destroy($id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Updates a config.
     */
    public function update(Request $request, $id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a config.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
