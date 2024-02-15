<?php

namespace App\Utils;

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
}
