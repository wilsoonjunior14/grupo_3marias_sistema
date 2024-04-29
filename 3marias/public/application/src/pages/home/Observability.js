import React, { useEffect, useState } from "react";
import "../../App.css";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import Loading from "../../components/loading/Loading";
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
    const [errorMetrics, setErrorMetrics] = useState({
        labels: ['Erro 4XX', 'Erro 5XX'],
        datasets: [
            {
            label: 'My First Dataset',
            data: [0, 0],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
            },
            {
                label: 'My Second Dataset',
                data: [0, 0],
                fill: false,
                borderColor: 'rgb(0, 0, 155)',
                tension: 0.1
            }
        ]
    });
    const [cpuMetrics, setCPUMetrics] = useState({
        labels: ['Erro 4XX', 'Erro 5XX'],
        datasets: [
            {
            label: 'My First Dataset',
            data: [0, 0],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
            },
            {
                label: 'My Second Dataset',
                data: [0, 0],
                fill: false,
                borderColor: 'rgb(0, 0, 155)',
                tension: 0.1
            }
        ]
    });

    const [memoryMetrics, setMemoryMetrics] = useState({
        labels: ['Erro 4XX', 'Erro 5XX'],
        datasets: [
            {
            label: 'My First Dataset',
            data: [0, 0],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
            },
            {
                label: 'My Second Dataset',
                data: [0, 0],
                fill: false,
                borderColor: 'rgb(0, 0, 155)',
                tension: 0.1
            }
        ]
    });

    const [diskMetrics, setDiskMetrics] = useState({
        labels: ['Erro 4XX', 'Erro 5XX'],
        datasets: [
            {
            label: 'My First Dataset',
            data: [0, 0],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
            },
            {
                label: 'My Second Dataset',
                data: [0, 0],
                fill: false,
                borderColor: 'rgb(0, 0, 155)',
                tension: 0.1
            }
        ]
    });

    const data = {
        labels: ['Erro 4XX', 'Erro 5XX'],
        datasets: [
            {
            label: 'My First Dataset',
            data: [1, 10],
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
            },
            {
                label: 'My Second Dataset',
                data: [5, 35],
                fill: false,
                borderColor: 'rgb(0, 0, 155)',
                tension: 0.1
            }
        ]
    };

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
        const cpuData = res.data.server_metrics.filter((item) => item.metric_name === "cpu_usage");
        const labels = cpuData.map((item) => formatHour(item.created_at));
        const data = cpuData.map((item) => item.metric_value);
        setCPUMetrics({
            labels: labels,
            datasets: [
                {
                label: 'CPU %',
                data: data,
                fill: false,
                borderColor: 'rgba(0, 99, 255, 0.5)',
                tension: 0.1
                }
            ]
        });
    }

    const mountMemoryUsageMetrics = (res) => {
        const memoryData = res.data.server_metrics.filter((item) => item.metric_name === "memory_usage");
        const labels = memoryData.map((item) => formatHour(item.created_at));
        const data = memoryData.map((item) => item.metric_value);
        setMemoryMetrics({
            labels: labels,
            datasets: [
                {
                label: 'Uso de Memória %',
                data: data,
                fill: false,
                borderColor: 'rgba(0, 99, 255, 0.5)',
                tension: 0.1
                }
            ]
        });
    }

    const mountDiskSpaceMetrics = (res) => {
        const memoryData = res.data.server_metrics.filter((item) => item.metric_name === "disk_free_space");
        const labels = memoryData.map((item) => formatHour(item.created_at));
        const data = memoryData.map((item) => item.metric_value);
        setDiskMetrics({
            labels: labels,
            datasets: [
                {
                label: 'Espaço Livre em Disco %',
                data: data,
                fill: false,
                borderColor: 'rgba(255, 99, 90, 0.5)',
                tension: 0.1
                }
            ]
        });
    }

    useEffect(() => {
        onGetMetrics();
    }, []);

    return (
        <>
            {!loadingMetrics &&
            <Row>
                <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Doughnut
                                height={250}
                                width={250}
                                data={{
                                    labels: [
                                      'Red',
                                      'Green',
                                    ],
                                    datasets: [{
                                      label: 'CPU % Média',
                                      data: [0.77, 100 - 0.77],
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
                <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Doughnut
                                height={250}
                                width={250}
                                data={{
                                    labels: [
                                      'Red',
                                      'Green',
                                    ],
                                    datasets: [{
                                      label: 'Memória % Média',
                                      data: [22.5, 100 - 22.5],
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
                <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Doughnut
                                height={250}
                                width={250}
                                data={{
                                    labels: [
                                      'Red',
                                      'Green',
                                    ],
                                    datasets: [{
                                      label: 'Espaço em Disco % Média',
                                      data: [47.5, 100 - 47.5],
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
            </Row>
            }
        </>
    );
};