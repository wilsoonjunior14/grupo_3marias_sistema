<?php

namespace App\Http\Controllers;

use App\Business\EnterprisePartnerBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class EnterprisePartnerController extends Controller implements APIController
{
    private $enterprisePartnerBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->enterprisePartnerBusiness = new EnterprisePartnerBusiness();
        Logger::info("Iniciando o EnterprisePartnerController em {$startTime}.");
    }

    /**
     * Gets all EnterprisePartners.
     */
    public function index() {
        try {
            $enterprisePartners = $this->enterprisePartnerBusiness->get(enterpriseId: 1);
            return ResponseUtils::getResponse($enterprisePartners, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a EnterprisePartner.
     */
    public function store(Request $request) {
        try {
            $enteprisePartner = $this->enterprisePartnerBusiness->create(request: $request);
            return ResponseUtils::getResponse($enteprisePartner, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a EnterprisePartner by id.
     */
    public function show($id) {
        try {
            $enteprisePartner = $this->enterprisePartnerBusiness->getById(id: $id);
            return ResponseUtils::getResponse($enteprisePartner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a EnterprisePartner by id.
     */
    public function destroy($id) {
        try {
            $enteprisePartner = $this->enterprisePartnerBusiness->delete(id: $id);
            return ResponseUtils::getResponse($enteprisePartner, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a EnterprisePartner.
     */
    public function update(Request $request, $id) {
        try {
            $enteprisePartnerUpdated = $this->enterprisePartnerBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($enteprisePartnerUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a EnterprisePartner.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
