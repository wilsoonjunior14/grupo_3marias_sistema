<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\ContractModel;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class ContractModelBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de modelos de contrato.");
        $models = (new ContractModel())->getAll("name");
        $amount = count($models);
        Logger::info("Foram recuperados {$amount} modelos de contrato.");
        Logger::info("Finalizando a recuperação de modelos de contrato.");
        return $models;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de modelo de contrato $id.");
        $model = (new ContractModel())->getById($id);
        Logger::info("Finalizando a recuperação de modelo de contrato $id.");
        return $model;
    }

    public function delete(int $id) {
        $model = $this->getById(id: $id);
        Logger::info("Deletando o modelo $id.");
        $model->deleted = true;
        $model->save();
        return $model;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de modelo.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $modelValidator = new ModelValidator(ContractModel::$rules, ContractModel::$rulesMessages);
        $hasErrors = $modelValidator->validate(data: $data);
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
        
        Logger::info("Salvando a nova modelo.");
        $model = new ContractModel($data);
        $model->save();
        Logger::info("Finalizando a atualização de modelo.");
        return $model;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do modelo.");
        $model = (new ContractModel())->getById($id);
        $modelUpdated = UpdateUtils::processFieldsToBeUpdated($model, $request->all(), ContractModel::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do modelo.");
        $modelValidator = new ModelValidator(ContractModel::$rules, ContractModel::$rulesMessages);
        $modelValidator->validate(data: $request->all());

        Logger::info("Atualizando as informações do modelo.");
        $modelUpdated->save();
        return $this->getById(id: $modelUpdated->id);
    }
}