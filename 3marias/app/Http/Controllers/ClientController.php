<?php

namespace App\Http\Controllers;

use App\Business\ClientBusiness;
use App\Business\FileBusiness;
use Illuminate\Http\Request;
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
     * Gets all clients birthdates.
     */
    public function getBirthdates() {
        try {
            $clients = $this->clientBusiness->getClientsBirthdate();
            echo json_encode($clients);
            return ResponseUtils::getResponse($clients, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets all contracts.
     */
    public function index() {
        try {
            $clients = $this->clientBusiness->get();
            return ResponseUtils::getResponse($clients, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a client.
     */
    public function store(Request $request) {
        try {
            $client = $this->clientBusiness->create(request: $request);
            return ResponseUtils::getResponse($client, 201);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Gets a client by id.
     */
    public function show($id) {
        try {
            $client = $this->clientBusiness->getById(id: $id);
            return ResponseUtils::getResponse($client, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Deletes a client by id.
     */
    public function destroy($id) {
        try {
            $client = $this->clientBusiness->delete(id: $id);
            return ResponseUtils::getResponse($client, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Updates a client.
     */
    public function update(Request $request, $id) {
        try {
            $clientUpdated = $this->clientBusiness->update(id: $id, request: $request);
            return ResponseUtils::getResponse($clientUpdated, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Creates a client.
     */
    public function create(Request $request) {
        return $this->store(request: $request);
    }

    /**
     * Creates one or many files to clients.
     */
    public function saveDocuments(Request $request) {
        try {
            $data = $request->all();
            $files = (new FileBusiness())->createByClient(clientId: $data["client_id"], data: $data);
            return ResponseUtils::getResponse($files, 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }

    /**
     * Delete a specific document of the client.
     */
    public function deleteDocument(Request $request, $id) {
        try {
            return ResponseUtils::getResponse((new FileBusiness())->deleteDocument(id: $id), 200);
        } catch (\App\Exceptions\AppException $e) {
            return ResponseUtils::getExceptionResponse(message: $e->getMessage());
        } catch (\Exception $e) {
            return ResponseUtils::getErrorResponse();
        }
    }
}
