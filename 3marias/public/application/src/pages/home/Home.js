import React from "react";
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

    return (
        <>
            <VHeader />
            <Container id='app-container' className="home-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
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