<?php

namespace App\Http\Controllers;

use App\Business\PartnerBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class PartnerController extends Controller implements APIController
{
    private $partnerBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->partnerBusiness = new PartnerBusiness();
        Logger::info("Iniciando o PartnerController em {$startTime}.");
    }

    /**
     * Gets all partners.
     */
    public function index() {
        $partners = $this->partnerBusiness->get();
        return ResponseUtils::getResponse($partners, 200);
    }

    /**
     * Creates a partner.
     */
    public function store(Request $request) {
        $partner = $this->partnerBusiness->create(request: $request);
        return ResponseUtils::getResponse($partner, 201);
    }

    /**
     * Gets a partner by id.
     */
    public function show($id) {
        $partner = $this->partnerBusiness->getById(id: $id);
        return ResponseUtils::getResponse($partner, 200);
    }

    /**
     * Deletes a partner by id.
     */
    public function destroy($id) {
        $partner = $this->partnerBusiness->delete(id: $id);
        return ResponseUtils::getResponse($partner, 200);
    }

    /**
     * Updates a partner.
     */
    public function update(Request $request, $id) {
        $partner = $this->partnerBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($partner, 200);
    }

    /**
     * Creates a partner.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
