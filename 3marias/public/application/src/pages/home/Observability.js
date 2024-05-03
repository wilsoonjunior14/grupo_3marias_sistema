import React, { useEffect, useState } from "react";
import "../../App.css";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import { performRequest } from '../../services/Api';
import "./Home.css";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut, Line, Pie } from 'react-chartjs-2';
import { CategoryScale } from "chart.js";
import { registerables} from 'chart.js';
import { formatHour } from "../../services/Format";

ChartJS.register(...registerables);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(CategoryScale);

export default function Observability() {

    const [loadingMetrics, setLoadingMetrics] = useState(false);
    const initialMetrics = {
        labels: [],
        datasets: []
    };
    const [errorMetrics, setErrorMetrics] = useState(initialMetrics);
    const [cpuMetrics, setCPUMetrics] = useState(initialMetrics);
    const [cpuAverage, setCPUAverage] = useState(null);
    const [memoryMetrics, setMemoryMetrics] = useState(initialMetrics);
    const [memoryAverage, setMemoryAverage] = useState(null);
    const [diskMetrics, setDiskMetrics] = useState(initialMetrics);
    const [diskAverage, setDiskAverage] = useState(null);

    const onGetMetrics = () => {
        setLoadingMetrics(true);

        performRequest("GET", "/v1/observability/metrics")
        .then(successPost)
        .catch(errorResponse);
    }

    const successPost = (res) => {
        setLoadingMetrics(false);

        const months = res.data.errors_metrics.items.map((item) => item.description);
        const FourXXErrors = res.data.errors_metrics.items.map((item) => item.fourHundredErrors.count);
        const FiveXXErrors = res.data.errors_metrics.items.map((item) => item.fiveHundredErrors.count);

        setErrorMetrics({
            labels: months,
            datasets: [
                {
                label: 'Erros 4XX',
                data: FourXXErrors,
                fill: false,
                borderColor: 'rgba(0, 99, 255, 0.5)',
                tension: 0.1
                },
                {
                    label: 'Error 5XX',
                    data: FiveXXErrors,
                    fill: false,
                    borderColor: 'rgba(255, 99, 90, 0.5)',
                    tension: 0.1
                }
            ]
        });

        mountCPUUsageMetrics(res);
        mountMemoryUsageMetrics(res);
        mountDiskSpaceMetrics(res);
    }

    const errorResponse = (err) => {
        setLoadingMetrics(false);
    }

    const mountCPUUsageMetrics = (res) => {
        const metrics = mountMetrics("CPU %", "cpu_usage", res);
        setCPUMetrics(metrics.chartData);
        setCPUAverage(metrics.average);
    }

    const mountMemoryUsageMetrics = (res) => {
        const metrics = mountMetrics("Uso de Memória %", "memory_usage", res);
        setMemoryMetrics(metrics.chartData);
        setMemoryAverage(metrics.average);
    }

    const mountDiskSpaceMetrics = (res) => {
        const metrics = mountMetrics("Espaço Livre em Disco %", "disk_free_space", res);
        setDiskMetrics(metrics.chartData);
        setDiskAverage(metrics.average);
    }

    const mountMetrics = (label, fieldName, res) => {
        const metricData = res.data.server_metrics.filter((item) => item.metric_name === fieldName);
        const labels = metricData.map((item) => formatHour(item.created_at));
        const data = metricData.map((item) => item.metric_value);

        let total = 0;
        data.forEach(element => {
            total += parseFloat(element);
        });
        return {
            average: total / data.length,
            chartData: {
                labels: labels,
                datasets: [
                    {
                    label: label,
                    data: data,
                    fill: false,
                    borderColor: 'rgba(0, 99, 255, 0.5)',
                    tension: 0.1
                    }
                ]
            }
        };
    }

    useEffect(() => {
        onGetMetrics();
    }, []);

    return (
        <>
            {!loadingMetrics &&
            <Row>
                {cpuAverage !== null &&
                <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Doughnut
                                height={250}
                                width={250}
                                data={{
                                    labels: [
                                      'Utilizado',
                                      'Disponível',
                                    ],
                                    datasets: [{
                                      label: 'CPU % Média',
                                      data: [cpuAverage, 100 - cpuAverage],
                                      backgroundColor: [
                                        'rgba(255, 99, 90, 0.5)',
                                        'rgba(54, 162, 0, 0.5)',
                                      ],
                                      hoverOffset: 4
                                    }]
                                  }}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "CPU"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
                {memoryAverage !== null &&
                <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Doughnut
                                height={250}
                                width={250}
                                data={{
                                    labels: [
                                      'Utilizado',
                                      'Disponível',
                                    ],
                                    datasets: [{
                                      label: 'Memória % Média',
                                      data: [memoryAverage, 100 - memoryAverage],
                                      backgroundColor: [
                                        'rgba(255, 99, 90, 0.5)',
                                        'rgba(54, 162, 0, 0.5)',
                                      ],
                                      hoverOffset: 4
                                    }]
                                  }}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Memória"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
                {diskAverage !== null &&
                <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Doughnut
                                height={250}
                                width={250}
                                data={{
                                    labels: [
                                      'Utilizado',
                                      'Disponível',
                                    ],
                                    datasets: [{
                                      label: 'Espaço em Disco % Média',
                                      data: [diskAverage, 100 - diskAverage],
                                      backgroundColor: [
                                        'rgba(255, 99, 90, 0.5)',
                                        'rgba(54, 162, 0, 0.5)',
                                      ],
                                      hoverOffset: 4
                                    }]
                                  }}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Disco"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
                {cpuAverage !== null &&
                <Col xs={12} lg={6}>
                    <Card>
                        <Card.Body>
                            <Line
                                height={250}
                                width={250}
                                data={cpuMetrics}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Uso de CPU"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
                {memoryMetrics &&
                <Col xs={12} lg={6}>
                    <Card>
                        <Card.Body>
                            <Line
                                height={250}
                                width={250}
                                data={memoryMetrics}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Uso de Memória"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
                {diskMetrics &&
                <Col xs={12} lg={6}>
                    <Card>
                        <Card.Body>
                            <Line
                                height={250}
                                width={250}
                                data={diskMetrics}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Espaço Livre em Disco"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
                {errorMetrics && errorMetrics.labels.length > 0 &&
                <Col xs={12} lg={6}>
                    <Card>
                        <Card.Body>
                            <Line
                                height={250}
                                width={250}
                                data={errorMetrics}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Gráfico de Erros"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
            </Row>
            }
        </>
    );
};