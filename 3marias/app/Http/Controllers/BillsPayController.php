<?php

namespace App\Http\Controllers;

use App\Business\BillPayBusiness;
use App\Business\BillReceiveBusiness;
use App\Exceptions\InputValidationException;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;
use App\Utils\ErrorMessage;

class BillsPayController extends Controller implements APIController
{
    private $billsPayBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->billsPayBusiness = new BillPayBusiness();
        Logger::info("Iniciando o BillsPayController em {$startTime}.");
    }

    /**
     * Gets all bills.
     */
    public function index() {
        try {
            $bills = $this->billsPayBusiness->getAll();
            return ResponseUtils::getResponse($bills, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a bill by id.
     */
    public function show($id) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a bill.
     */
    public function store(Request $request) {
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
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
        throw new InputValidationException(ErrorMessage::$METHOD_NOT_IMPLEMENTED);
    }

    /**
     * Creates a bill.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
