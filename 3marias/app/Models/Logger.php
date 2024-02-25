<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    protected $table = "logs";
    protected $fillable = ["trace_id", "type", "message", "created_at", "updated_at", "timestamp", "statusCode"];

    static function error(string $message, int $statusCode): void {
        if (strcmp(env('APP_ENV'), 'testing') === 0) {
            return;
        }
        error_log($message);

        $log = new Logger();
        $log->type = "error";
        $log->message = $message;
        $log->trace_id = Logger::getTraceId();
        $log->timestamp = time();
        $log->statusCode = $statusCode;

        $log->save();
    }

    static function info(string $message): void {
        if (strcmp(env('APP_ENV'), 'testing') === 0) {
            return;
        }
        $log = new Logger();
        $log->type = "info";
        $log->message = $message;
        $log->trace_id = Logger::getTraceId();
        $log->timestamp = time();

        $log->save();
    }

    static function getTraceId() {
        $traceId = "";

        // if the traceId exists
        if (isset($_SESSION["traceId"]) && !empty($_SESSION["traceId"])) {
           $traceId = $_SESSION["traceId"];
        }

        // if the traceId does not exists
        if (!isset($_SESSION["traceId"])) {
            $_SESSION["traceId"] = "ID-" . rand(10000,100000) . "-" . time();
            $traceId = $_SESSION["traceId"];
        }

        return $traceId;
    }

    public function searchByTraceId(string $traceId) {
        return $this::where("trace_id", $traceId)
        ->orderBy("id")
        ->get();
    }

    public function get4XXLogsByPeriod(string $startDate, string $endDate) {
        return $this->getLogsQuery($startDate, $endDate, 400, 500);
    }

    public function get5XXLogsByPeriod(string $startDate, string $endDate) {
        return $this->getLogsQuery($startDate, $endDate, 500, 600);
    }

    private function getLogsQuery(string $startDate, string $endDate, int $startCode, int $endCode) {
        return $this->whereRaw(
            "(created_at >= ? AND created_at <= ?)", 
            [
               $startDate ." 00:00:00", 
               $endDate ." 23:59:59"
            ]
        )
        ->where("statusCode", ">=", $startCode)
        ->where("statusCode", "<", $endCode)
        ->orderBy("created_at")
        ->get();
    }
}
