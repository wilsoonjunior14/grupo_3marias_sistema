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
        $bills = $this->billsReceiveBusiness->getAll();
        return ResponseUtils::getResponse($bills, 200);
    }

    /**
     * Gets all bills in progress.
     */
    public function inprogress() {
        $bills = $this->billsReceiveBusiness->getBillsInProgress();
        return ResponseUtils::getResponse($bills, 200);
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
        $bill = $this->billsReceiveBusiness->getById(id: $id);
        return ResponseUtils::getResponse($bill, 200);
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
        $bill = $this->billsReceiveBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($bill, 200);
    }

    /**
     * Creates a bill.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
