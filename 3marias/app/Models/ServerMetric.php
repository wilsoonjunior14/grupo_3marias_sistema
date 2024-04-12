<?php

namespace App\Models;

class ServerMetric extends BaseModel
{
    protected $table = "server_metrics";
    protected $fillable = ["id", "metric_name", "metric_value", "created_at", "updated_at"];

    /**
     * Gets metrics by specific date.
     * @return array The array of the metrics of the speficif day.
     */
    public function getMetrics(string $date) {
        return (new ServerMetric())->whereRaw(
            "(created_at >= ? AND created_at <= ?)", 
            [
               $date ." 00:00:00", 
               $date ." 23:59:59"
            ]
        )
        ->orderBy("created_at")
        ->get();
    }
}
