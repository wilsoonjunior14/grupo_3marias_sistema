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
        return $this::where("deleted", false)
        ->orderBy($orderBy)
        ->get();
    }

    /**
     * Retrieves an entity by id
     */
    public function getById($id) {
        return $this
        ::where("deleted", false)
        ->where("id", $id)
        ->get()
        ->first();
    }

    /**
     * Checks if exists an entity based on query
     */
    public function existsEntity(array $condition, int $id = null) {
        $condition[] = ["deleted", "=", false];

        if (!is_null($id)) {
            $condition[] = ["id", "!=", $id];
        }

        $entities = $this::
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
}
