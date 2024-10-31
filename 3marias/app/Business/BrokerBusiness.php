<?php

namespace App\Business;

use App\Exceptions\InputValidationException;
use App\Models\Broker;
use App\Utils\ErrorMessage;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Http\Request;

class BrokerBusiness {

    public function __construct() {}

    public function get() {
        $brokers = (new Broker())->getAll("name");
        return $brokers;
    }

    public function getById(int $id, bool $mergeFields = true) {
        try {
            $broker = (new Broker())->getById($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $mnfe) {
            throw new InputValidationException(sprintf(ErrorMessage::$ENTITY_NOT_FOUND_PATTERN, "Corretor"));
        } 
        if ($mergeFields) {
            $address = (new AddressBusiness())->getById($broker->address_id, merge: true);
            $broker = $broker->mountAddressInline($broker, $address);
        }
        return $broker;
    }

    public function delete(int $id) {
        $broker = $this->getById(id: $id, mergeFields: false);
        $broker->deleted = true;
        $broker->save();
        return $broker;
    }

    public function create(Request $request) {
        $data = $request->all();

        $broker = new Broker($data);
        $broker->validate(rules: Broker::$rules, rulesMessages: Broker::$rulesMessages);
        $address = (new AddressBusiness())->create($data);
        
        $broker->address_id = $address->id;
        $broker->save();
        return $broker;
    }

    public function update(int $id, Request $request) {
        $data = $request->all();
        $broker = (new Broker())->getById($id);
        $brokerUpdated = UpdateUtils::processFieldsToBeUpdated($broker, $data, Broker::$fieldsToBeUpdated);

        $brokerValidator = new ModelValidator(Broker::$rules, Broker::$rulesMessages);
        $validation = $brokerValidator->validate(data: $request->all());
        if (!is_null($validation)) {
            throw new InputValidationException($validation);
        }
        (new AddressBusiness())->update($request->all(), id: $broker->address_id);

        $brokerUpdated->save();
        return $this->getById(id: $brokerUpdated->id);
    }

}