<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Engineer;
use App\Models\Logger;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use Illuminate\Http\Request;

class EngineerBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de engenheiros.");
        $engineers = (new Engineer())->getAll("name");
        $amount = count($engineers);
        Logger::info("Foram recuperados {$amount} engenheiros.");
        Logger::info("Finalizando a recuperação de engenheiros.");
        return $engineers;
    }

    public function getById(int $id) {
        Logger::info("Iniciando a recuperação de engenheiro $id.");
        if ($id <= 0) {
            throw new InputValidationException(sprintf(ErrorMessage::$ID_NOT_EXISTS, "Engenheiro"));
        }
        $engineer = (new Engineer())->getById($id);
        if (is_null($engineer)) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Engenheiro"));
        }
        Logger::info("Finalizando a recuperação de engenheiro $id.");
        return $engineer;
    }

    public function delete(int $id) {
        $engineer = $this->getById(id: $id);
        Logger::info("Deletando o engenheiro $id.");
        $engineer->deleted = true;
        $engineer->save();
        return $engineer;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de engenheiro.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $engineer = new Engineer($data);
        $engineer->validate(rules: Engineer::$rules, rulesMessages: Engineer::$rulesMessages);
        $this->existsEntity(name: $data["name"]);
        
        Logger::info("Salvando o novo engenheiro.");
        $engineer->save();
        Logger::info("Finalizando a atualização de engenheiro.");
        return $engineer;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do engenheiro.");
        Logger::info("Validando as informações fornecidas.");
        $engineer = $this->getById($id);
        $newEngineer = new Engineer($request->all());
        $newEngineer->validate(rules: Engineer::$rules, rulesMessages: Engineer::$rulesMessages);

        $engineerUpdated = UpdateUtils::processFieldsToBeUpdated($engineer, $request->all(), Engineer::$fieldsToBeUpdated);
        $this->existsEntity(name: $engineerUpdated["product"], id: $id);

        Logger::info("Atualizando as informações do engenheiro.");
        $engineerUpdated->save();
        return $this->getById(id: $engineerUpdated->id);
    }

    private function existsEntity(string $name, int $id = null) {
        $condition = [["name", "like", "%" . $name . "%"]];
        $exists = (new Engineer())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "Nome da Engenheiro", "Engenheiros"));
        }
        return $exists;
    }

}