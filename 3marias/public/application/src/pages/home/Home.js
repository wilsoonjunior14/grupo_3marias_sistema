import React, { useEffect, useState } from "react";
import VHeader from "../../components/vHeader/vHeader";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import { CategoryScale } from "chart.js";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Bar, Line, Pie } from 'react-chartjs-2';
import { registerables} from 'chart.js';
import "../../App.css";
import { performRequest } from '../../services/Api';
import Loading from "../../components/loading/Loading";

ChartJS.register(...registerables);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(CategoryScale);

export default function Home() {

    const data = {
        labels: ['Red', 'Orange', 'Blue'],
        // datasets is an array of objects where each object represents a set of data to display corresponding to the labels above. for brevity, we'll keep it at one object
        datasets: [
            {
              label: 'Popularity of colours',
              data: [55, 23, 96],
              // you can set indiviual colors for each bar
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)',
                'rgba(54, 162, 235, 0.5)',
                'rgba(255, 206, 86, 0.5)',
              ],
              borderWidth: 1,
            }
        ]
    };

    const [loadingProposals, setLoadingProposals] = useState(false);
    const [proposalsData, setProposalsData] = useState(data);

    useEffect(() => {
        getProposals();
    }, []);

    const getProposals = () => {
        setLoadingProposals(true);

        performRequest("GET", "/v1/proposals")
        .then(onSuccessGetProposals)
        .finally(() => setLoadingProposals(false));
    }

    const onSuccessGetProposals = (res) => {
        if (res.data) {
            var accepted = 0;
            var canceled = 0;
            var waiting = 0;
            res.data.forEach((p) => {
                if (p.status === 0)
                    waiting++;
                if (p.status === 1)
                    canceled++;
                if (p.status === 2)
                    accepted++;
            });

            setProposalsData({
                labels: ['Canceladas', 'Negociação', 'Aceitas'],
                datasets: [
                    {
                      label: '',
                      data: [canceled, waiting, accepted],
                      backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(100, 255, 100, 0.5)',
                      ],
                      borderWidth: 1,
                    }
                ]
            });
        }
    }

    return (
        <>
            <VHeader />
            <Container id='app-container' className="home-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <Row>
                    <Col xs={12} lg={4}>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                    Propostas
                                </Card.Title>
                                {!loadingProposals &&
                                <Pie
                                    data={proposalsData}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Informações das Propostas"
                                        }
                                    }
                                    }}
                                />
                                }
                                {loadingProposals &&
                                <Row>
                                    <Col></Col>
                                    <Col style={{position: "absolute", top: "50%", left: "45%"}}><Loading /></Col>
                                    <Col></Col>
                                </Row>
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                    <Col xs={12} lg={4}>
                    <Card>
                        <Card.Body>
                            <Card.Title>
                                    Apresentação de Dados
                            </Card.Title>
                                <Bar
                                    data={data}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Users Gained between 2016-2020"
                                        },
                                        legend: {
                                        display: false
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
                            <Card.Title>
                                    Apresentação de Dados
                            </Card.Title>
                                <Line
                                    data={data}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Users Gained between 2016-2020"
                                        },
                                        legend: {
                                        display: false
                                        }
                                    }
                                    }}
                                />
                            </Card.Body>
                    </Card>
                    </Col>
                </Row>
                <Row>
                    <Col xs={12} lg={4}>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                    Apresentação de Dados
                                </Card.Title>
                                <Pie
                                    data={data}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Users Gained between 2016-2020"
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
                            <Card.Title>
                                    Apresentação de Dados
                            </Card.Title>
                                <Bar
                                    data={data}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Users Gained between 2016-2020"
                                        },
                                        legend: {
                                        display: false
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
                            <Card.Title>
                                    Apresentação de Dados
                            </Card.Title>
                                <Bar
                                    data={data}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Users Gained between 2016-2020"
                                        },
                                        legend: {
                                        display: false
                                        }
                                    }
                                    }}
                                />
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
};