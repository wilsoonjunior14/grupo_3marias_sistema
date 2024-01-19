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
        $proposals = $this->proposalBusiness->get();
        return ResponseUtils::getResponse($proposals, 200);
    }

    /**
     * Creates a proposal.
     */
    public function store(Request $request) {
        $proposal = $this->proposalBusiness->create(request: $request);
        return ResponseUtils::getResponse($proposal, 201);
    }

    /**
     * Gets a proposal by id.
     */
    public function show($id) {
        $proposal = $this->proposalBusiness->getById(id: $id);
        return ResponseUtils::getResponse($proposal, 200);
    }

    /**
     * Deletes a proposal by id.
     */
    public function destroy($id) {
        $proposal = $this->proposalBusiness->delete(id: $id);
        return ResponseUtils::getResponse($proposal, 200);
    }

    /**
     * Updates a proposal.
     */
    public function update(Request $request, $id) {
        $proposalUpdated = $this->proposalBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($proposalUpdated, 200);
    }

    /**
     * Creates a proposal.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

}
