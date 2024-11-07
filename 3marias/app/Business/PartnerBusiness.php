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
            if (!is_null($partner->address_id)) {
                $address = (new AddressBusiness())->getById(id: $partner->address_id);
                $partner = $partner->mountAddressInline($partner, $address);
            }
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
        $data = $request->all();
        $addressBusiness = new AddressBusiness();

        $addressBusiness->validateData($data);
        $serviceValidator = new ModelValidator(Partner::$rules, Partner::$rulesMessages);
        $errors = $serviceValidator->validate(data: $data);
        if (!is_null($errors)) {
            throw new InputValidationException($errors);
        }
        if (isset($data["cnpj"]) || !empty($data["cnpj"])) {
            $this->existsEntity(cnpj: $data["cnpj"]);
        }

        $address = $addressBusiness->create($data);
        $partner = new Partner($data);
        $partner->address_id = $address->id;
        $partner->save();
        return $partner;
    }

    public function update(int $id, Request $request) {
        Logger::info("Alterando informações do contrato.");
        $data = $request->all();
        $partner = (new Partner())->getById($id);
        $partnerUpdated = UpdateUtils::processFieldsToBeUpdated($partner, $data, Partner::$fieldsToBeUpdated);

        $validator = new ModelValidator(Partner::$rules, Partner::$rulesMessages);
        $validation = $validator->validate(data: $data);
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }

        Logger::info("Atualizando as informações de endereço.");
        (new AddressBusiness())->update($request->all(), id: $partner->address_id);

        Logger::info("Atualizando as informações do contrato.");
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