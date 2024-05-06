<?php

namespace App\Business;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\Client;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ClientValidator;
use Illuminate\Http\Request;

class ClientBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de clientes.");
        $clients = (new Client())->getAll("name");
        $amount = count($clients);
        foreach ($clients as $client) {
            $client["files"] = (new FileBusiness())->getByClient(clientId: $client->id);
            $client->birthdate = !is_null($client->birthdate) ? date_format(date_create($client->birthdate),"d/m/Y") : "";
            $client->rg_date = !is_null($client->rg_date) ? date_format(date_create($client->rg_date),"d/m/Y") : "";
            if (!is_null($client->address_id)) {
                $address = (new AddressBusiness())->getById($client->address_id);
                $client = $this->mountClientAddressInline(client: $client, address: $address);
            }
        }
        Logger::info("Foram recuperados {$amount} clientes.");
        Logger::info("Finalizando a recuperação de clientes.");
        return $clients;
    }

    public function getClientsBirthdate() {
        Logger::info("Iniciando a recuperação de aniversariantes.");
        $clients = (new Client())->getClientsBirthdate();
        Logger::info("Finalizando a recuperação de clientes aniversariantes.");
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

    public function getById(int $id, bool $mergeFields = true) {
        Logger::info("Iniciando a recuperação de cliente $id.");
        try {
            $client = (new Client())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "cliente"));
        } 
        if ($mergeFields && !is_null($client->address_id)) {
            $address = (new AddressBusiness())->getById($client->address_id, merge: $mergeFields);
            $client = $this->mountClientAddressInline($client, $address);
        }
        Logger::info("Finalizando a recuperação de cliente $id.");
        return $client;
    }

    public function delete(int $id) {
        Logger::info("Deletando o de cliente $id.");
        $proposal = (new ProposalBusiness())->getByClientId(clientId: $id);
        if (count($proposal) > 0) {
            throw new InputValidationException("Cliente não pode ser excluído. Existe proposta desse cliente.");
        }

        $client = $this->getById(id: $id, mergeFields: false);
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

        $addressId = null;
        if (isset($data["address"])) {
            $address = (new AddressBusiness())->create($data);
            $addressId = $address->id;
        }
        
        if (!isset($data["state"]) || strcmp($data["state"], "Casado") !== 0) {
            Logger::info("Removendo campos de dependented.");
            $data = UpdateUtils::deleteFields(targetData: $data, fields: Client::$dependentFields);
        }
        Logger::info("Salvando o novo cliente.");
        $client = new Client($data);
        $client->address_id = $addressId;
        $client->save();
        Logger::info("Finalizando a atualização de cliente.");
        return $client;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do cliente.");
        $client = (new Client())->getById($id);
        $clientUpdated = UpdateUtils::processFieldsToBeUpdated($client, $request->all(), Client::$fieldsToBeUpdated);
        if (strcmp($clientUpdated->state, "Casado") !== 0) {
            $clientUpdated = UpdateUtils::nullFields($clientUpdated, Client::$dependentFields);
        }
        
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
        $client["city_name"] = $address->city_name;
        $client["state_acronym"] = $address->state_acronym;
        return $client;
    }

}