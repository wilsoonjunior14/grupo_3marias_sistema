<?php

namespace App\Utils;
use App\Models\Logger;

class ResponseUtils
{
    public static function getResponse($data, $httpStatusCode) {
        return response()->json($data, $httpStatusCode, ["Trace-ID" => Logger::getTraceId()]);
    }

    public static function getErrorResponse(\Exception $e) {
        Logger::error($e->getMessage(), 500);
        return response()->json(
            [
                "message" => "Não foi possível processar as informações no servidor.", 
                "stack_trace" => $e->getMessage()
            ], 
            500, 
            ["Trace-ID" => Logger::getTraceId()]
        );
    }

    public static function getExceptionResponse(string $message, int $statusCode = 400) {
        return response()->json(["message" => $message], $statusCode, ["Trace-ID" => Logger::getTraceId()]);
    }
}
