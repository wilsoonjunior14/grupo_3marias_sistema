<?php

namespace App\Business;

use App\Exceptions\EntityNotFoundException;
use App\Models\Address;
use App\Models\Client;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ClientValidator;
use Exception;
use Illuminate\Http\Request;

class ClientBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de clientes.");
        $clients = (new Client())->getAll("name");
        $amount = count($clients);
        foreach ($clients as $client) {
            $client["files"] = (new FileBusiness())->getByClient(clientId: $client->id);
            $address = (new AddressBusiness())->getById($client->address_id);
            $client = $this->mountClientAddressInline(client: $client, address: $address);
        }
        Logger::info("Foram recuperados {$amount} clientes.");
        Logger::info("Finalizando a recuperação de clientes.");
        return $clients;
    }

    public function getByNameAndCPF(string $name, string $cpf) {
        Logger::info("Iniciando a recuperação de cliente pelo nome e cpf.");
        $client = (new Client())->getByNameAndCPF(name: $name, cpf: $cpf);

        if (count($client) === 0) {
            throw new EntityNotFoundException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Cliente"));
        }

        Logger::info("Finalizando a recuperação de cliente.");
        return $client[0];
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de cliente $id.");
        $client = (new Client())->getById($id);
        $address = (new AddressBusiness())->getById($client->address_id);
        $client = $this->mountClientAddressInline($client, $address);
        Logger::info("Finalizando a recuperação de cliente $id.");
        return $client;
    }

    public function delete(int $id) {
        $client = $this->getById(id: $id);
        Logger::info("Deletando o de cliente $id.");
        $client->deleted = true;
        $client->save();
        return $client;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de cliente.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $clientValidator = new ClientValidator(Client::$rules, Client::$rulesMessages);
        $clientValidator->validateData(request: $request);

        $address = (new AddressBusiness())->create($data);
        
        Logger::info("Salvando a nova cliente.");
        $client = new Client($data);
        $client->address_id = $address->id;
        $client->save();
        Logger::info("Finalizando a atualização de cliente.");
        return $client;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do cliente.");
        $client = (new Client())->getById($id);
        $clientUpdated = UpdateUtils::processFieldsToBeUpdated($client, $request->all(), Client::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do cliente.");
        $clientValidator = new ClientValidator(Client::$rules, Client::$rulesMessages);
        $clientValidator->validateUpdate(request: $request);

        (new AddressBusiness())->update($request->all(), id: $client->address_id);

        Logger::info("Atualizando as informações do cliente.");
        $clientUpdated->save();
        return $this->getById(id: $clientUpdated->id);
    }

    private function mountClientAddressInline(Client $client, Address $address) {
        $client["address"] = $address->address;
        $client["neighborhood"] = $address->neighborhood;
        $client["number"] = $address->number;
        $client["complement"] = $address->complement;
        $client["city_id"] = $address->city_id;
        $client["zipcode"] = $address->zipcode;
        return $client;
    }

}