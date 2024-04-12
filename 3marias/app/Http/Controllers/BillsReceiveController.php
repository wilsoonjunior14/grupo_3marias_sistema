<?php

namespace App\Http\Controllers;

use App\Business\AccountantBusiness;
use App\Business\BillReceiveBusiness;
use App\Exceptions\InputValidationException;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class BillsReceiveController extends Controller implements APIController
{
    private $billsReceiveBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->billsReceiveBusiness = new BillReceiveBusiness();
        Logger::info("Iniciando o BillsReceiveController em {$startTime}.");
    }

    /**
     * Gets all bills.
     */
    public function index() {
        try {
            $bills = $this->billsReceiveBusiness->getAll();
            return ResponseUtils::getResponse($bills, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets all bills in progress.
     */
    public function inprogress() {
        try {
            $bills = $this->billsReceiveBusiness->getBillsInProgress();
            return ResponseUtils::getResponse($bills, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a bill.
     */
    public function store(Request $request) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Gets a bill by id.
     */
    public function show($id) {
        try {
            $bill = $this->billsReceiveBusiness->getById(id: $id);
            return ResponseUtils::getResponse($bill, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a bill by id.
     */
    public function destroy($id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Updates a bill.
     */
    public function update(Request $request, $id) {
        try {
            $bill = $this->billsReceiveBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($bill, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a bill.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
