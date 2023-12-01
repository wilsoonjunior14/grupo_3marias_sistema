<?php

namespace App\Utils;

use Exception;
use App\Models\Logger;

class ResponseUtils
{
    public static function getResponse($data, $httpStatusCode) {
        return response()->json($data, $httpStatusCode, ["Trace-ID" => Logger::getTraceId()]);
    }
}
