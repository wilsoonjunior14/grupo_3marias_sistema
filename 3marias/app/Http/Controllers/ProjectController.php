<?php

namespace App\Http\Controllers;

use App\Business\ProjectBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ProjectController extends Controller implements APIController
{
    private $projectBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->projectBusiness = new ProjectBusiness();
        Logger::info("Iniciando o ProjectController em {$startTime}.");
    }

    /**
     * Gets all projects.
     */
    public function index() {
        $projects = $this->projectBusiness->get();
        return ResponseUtils::getResponse($projects, 200);
    }

    /**
     * Creates a project.
     */
    public function store(Request $request) {
        $project = $this->projectBusiness->create(request: $request);
        return ResponseUtils::getResponse($project, 201);
    }

    /**
     * Gets a project by id.
     */
    public function show($id) {
        $project = $this->projectBusiness->getById(id: $id);
        return ResponseUtils::getResponse($project, 200);
    }

    /**
     * Deletes a project by id.
     */
    public function destroy($id) {
        $project = $this->projectBusiness->delete(id: $id);
        return ResponseUtils::getResponse($project, 200);
    }

    /**
     * Updates a project.
     */
    public function update(Request $request, $id) {
        $projectUpdated = $this->projectBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($projectUpdated, 200);
    }

    /**
     * Creates a project.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

}
