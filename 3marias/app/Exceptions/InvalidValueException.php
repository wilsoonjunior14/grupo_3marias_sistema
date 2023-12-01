<?php

namespace App\Exceptions;

use Exception;
use App\Models\Logger;
use App\Utils\ResponseUtils;

class InvalidValueException extends Exception
{
    public function render($request) {
         Logger::error($this->getMessage(), 400);
         return ResponseUtils::getResponse(
                ["message" => $this->getMessage()],
                400,
                ["Trace-ID" => Logger::getTraceId()]
         );
    }
}
