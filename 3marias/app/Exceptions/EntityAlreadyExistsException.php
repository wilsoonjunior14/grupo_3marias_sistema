<?php

namespace App\Exceptions;

use App\Models\Logger;
use App\Utils\ResponseUtils;

class EntityAlreadyExistsException extends AppException
{
    public function render($request) {
         Logger::error($this->getMessage(), 400);
         return ResponseUtils::getResponse(["message" => $this->getMessage()], 400);
    }
}
