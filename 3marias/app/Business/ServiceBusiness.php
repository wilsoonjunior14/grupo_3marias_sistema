<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\Service;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ServiceBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de serviços.");
        $services = (new Service())->getAll("service");
        $amount = count($services);
        Logger::info("Foram recuperados {$amount} serviços.");
        Logger::info("Finalizando a recuperação de serviços.");
        return $services;
    }

    public function getById(int $id, bool $merge = false) {
        Logger::info("Iniciando a recuperação de serviço $id.");
        try {
            $service = (new Service())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Serviço"));
        }
        Logger::info("Finalizando a recuperação de serviço $id.");
        return $service;
    }

    public function delete(int $id) {
        $service = $this->getById(id: $id);
        Logger::info("Deletando o de serviço $id.");
        $service->deleted = true;
        $service->save();
        return $service;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de serviço.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        if (!isset($data["category_service_name"]) || empty($data["category_service_name"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço"));
        }
        $category = (new CategoryServiceBusiness())->getByName(name: $data["category_service_name"]);
        $data["category_service_id"] = $category->id;
        $serviceValidator = new ModelValidator(Service::$rules, Service::$rulesMessages);
        $errors = $serviceValidator->validate(data: $data);
        if (!is_null($errors)) {
            throw new InputValidationException($errors);
        }
        $this->existsEntity(name: $data["service"]);
        (new CategoryServiceBusiness())->getById(id: $data["category_service_id"]);
        
        Logger::info("Salvando o novo serviço.");
        $service = new service($data);
        $service->save();
        Logger::info("Finalizando a atualização de serviço.");
        return $service;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do serviço.");
        $data = $request->all();
        $service = $this->getById($id);
        $serviceUpdated = UpdateUtils::updateFields(fieldsToBeUpdated: Service::$fieldsToBeUpdated, model: $service, requestData: $request->all());

        Logger::info("Validando as informações do serviço.");
        if (!isset($data["category_service_name"]) || empty($data["category_service_name"])) {
            throw new InputValidationException(sprintf(ErrorMessage::$FIELD_REQUIRED, "Nome da Categoria de Serviço"));
        }
        $category = (new CategoryServiceBusiness())->getByName(name: $data["category_service_name"]);
        $serviceUpdated["category_service_id"] = $category->id;
        $data["category_service_id"] = $category->id;
        $serviceValidator = new ModelValidator(Service::$rules, Service::$rulesMessages);
        $errors = $serviceValidator->validate(data: $data);
        if (!is_null($errors)) {
            throw new InputValidationException($errors);
        }
        $this->existsEntity(name: $serviceUpdated["service"], id: $id);
        (new CategoryServiceBusiness())->getById(id: $serviceUpdated["category_service_id"]);

        Logger::info("Atualizando as informações do serviço.");
        $serviceUpdated->save();
        return $this->getById(id: $serviceUpdated->id);
    }

    private function existsEntity(string $name, int $id = null) {
        $condition = [["service", "like", "%" . $name . "%"]];
        $exists = (new Service())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome do Serviço", "serviços"));
        }
        return $exists;
    }

}