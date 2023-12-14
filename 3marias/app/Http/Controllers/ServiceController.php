<?php

namespace App\Http\Controllers;

use App\Business\ServiceBusiness;
use Illuminate\Http\Request;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ServiceController extends Controller implements APIController
{
    private $serviceBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->serviceBusiness = new ServiceBusiness();
        Logger::info("Iniciando o ServiceController em {$startTime}.");
    }

    /**
     * Gets all services.
     */
    public function index() {
        $services = $this->serviceBusiness->get();
        return ResponseUtils::getResponse($services, 200);
    }

    /**
     * Creates a service.
     */
    public function store(Request $request) {
        $service = $this->serviceBusiness->create(request: $request);
        return ResponseUtils::getResponse($service, 201);
    }

    /**
     * Gets a service by id.
     */
    public function show($id) {
        $service = $this->serviceBusiness->getById(id: $id);
        return ResponseUtils::getResponse($service, 200);
    }

    /**
     * Deletes a service by id.
     */
    public function destroy($id) {
        $service = $this->serviceBusiness->delete(id: $id);
        return ResponseUtils::getResponse($service, 200);
    }

    /**
     * Updates a service.
     */
    public function update(Request $request, $id) {
        $service = $this->serviceBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($service, 200);
    }

    /**
     * Creates a service.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
