<?php

namespace App\Http\Controllers;

use App\Business\ProposalBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ProposalController extends Controller implements APIController
{
    private $proposalBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->proposalBusiness = new ProposalBusiness();
        Logger::info("Iniciando o ProposalController em {$startTime}.");
    }

    /**
     * Gets all proposals.
     */
    public function index() {
        try {
            $proposals = $this->proposalBusiness->get();
            return ResponseUtils::getResponse($proposals, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a proposal.
     */
    public function store(Request $request) {
        try {
            $proposal = $this->proposalBusiness->create(request: $request);
            return ResponseUtils::getResponse($proposal, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a proposal by id.
     */
    public function show($id) {
        try {
            $proposal = $this->proposalBusiness->getById(id: $id);
            return ResponseUtils::getResponse($proposal, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a proposal by id.
     */
    public function destroy($id) {
        try {
            $proposal = $this->proposalBusiness->delete(id: $id);
            return ResponseUtils::getResponse($proposal, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Approves a proposal by id.
     */
    public function approve($id) {
        try {
            $proposal = $this->proposalBusiness->approve(id: $id);
            return ResponseUtils::getResponse($proposal, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Rejects a proposal by id.
     */
    public function reject($id) {
        try {
            $proposal = $this->proposalBusiness->reject(id: $id);
            return ResponseUtils::getResponse($proposal, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a proposal.
     */
    public function update(Request $request, $id) {
        try {
            $proposalUpdated = $this->proposalBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($proposalUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a proposal.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

}
