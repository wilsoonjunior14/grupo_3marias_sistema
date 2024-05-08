<?php

namespace App\Models;

use App\Exceptions\InputValidationException;
use App\Utils\UpdateUtils;
use App\Validation\ModelValidator;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    /**
     * Retrieves all non-deleted entities ordered by column. 
     */
    public function getAll(string $orderBy) {
        return $this::where("deleted", false) // @phpstan-ignore-line
        ->orderBy($orderBy)
        ->get();
    }

    /**
     * Retrieves an entity by id
     */
    public function getById(int $id) {
        try {
            return $this // @phpstan-ignore-line
            ::where("deleted", false)
            ->where("id", $id)
            ->get()
            ->firstOrFail();
        } catch (\Exception $e) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException($e->getMessage(), 400);
        }
    }

    /**
     * Checks if exists an entity based on query
     */
    public function existsEntity(array $condition, int $id = null) {
        $condition[] = ["deleted", "=", false];

        if (!is_null($id)) {
            $condition[] = ["id", "!=", $id];
        }

        $entities = $this:: // @phpstan-ignore-line
        where($condition)
        ->get();

        return count($entities) > 0;
    }

    /**
     * Checks if the model validation is ok.
     */
    public function validate(array $rules, array $rulesMessages) {
        $modelValidator = new ModelValidator($rules, $rulesMessages);
        $hasErrors = $modelValidator->validate(data: UpdateUtils::convertModelToArray(baseModel: $this));
        if (!is_null($hasErrors)) {
            throw new InputValidationException($hasErrors);
        }
    }

    /**
     * Mounts the address inline.
     */
    public function mountAddressInline(BaseModel $model, Address $address) {
        $model["address"] = $address->address;
        $model["neighborhood"] = $address->neighborhood;
        $model["number"] = $address->number;
        $model["complement"] = $address->complement;
        $model["city_id"] = $address->city_id;
        $model["zipcode"] = $address->zipcode;
        $model["city_name"] = $address->city_name;
        $model["state_name"] = $address->state_name;
        $model["state_acronym"] = $address->state_acronym;
        return $model;
    }
}
