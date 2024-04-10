<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\ServiceOrder;
use App\Models\Stock;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ServiceOrderBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de serviços.");
        $services = (new ServiceOrder())->getAll("date");
        $amount = count($services);
        Logger::info("Foram recuperados {$amount} serviços.");
        Logger::info("Finalizando a recuperação de serviços.");
        return $services;
    }

    public function getById(int $id, bool $merge = false) {
        Logger::info("Iniciando a recuperação de ordem de serviço $id.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Ordem de Serviço"));
        }
        $service = (new ServiceOrder())->getById($id);
        if (is_null($service)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Ordem de Serviço"));
        }
        Logger::info("Finalizando a recuperação de ordem de serviço $id.");
        return $service;
    }

    public function delete(int $id) {
        $service = $this->getById(id: $id);
        Logger::info("Deletando ordem de serviço $id.");
        $service->deleted = true;
        $service->save();
        return $service;
    }

    public function create(Request $request) {
        $payload = $request->all();
        $rules = [
            'services' => 'required|array|min:1|distinct',
            'services.*.description' => 'distinct'
        ];
        $rulesMessages = [
            'services.required' => 'Campo Lista de Serviços é obrigatório.',
            'services.array' => 'Campo Lista de Serviços está inválido.',
            'services.min' => 'Campo Lista de Serviços não pode ser vazio.',
            'services.distinct' => 'Campo Lista de Serviços está inválido.',
            'services.*.description.distinct' => 'Campo Lista de Serviços contém ordens com mesma descrição.'
        ];
        
        // Validating the services payload
        $validator = new ModelValidator($rules, $rulesMessages);
        $hasErrors = $validator->validate(data: $payload);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }

        // Validating the services individually
        foreach ($payload["services"] as $service) {
            $serviceOrder = (new ServiceOrder($service))->withStatus(0);
            $serviceOrder->validate(ServiceOrder::$rules, ServiceOrder::$rulesMessages);
            (new StockBusiness())->getById(id: $serviceOrder->cost_center_id, mergeFields: false);
            (new ServiceBusiness())->getById(id: $serviceOrder->cost_center_id);
        }

        // Creating the services individually
        $services = [];
        foreach ($payload["services"] as $service) {
            $serviceOrder = (new ServiceOrder($service))->withStatus(0);
            $serviceOrder->save();
            $services[] = $serviceOrder;
        }

        return $services;
    }

    public function update(int $id, Request $request) {
    }

}