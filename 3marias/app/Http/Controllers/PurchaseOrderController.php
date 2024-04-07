<?php

namespace App\Http\Controllers;

use App\Business\PurchaseOrderBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class PurchaseOrderController extends Controller implements APIController
{
    private $purchaseBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->purchaseBusiness = new PurchaseOrderBusiness();
        Logger::info("Iniciando o PurchaseOrderController em {$startTime}.");
    }

    /**
     * Gets all purchases.
     */
    public function index() {
        try {
            $purchases = $this->purchaseBusiness->get();
            return ResponseUtils::getResponse($purchases, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a purchase.
     */
    public function store(Request $request) {
        try {
            $purchase = $this->purchaseBusiness->create(request: $request);
            return ResponseUtils::getResponse($purchase, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a purchase by id.
     */
    public function show($id) {
        try {
            $purchase = $this->purchaseBusiness->getById(id: $id, mergeFields: true);
            return ResponseUtils::getResponse($purchase, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a purchase by id.
     */
    public function destroy($id) {
        try {
            $purchase = $this->purchaseBusiness->delete(id: $id);
            return ResponseUtils::getResponse($purchase, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a purchase.
     */
    public function update(Request $request, $id) {
        try {
            $purchase = $this->purchaseBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($purchase, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a purchase.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

    /**
     * Approves a purchase by id.
     */
    public function approve($id) {
        try {
            $purchase = $this->purchaseBusiness->approve(id: $id);
            return ResponseUtils::getResponse($purchase, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Rejects a purchase by id.
     */
    public function reject($id) {
        try {
            $purchase = $this->purchaseBusiness->reject(id: $id);
            return ResponseUtils::getResponse($purchase, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

}
