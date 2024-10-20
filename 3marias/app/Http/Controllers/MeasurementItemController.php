<?php

namespace App\Http\Controllers;

use App\Business\MeasurementItemBusiness;
use App\Exceptions\InputValidationException;
use App\Exceptions\MethodNotImplementedYet;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class MeasurementItemController extends Controller implements APIController
{
    private $measurementItemBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->measurementItemBusiness = new MeasurementItemBusiness();
        Logger::info("Iniciando o MeasurementitemurationController em {$startTime}.");
    }

    /**
     * Gets all items.
     */
    public function index() {
        throw new MethodNotImplementedYet(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a item.
     */
    public function store(Request $request) {
        try {
            $item = $this->measurementItemBusiness->create(data: $request->all());
            return ResponseUtils::getResponse($item, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a item by id.
     */
    public function show($id) {
        throw new MethodNotImplementedYet(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Deletes a item by id.
     */
    public function destroy($id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Updates a item.
     */
    public function update(Request $request, $id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a item.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
