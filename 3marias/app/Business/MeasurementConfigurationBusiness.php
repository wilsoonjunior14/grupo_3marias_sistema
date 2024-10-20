<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\MeasurementConfiguration;
use App\Utils\ErrorMessage;
use App\Validation\ModelValidator;

class MeasurementConfigurationBusiness {

    public function getById($id, bool $mergeFields = false) {
        Logger::info("Iniciando a recuperação do pagamento.");
        try {
            $bill = (new MeasurementConfiguration())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Medição Inicial"));
        }
        if (!$mergeFields) {
            Logger::info("Finalizando a recuperação do pagamento.");
            return $bill;
        }
        return $bill;
    }

    public function getByBillReceiveId(int $billReceiveId) {
        Logger::info("Iniciando a recuperação da configuração da medição.");
        $configs = (new MeasurementConfiguration())->getByBillReceiveId(billReceiveId: $billReceiveId);
        return $configs;
    }

    public function delete(int $id) {
        $config = $this->getById(id: $id);
        Logger::info("Deletando configuração de medição $id.");
        $config->deleted = true;
        $config->save();
        return $config;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação da medição inicial.");
        Logger::info("Validando a medição inicial.");
        $this->validate(data: $data);

        $configs = [];
        foreach ($data["measurements"] as $measurement) {
            $config = new MeasurementConfiguration($measurement);
            $config->save();
            $configs[] = $config; 
        }
        Logger::info("Finalizando a criação da medição inicial.");
        return $configs;
    }

    public function update(int $billReceiveId, array $data) {
        Logger::info("Alterando informações do parceiro/fornecedor.");
        $this->validate(data: $data);

        // TODO: needs get a bill receive id with all measurement configuration associated.
        $configs = $this->getByBillReceiveId(billReceiveId: $billReceiveId);

        // TODO: needs checks if already exists some measurement over this configuration, if yes, we cannot change the initial configuration
        
        // TODO: deletes the old measurement configuration.
        foreach ($configs as $measurementConfig) {
            $measurementConfig->deleted = true;
            $measurementConfig->save();
        }

        // TODO: needs recreate the measurement items.
        $newConfigs = $this->create(data: $data);

        // TODO: needs return the measurement configuration.
        return $newConfigs;
    }

    public function validate(array $data) {
        $rules = [
            'measurements' => 'required|array|min:20|distinct',
            'measurements.*.measurement_item_id' => 'required|distinct'
        ];
        $rulesMessages = [
            'measurements.required' => 'Campo Medições é obrigatório.',
            'measurements.array' => 'Campo Medições está inválido.',
            'measurements.min' => 'Campo Medições deve conter no mínimo 20 itens de medição.',
            'measurements.distinct' => 'Campo Medições está inválido.',
            'measurements.*.measurement_item_id.required' => 'Campo Item de Medição é obrigatório.',
            'measurements.*.measurement_item_id.distinct' => 'Campo Item de Medição está duplicado.'
        ];
        $modelValidator = new ModelValidator($rules, $rulesMessages);
        $validation = $modelValidator->validate($data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        $billReceive = null;
        $amountProvided = 0;
        foreach ($data["measurements"] as $measurement) {
            $config = new MeasurementConfiguration($measurement);
            $config->validate(MeasurementConfiguration::$rules, MeasurementConfiguration::$rulesMessages);
            $billReceive = (new BillReceiveBusiness())->getById(id: $config->bill_receive_id);
            (new MeasurementItemBusiness())->getById(id: $config->measurement_item_id);
            $amountProvided += ($config->incidence * $billReceive->value/100);
        }

        if ($billReceive->value != $amountProvided) {
            $difference = floatval($billReceive->value) - floatval($amountProvided);
            throw new InputValidationException("Incidência dos valores informados difere do valor total da medição. Diferença: $difference");
        }

        $initialConfigs = $this->getByBillReceiveId(billReceiveId: $billReceive->id);
        if (count($initialConfigs) === 20) {
            throw new InputValidationException("Configuração da medição já existente. Não é possível criar uma outra.");
        }
    }
}