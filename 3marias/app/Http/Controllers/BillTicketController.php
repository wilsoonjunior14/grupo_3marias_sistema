<?php

namespace App\Http\Controllers;

use App\Business\BillTicketBusiness;
use App\Exceptions\InputValidationException;
use App\Exceptions\MethodNotImplementedYet;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class BillTicketController extends Controller implements APIController
{
    private $billTicketBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->billTicketBusiness = new BillTicketBusiness();
        Logger::info("Iniciando o BillTicketController em {$startTime}.");
    }

    /**
     * Gets all bills.
     */
    public function index() {
        try {
            $bills = $this->billTicketBusiness->getAll();
            return ResponseUtils::getResponse($bills, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a bill.
     */
    public function store(Request $request) {
        try {
            $bill = $this->billTicketBusiness->create(data: $request->all());
            return ResponseUtils::getResponse($bill, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a bill by id.
     */
    public function show($id) {
        try {
            $bill = $this->billTicketBusiness->getById(id: $id);
            return ResponseUtils::getResponse($bill, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a bill by id.
     */
    public function destroy($id) {
        try {
            $bill = $this->billTicketBusiness->delete(id: $id);
            return ResponseUtils::getResponse($bill, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a bill.
     */
    public function update(Request $request, $id) {
        throw new MethodNotImplementedYet("Método não implementado.", 400);
    }

    /**
     * Creates a bill.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
