<?php

namespace App\Http\Controllers;

use App\Business\ClientBusiness;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Utils\ResponseUtils;
use App\Models\Logger;

class ClientController extends Controller implements APIController
{
    private $clientBusiness;

    public function __construct() {
        $startTime = date('d/m/Y H:i:s');
        $this->clientBusiness = new ClientBusiness();
        Logger::info("Iniciando o ClientController em {$startTime}.");
    }

    /**
     * Gets all contracts.
     */
    public function index() {
        $clients = $this->clientBusiness->get();
        return ResponseUtils::getResponse($clients, 200);
    }

    /**
     * Creates a client.
     */
    public function store(Request $request) {
        $client = $this->clientBusiness->create(request: $request);
        return ResponseUtils::getResponse($client, 201);
    }

    /**
     * Gets a client by id.
     */
    public function show($id) {
        $client = $this->clientBusiness->getById(id: $id);
        return ResponseUtils::getResponse($client, 200);
    }

    /**
     * Deletes a client by id.
     */
    public function destroy($id) {
        $client = $this->clientBusiness->delete(id: $id);
        return ResponseUtils::getResponse($client, 200);
    }

    /**
     * Updates a client.
     */
    public function update(Request $request, $id) {
        $clientUpdated = $this->clientBusiness->update(id: $id, request: $request);
        return ResponseUtils::getResponse($clientUpdated, 200);
    }

    /**
     * Creates a client.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }
}
