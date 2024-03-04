<?php

namespace App\Http\Controllers;

use App\Exceptions\InputValidationException;
use App\Models\Enterprise;
use App\Models\Logger;
use App\Models\ServerMetric;
use App\Models\User;
use App\Utils\ResponseUtils;
use Illuminate\Http\Request;

class ObservabilityController extends Controller
{
    public function __construct(){
        $startTime = date('d/m/Y H:i:s');
        Logger::info("Iniciando o GroupController em {$startTime}.");
    }

    /**
     * Gets all metrics system.
     */
    public function getMetrics() {
        Logger::info("Coletando métricas do sistema.");

        $this->storeServerMetrics();
        $usersMetrics = $this->getUsers();
        $enterprisesMetrics = $this->getEnterprises();
        $errorsMetrics = $this->getErrorsInfo();

        $today = date('Y-m-d');
        $serverMetrics = (new ServerMetric())->getMetrics($today);

        $metrics = [
            "server_metrics" => $serverMetrics,
            "user_metrics" => $usersMetrics,
            "enterprise_metrics" => $enterprisesMetrics,
            "errors_metrics" => $errorsMetrics
        ];

        Logger::info("Finalizando coleta de métricas do sistema.");
        return ResponseUtils::getResponse($metrics, 200);
    }

    public function getLogs(Request $request) {
        Logger::info("Iniciando a recuperação de logs.");

        $data = $request->all();
        if (!isset($data["trace_id"]) || empty($data["trace_id"])) {
            throw new InputValidationException("O Campo TRACE-ID é obrigatório.");
        }

        $logs = (new Logger())->searchByTraceId($data["trace_id"]);
        $logsCount = count($logs);

        Logger::info("{$logsCount} logs foram encontrados.");
        return ResponseUtils::getResponse($logs, 200);
    }

    /**
     * Creates a metric based on percentage.
     */
    private function createMetricPercentage(string $metricName, string $metricValue) {
        $value = floatval(str_replace("%", "", $metricValue));
        $this->createMetric(metricName: $metricName, metricValue: $value);
    }

    /**
     * Creates a metric based on GB.
     */
    private function createMetricGB(string $metricName, string $metricValue) {
        $value = preg_replace('/^.+\(/i', '', $metricValue);
        $value = floatval(str_replace('GB)', '', $value));
        $this->createMetric(metricName: $metricName, metricValue: $value);
    }

    /**
     * Creates a metric inside database.
     */
    private function createMetric(string $metricName, float $metricValue) {
        $metric = new ServerMetric();
        $metric->metric_name = $metricName;
        $metric->metric_value = $metricValue;
        $metric->save();
    }

    /**
     * Storing server metrics.
     */
    public function storeServerMetrics() {
        Logger::info("Salvando métricas do sistema.");
        $output = shell_exec("php artisan stethoscope:listen");
        $serverMetrics = explode("\n", str_replace(" ", "",$output));

        foreach ($serverMetrics as $key) {
            $metrics = explode("===>", $key);
            if (count($metrics) === 2) {
                match($metrics[0]) {
                    "cpuusage" => $this->createMetricPercentage(metricName: "cpu_usage", metricValue: $metrics[1]),
                    "memoryusage" => $this->createMetricPercentage(metricName: "memory_usage", metricValue: $metrics[1]),
                    "harddiskfreespace" => $this->createMetricGB(metricName: "disk_free_space", metricValue: $metrics[1]),
                    default => $nothing=0
                };
            }
        }
    }

    /**
     * Gets users metrics.
     */
    public function getUsers() {
        Logger::info("Recuperando quantidade de usuários por período.");

        $year = date('Y');
        $items = [
            [
                "id" => 1,
                "description" => "Jan - Mar",
                "startDate" => "{$year}-01-01",
                "endDate" => "{$year}-03-31",
                "count" => 0
            ],
            [
                "id" => 2,
                "description" => "Abr - Jun",
                "startDate" => "{$year}-04-01",
                "endDate" => "{$year}-06-30",
                "count" => 0
            ],
            [
                "id" => 3,
                "description" => "Jul - Set",
                "startDate" => "{$year}-07-01",
                "endDate" => "{$year}-09-31",
                "count" => 0
            ],
            [
                "id" => 4,
                "description" => "Out - Dez",
                "startDate" => "{$year}-10-01",
                "endDate" => "{$year}-12-31",
                "count" => 0
            ],
        ];

        Logger::info("Realizando busca de usuários.");
        $users = (new User())->getUsersByPeriod("{$year}-01-01", "{$year}-12-31");

        Logger::info("Calculando quantidade de usuários criados por trimestre.");
        $responseData = [];
        foreach ($items as $item) {
            $startTimestamp = (new \DateTime($item["startDate"]))->getTimestamp();
            $endTimestamp = (new \DateTime($item["endDate"]))->getTimestamp();

            foreach ($users as $user) {
                $userTimestamp = (new \DateTime($user->created_at))->getTimestamp();
                if ($userTimestamp >= $startTimestamp && $userTimestamp <= $endTimestamp) {
                    $item["count"] = $item["count"] + 1;
                }
            }

            $responseData[] = $item;
        }

        Logger::info("Retornando os dados calculados.");
        return $responseData;
    }

