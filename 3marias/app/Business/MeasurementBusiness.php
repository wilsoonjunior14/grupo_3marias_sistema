<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\Measurement;
use App\Utils\ErrorMessage;
use App\Validation\ModelValidator;

class MeasurementBusiness {

    public function getById($id, bool $mergeFields = false) {
        Logger::info("Iniciando a recuperação do pagamento.");
        try {
            $bill = (new Measurement())->getById(id: $id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Medição"));
        }
        if (!$mergeFields) {
            Logger::info("Finalizando a recuperação do pagamento.");
            return $bill;
        }
        return $bill;
    }

    public function getByReceiveId(int $billReceiveId) {
        Logger::info("Iniciando a recuperação das medições.");
        $bills = (new Measurement())->getByBillReceiveId(billReceiveId: $billReceiveId);
        Logger::info("Finalizando a recuperação das medições.");
        return $bills;
    }

    public function delete(int $id) {
        $config = $this->getById(id: $id);
        Logger::info("Deletando medição $id.");
        $config->deleted = true;
        $config->save();

        Logger::info("Atualizando conta a receber.");
        (new BillReceiveBusiness())->refreshBillReceiveMeasurements(id: $config->bill_receive_id);
        return $config;
    }

    public function create(array $data) {
        Logger::info("Iniciando a criação da medição inicial.");
        Logger::info("Validando a medição inicial.");

        $this->validate(data: $data);
        $billReceiveId = $data["measurements"][0]["bill_receive_id"];
        $measurementId = 1 + (count($this->getByReceiveId($billReceiveId)) / 20);

        Logger::info("Salvando as medições.");
        $measurements = [];
        foreach ($data["measurements"] as $measurement) {
            $measurement = new Measurement($measurement);
            $measurement->number = $measurementId;
            $measurement->save();
            $measurements[] = $measurement;
        }
        
        Logger::info("Atualizando conta a receber.");
        (new BillReceiveBusiness())->refreshBillReceiveMeasurements(id: $billReceiveId);

        Logger::info("Finalizando a criação da medição inicial.");
        return $measurements;
    }

    private function validate(array $data) {
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
        $measurementNumber = 0;
        $measurements = [];
        foreach ($data["measurements"] as $measurement) {
            $measurement = new Measurement($measurement);
            $measurement->validate(Measurement::$rules, Measurement::$rulesMessages);
            $billReceive = (new BillReceiveBusiness())->getById(id: $measurement->bill_receive_id);
            (new MeasurementItemBusiness())->getById(id: $measurement->measurement_item_id);
            $measurementNumber = $measurement->number;
            $measurements[] = $measurement;
        }

        $measurementConfiguration = (new MeasurementConfigurationBusiness())->getByBillReceiveId(billReceiveId: $billReceive->id);
        if (is_null($measurementConfiguration) || empty($measurementConfiguration) || count($measurementConfiguration) === 0) {
            throw new InputValidationException("Operação não permitida. Configuração da medição inicial não definida.");
        }

        $existingMeasurements = $this->getByReceiveId(billReceiveId: $billReceive->id);
        foreach ($measurements as $measurement) {
            $this->validateMeasurements(configurations: $measurementConfiguration, 
                measurements: $existingMeasurements, 
                measurementToAdd: $measurement,
                measurementNumber: $measurementNumber);
        }
    }

    private function validateMeasurements($configurations, $measurements, Measurement $measurementToAdd, $measurementNumber) {
        foreach ($configurations as $config) {
            if ($measurementToAdd->measurement_item_id == $config->measurement_item_id) {
                $sumIncidence = $this->getMeasurement(measurements: $measurements, 
                    billReceiveId: $config->bill_receive_id, 
                    measurementItemId: $config->measurement_item_id,
                    measurementNumber: $measurementNumber);
                if ($sumIncidence + $measurementToAdd->incidence > $config->incidence) {
                    throw new InputValidationException("O valor de incidência do item de medição '{$measurementToAdd->measurement_item_id}' está inválido. Valor ultrapassa o valor definido.");
                }
            }
        }
    }

    private function getMeasurement($measurements, int $billReceiveId, int $measurementItemId, int $measurementNumber) {
        $incidence = 0;
        foreach ($measurements as $measurement) {
            if ($measurement->number === $measurementNumber) {
                throw new InputValidationException("Número da medição inválido.");
            }
            if ($measurement->bill_receive_id == $billReceiveId && $measurement->measurement_item_id == $measurementItemId) {
                $incidence = $incidence + $measurement->incidence;
            }
        }
        return $incidence;
    }
}