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
        try {
            $projects = $this->projectBusiness->get();
            return ResponseUtils::getResponse($projects, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a project.
     */
    public function store(Request $request) {
        try {
            $project = $this->projectBusiness->create(request: $request);
            return ResponseUtils::getResponse($project, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Gets a project by id.
     */
    public function show($id) {
        try {
            $project = $this->projectBusiness->getById(id: $id);
            return ResponseUtils::getResponse($project, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Deletes a project by id.
     */
    public function destroy($id) {
        try {
            $project = $this->projectBusiness->delete(id: $id);
            return ResponseUtils::getResponse($project, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Updates a project.
     */
    public function update(Request $request, $id) {
        try {
            $projectUpdated = $this->projectBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($projectUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse($e);
        }
    }

    /**
     * Creates a project.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

}
