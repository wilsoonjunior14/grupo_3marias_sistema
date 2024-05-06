<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Logger;
use App\Models\Partner;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class PartnerBusiness {

    public function get() {
        Logger::info("Iniciando a recuperação de parceiros/fornecedores.");
        $partners = (new Partner())->getAll("fantasy_name");
        $amount = count($partners);
        Logger::info("Foram recuperados {$amount} parceiros/fornecedores.");
        Logger::info("Finalizando a recuperação de parceiros/fornecedores.");
        return $partners;
    }

    public function getById(int $id, bool $merge = false) {
        Logger::info("Iniciando a recuperação de parceiro/fornecedor $id.");
        try {
            $partner = (new Partner())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Parceiro/Fornecedor"));
        }
        Logger::info("Finalizando a recuperação de parceiro/fornecedor $id.");
        return $partner;
    }

    public function delete(int $id) {
        $partner = $this->getById(id: $id);
        Logger::info("Deletando o de parceiro/fornecedor $id.");
        $partner->deleted = true;
        $partner->save();
        return $partner;
    }

    public function create(Request $request) {
        Logger::info("Iniciando a criação de parceiro/fornecedor.");
        Logger::info("Validando as informações fornecidas.");
        $data = $request->all();

        $serviceValidator = new ModelValidator(Partner::$rules, Partner::$rulesMessages);
        $errors = $serviceValidator->validate(data: $data);
        if (!is_null($errors)) {
            throw new InputValidationException($errors);
        }
        if (isset($data["cnpj"]) || !empty($data["cnpj"])) {
            $this->existsEntity(cnpj: $data["cnpj"]);
        }
        
        Logger::info("Salvando o novo parceiro/fornecedor.");
        $partner = new Partner($data);
        $partner->save();
        Logger::info("Finalizando a atualização de parceiro/fornecedor.");
        return $partner;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do parceiro/fornecedor.");
        $data = $request->all();
        $partner = $this->getById($id);
        $partnerUpdated = UpdateUtils::processFieldsToBeUpdated($partner, $request->all(), Partner::$fieldsToBeUpdated);
        
        Logger::info("Validando as informações do parceiro/fornecedor.");
        $partnerValidator = new ModelValidator(Partner::$rules, Partner::$rulesMessages);
        $errors = $partnerValidator->validate(data: $data);
        if (!is_null($errors)) {
            throw new InputValidationException($errors);
        }
        $this->existsEntity(cnpj: $partnerUpdated["cnpj"], id: $id);

        Logger::info("Atualizando as informações do parceiro/fornecedor.");
        $partnerUpdated->save();
        return $this->getById(id: $partnerUpdated->id);
    }

    private function existsEntity(string $cnpj, int $id = null) {
        $condition = [["cnpj", "=", $cnpj]];
        $exists = (new Partner())->existsEntity(condition: $condition, id: $id);
        if ($exists) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_DUPLICATED, "CNPJ do Parceiro/Fornecedor", "Parceiro/Fornecedores"));
        }
        return $exists;
    }

}