<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Address;
use App\Models\Logger;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;

class AddressBusiness {

    public function create(array $data) {
        Logger::info("Iniciando a criação de endereço.");
        Logger::info("Validando as informações fornecidas.");

        $this->validateData(data: $data);
        
        Logger::info("Salvando a nova endereço.");
        $address = new Address($data);
        $address->save();
        Logger::info("Finalizando a atualização de endereço.");
        return $address;
    }

    public function update(array $data, int $id) {
        Logger::info("Iniciando a criação de endereço.");
        Logger::info("Validando as informações fornecidas.");

        $this->validateData(data: $data);
        
        Logger::info("Salvando o novo endereço.");
        $address = $this->getById(id: $id, merge: false);
        $address = UpdateUtils::processFieldsToBeUpdated($address, $data, Address::$fieldsToBeUpdated);

        $address->save();
        Logger::info("Finalizando a atualização de endereço.");
        return $address;
    }

    public function getById(int $id, bool $merge = true) {
        Logger::info("Recuperando endereço.");
        Logger::info("Salvando a nova endereço.");
        $address = (new Address())->getById($id);

        if ($merge) {
            $city = (new CityBusiness())->getById($address->city_id);
            $address["city_name"] = $city["city_name"];
            $address["state_name"] = $city["state_name"];
        }

        Logger::info("Finalizando a recuperação de endereço.");
        return $address;
    }

    public function validateData(array $data, int $id = null) {
        $validator = new ModelValidator(Address::$rules, Address::$rulesMessages);
        $addressValidation = $validator->validate($data);
        if (!is_null($addressValidation)) {
            throw new InputValidationException($addressValidation);
        }
    }

}