<?php

namespace App\Utils;

use App\Models\BaseModel;
use Exception;
use App\Models\Logger;

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

    public static function clearFields($targetData, $fields) {
        foreach ($fields as $key) {
            $targetData[$key] = "";
        }
        return $targetData;
    }
}
