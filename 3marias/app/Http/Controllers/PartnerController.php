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
        try {
            $partners = $this->partnerBusiness->get();
            return ResponseUtils::getResponse($partners, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a partner.
     */
    public function store(Request $request) {
        try {
            $partner = $this->partnerBusiness->create(request: $request);
            return ResponseUtils::getResponse($partner, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a partner by id.
     */
    public function show($id) {
        try {
            $partner = $this->partnerBusiness->getById(id: $id);
            return ResponseUtils::getResponse($partner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a partner by id.
     */
    public function destroy($id) {
        try {
            $partner = $this->partnerBusiness->delete(id: $id);
            return ResponseUtils::getResponse($partner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a partner.
     */
    public function update(Request $request, $id) {
        try {
            $partner = $this->partnerBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($partner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a partner.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
