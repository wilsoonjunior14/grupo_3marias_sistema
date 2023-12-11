<?php

namespace App\Http\Controllers;

use App\Business\DocumentTypeBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class DocumentTypeController extends Controller implements APIController
{
    private $documentTypeBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->documentTypeBusiness = new DocumentTypeBusiness();
        Logger::info("Iniciando o DocumentTypeController em {$startTime}.");
    }

    /**
     * Gets all DocumentTypes.
     */
    public function index() {
        $enterpriseBranches = $this->documentTypeBusiness->get();
        return ResponseUtils::getResponse($enterpriseBranches, 200);
    }

    /**
     * Creates a DocumentType.
     */
    public function store(Request $request) {
        $enterpriseBranch = $this->documentTypeBusiness->create(request: $request);
        return ResponseUtils::getResponse($enterpriseBranch, 201);
    }

    /**
     * Gets a DocumentType by id.
     */
    public function show($id) {
        $enterpriseBranch = $this->documentTypeBusiness->getById(id: $id);
        return ResponseUtils::getResponse($enterpriseBranch, 200);
    }

    /**
     * Deletes a DocumentType by id.
     */
    public function destroy($id) {
        $enterpriseBranch = $this->documentTypeBusiness->delete(id: $id);
        return ResponseUtils::getResponse($enterpriseBranch, 200);
    }

    /**
     * Updates a DocumentType.
     */
    public function update(Request $request, $id) {
        $enterpriseBranchUpdated = $this->documentTypeBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($enterpriseBranchUpdated, 200);
    }

    /**
     * Creates a DocumentType.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
