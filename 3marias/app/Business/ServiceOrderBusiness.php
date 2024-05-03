<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\ServiceOrder;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ServiceOrderBusiness {

    // TODO: these functions can be generalized
    private function setStatusIcon(ServiceOrder $serviceOrder) {
        if ($serviceOrder->status === 0) {
            $serviceOrder["icon"] = "access_time";
            $serviceOrder["icon_color"] = "gray";
        }
        if ($serviceOrder->status === 1) {
            $serviceOrder["icon"] = "thumb_down";
            $serviceOrder["icon_color"] = "red";
        }
        if ($serviceOrder->status === 2) {
            $serviceOrder["icon"] = "thumb_up";
            $serviceOrder["icon_color"] = "green";
        }
    }

    public function get() {
        Logger::info("Iniciando a recuperação de serviços.");
        $services = (new ServiceOrder())->getAll("date");
        $amount = count($services);
        foreach ($services as $service) {
            $service->total_value = str_replace(".", ",", "" . $service->value * $service->quantity);
            $this->setStatusIcon(serviceOrder: $service);
        }
        Logger::info("Foram recuperados {$amount} serviços.");
        Logger::info("Finalizando a recuperação de serviços.");
        return $services;
    }

    public function getServicesByStock(int $id) {
        Logger::info("Iniciando a recuperação de itens do centro de custo.");
        $services = (new ServiceOrder())->getServiceByStock(id: $id);
        Logger::info("Finalizando a recuperação de itens do centro de custo.");
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
        $bills = (new BillPayBusiness())->getBillsByService(serviceId: $id);
        if (count($bills) > 0) {
            throw new InputValidationException("Operação não permitida. Existem contas a pagar dessa ordem de serviço.");
        }

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
        Logger::info("Inicializando a atualização da ordem de serviço.");
        $serviceOrder = $this->getById($id);
        if ($serviceOrder->status !== 0) {
            throw new InputValidationException("Operação não permitida. Ordem de Serviço já validada ou cancelada.");
        }
        
        $serviceOrderUpdated = UpdateUtils::updateFields(fieldsToBeUpdated: ServiceOrder::$fieldsToBeUpdated, model: $serviceOrder, requestData: $request->all());
        Logger::info("Validando as informações da ordem de serviço.");
        $serviceOrderUpdated->validate(ServiceOrder::$rules, ServiceOrder::$rulesMessages);
        (new StockBusiness())->getById(id: $serviceOrder->cost_center_id, mergeFields: false);
        (new ServiceBusiness())->getById(id: $serviceOrder->cost_center_id);

        Logger::info("Atualizando a ordem de serviço.");
        $serviceOrderUpdated->save();

        // Creating bills to pay
        if ($serviceOrderUpdated->status === 2) { // only if service order is approved
            (new BillPayBusiness())->createBillPay(baseModel: $serviceOrderUpdated);
        }
        return $serviceOrderUpdated;
    }

}