    /**
     * Gets enterprises metrics.
     */
    public function getEnterprises() {
        Logger::info("Iniciando a recuperação de empresas.");

        $enterprises = (new Enterprise())->getAll("name");
        $responseData = [
            "waiting" => 0,
            "active" => 0,
            "inactive" => 0
        ];

        Logger::info("Processando estatísticas das empresas.");
        foreach ($enterprises as $enterprise) {
            if (strcmp($enterprise["status"], "waiting") === 0) {
                $responseData["waiting"] = $responseData["waiting"] + 1;
            } else if (strcmp($enterprise["status"], "active") === 0) {
                $responseData["active"] = $responseData["active"] + 1;
            } else if (strcmp($enterprise["status"], "inactive") === 0) {
                $responseData["inactive"] = $responseData["inactive"] + 1;
            }
        }

        Logger::info("Concluindo a recuperação de informações de empresas.");
        return $responseData;
    }

    /**
     * Gets errors metrics
     */
    public function getErrorsInfo() {
        Logger::info("Recuperando 4XX e 5XX logs.");

        $year = date('Y');
        $items = [
            [
                "id" => 1,
                "sizeDays" => 31,
                "description" => "Jan",
                "startDate" => "{$year}-01-01",
                "endDate" => "{$year}-01-31"
            ],
            [
                "id" => 2,
                "sizeDays" => 28,
                "description" => "Fev",
                "startDate" => "{$year}-02-01",
                "endDate" => "{$year}-02-28"
            ],
            [
                "id" => 3,
                "sizeDays" => 31,
                "description" => "Mar",
                "startDate" => "{$year}-03-01",
                "endDate" => "{$year}-03-31"
            ],
            [
                "id" => 4,
                "sizeDays" => 30,
                "description" => "Abr",
                "startDate" => "{$year}-04-01",
                "endDate" => "{$year}-04-30"
            ],
            [
                "id" => 5,
                "sizeDays" => 31,
                "description" => "Mai",
                "startDate" => "{$year}-05-01",
                "endDate" => "{$year}-05-31"
            ],
            [
                "id" => 6,
                "description" => "Jun",
                "sizeDays" => 30,
                "startDate" => "{$year}-06-01",
                "endDate" => "{$year}-06-30"
            ],
            [
                "id" => 7,
                "description" => "Jul",
                "sizeDays" => 31,
                "startDate" => "{$year}-07-01",
                "endDate" => "{$year}-07-31"
            ],
            [
                "id" => 8,
                "sizeDays" => 31,
                "description" => "Ago",
                "startDate" => "{$year}-08-01",
                "endDate" => "{$year}-08-31"
            ],
            [
                "id" => 9,
                "description" => "Set",
                "sizeDays" => 30,
                "startDate" => "{$year}-09-01",
                "endDate" => "{$year}-09-30"
            ],
            [
                "id" => 10,
                "sizeDays" => 31,
                "description" => "Out",
                "startDate" => "{$year}-10-01",
                "endDate" => "{$year}-10-31"
            ],
            [
                "id" => 11,
                "sizeDays" => 30,
                "description" => "Nov",
                "startDate" => "{$year}-11-01",
                "endDate" => "{$year}-11-30"
            ],
            [
                "id" => 12,
                "sizeDays" => 31,
                "description" => "Dez",
                "startDate" => "{$year}-12-01",
                "endDate" => "{$year}-12-31"
            ],
        ];

        $logInstance = new Logger();
        $countAll4Errors = 0;
        $countAll5Errors = 0;
        $responseData = [
            "currentMonth" => intval(date('m')),
            "currentYear" => $year,
            "items" => []
        ];

        foreach ($items as $item) {
            $fourHundredErrors = $logInstance->get4XXLogsByPeriod($item["startDate"], $item["endDate"]);
            $countFourHundredErrors = count($fourHundredErrors);
            $item["fourHundredErrors"] = [
                "count" => $countFourHundredErrors,
                "logs" => $fourHundredErrors
            ];

            $fiveHundredErrors = $logInstance->get5XXLogsByPeriod($item["startDate"], $item["endDate"]);
            $countfiveHundredErrors = count($fiveHundredErrors);
            $item["fiveHundredErrors"] = [
                "count" => $countfiveHundredErrors,
                "logs" => $fiveHundredErrors
            ];

            $countAll4Errors += $item["fourHundredErrors"]["count"];
            $countAll5Errors += $item["fiveHundredErrors"]["count"];
            $responseData["items"][] = $item;
        }

        Logger::info("Foram encontrados {$countAll4Errors} 4XX logs.");
        Logger::info("Foram encontrados {$countAll5Errors} 5XX logs.");
        return $responseData;
    }
    
}
