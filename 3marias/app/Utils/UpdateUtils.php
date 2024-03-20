<?php

namespace App\Utils;

use App\Models\BaseModel;

class UpdateUtils
{
    /**
     * Updates the specific fields allowed.
     */
    public static function processFieldsToBeUpdated($targetData, array $requestData, $fields) {
        foreach ($requestData as $key => $value) {
            if (in_array($key, $fields)) {
                $targetData[$key] = $value;
            }
        }
        return $targetData;
    }

    public static function updateFields(array $fieldsToBeUpdated, $model, $requestData) {
        foreach ($fieldsToBeUpdated as $field) {
            if (!isset($requestData[$field])) {
                $model[$field] = null;
            } else {
                $model[$field] = $requestData[$field];
            }
        }
        return $model;
    }

    public static function clearFields($targetData, $fields) {
        foreach ($fields as $key) {
            $targetData[$key] = "";
        }
        return $targetData;
    }

    public static function deleteFields($targetData, $fields) {
        foreach ($fields as $key) {
            if (isset($targetData[$key])) {
                unset($targetData[$key]);
            }
        }
        return $targetData;
    }

    public static function convertModelToArray(BaseModel $baseModel) {
        $encode = json_encode($baseModel);
        return json_decode($encode, true);
    }
}
