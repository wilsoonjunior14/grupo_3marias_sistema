<?php

namespace App\Exceptions;

use Exception;
use App\Models\Logger;
use App\Utils\ResponseUtils;

class MethodNotImplementedYet extends Exception
{
    public function render($request) {
         Logger::error($this->getMessage(), 404);
         return ResponseUtils::getResponse(
                ["message" => $this->getMessage()],
                404,
                ["Trace-ID" => Logger::getTraceId()]
         );
    }
}
