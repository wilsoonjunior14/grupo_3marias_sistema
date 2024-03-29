<?php

namespace App\Http\Controllers;

use App\Business\PurchaseOrderBusiness;
use App\Exceptions\MethodNotImplementedYet;
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
        $purchases = $this->purchaseBusiness->get();
        return ResponseUtils::getResponse($purchases, 200);
    }

    /**
     * Creates a purchase.
     */
    public function store(Request $request) {
        $purchase = $this->purchaseBusiness->create(request: $request);
        return ResponseUtils::getResponse($purchase, 201);
    }

    /**
     * Gets a purchase by id.
     */
    public function show($id) {
        $purchase = $this->purchaseBusiness->getById(id: $id, mergeFields: true);
        return ResponseUtils::getResponse($purchase, 200);
    }

    /**
     * Deletes a purchase by id.
     */
    public function destroy($id) {
        $purchase = $this->purchaseBusiness->delete(id: $id);
        return ResponseUtils::getResponse($purchase, 200);
    }

    /**
     * Updates a purchase.
     */
    public function update(Request $request, $id) {
        $purchase = $this->purchaseBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($purchase, 200);
    }

    /**
     * Creates a purchase.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

}
