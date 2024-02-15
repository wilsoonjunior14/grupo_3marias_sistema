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
import { formatDate, formatDoubleValue, formatMoney } from "../../services/Format";

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
    const [proposals, setProposals] = useState([]);

    const [billReceive, setBillReceive] = useState("0,000.00");
    const [loadingBills, setLoadingBills] = useState(false);

    const [billPay, setBillPay] = useState(0);
    const [loadingBillPay, setLoadingBillPay] = useState(false);

    const [balance, setBalance] = useState(0);
    const [loadingBalance, setLoadingBalance] = useState(false);

    const [nextBillsReceive, setNextBillsReceive] = useState([]);

    useEffect(() => {
        getProposals();
        getBillsToReceiveInProgress();
    }, []);

    const getProposals = () => {
        setLoadingProposals(true);

        performRequest("GET", "/v1/proposals")
        .then(onSuccessGetProposals)
        .finally(() => setLoadingProposals(false));
    }

    const onSuccessGetProposals = (res) => {
        if (res.data) {
            setProposals(res.data);
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

    const getBillsToReceiveInProgress = () => {
        setLoadingBills(true);
        performRequest("GET", "/v1/billsReceive/get/inProgress")
        .then(onSuccessGetBillsToReceive)
        .catch(onErrorGetBillsToReceive);
    };

    const onSuccessGetBillsToReceive = (res) => {
        var globalValue = 0;
        const responseData = res.data;
        responseData.forEach((bill) => {
            globalValue += Math.abs(Number(bill.value_performed) - Number(bill.value));
        });
        setLoadingBills(false);
        setBillReceive(formatMoney(globalValue.toString()));

        var nextBills = [];
        const billsLength = responseData.length > 5 ? 5 : responseData.length;
        for (var i = 0; i < billsLength; i++) {
            if (responseData[i].desired_date) {
                nextBills.push(responseData[i]);
            }
        }
        setNextBillsReceive(nextBills);
    };

    const onErrorGetBillsToReceive = (err) => {
        setLoadingBills(false);
        setBillReceive("Erro");
    }

    return (
        <>
            <VHeader />
            <Container id='app-container' className="home-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <Row>
                    <Col xs={12}>
                        <Row>
                            <Col xs={12} lg={4} style={{marginBottom: 7}}>
                                <Card style={{height: 200, background: "rgba(54, 162, 0, 0.5)", color: "white"}}>
                                    <Card.Body>
                                        <Card.Title>
                                            Contas a Receber
                                            <i className="material-icons float-left">attach_money</i>
                                        </Card.Title>
                                        {loadingBills &&
                                        <Row>
                                            <Col></Col>
                                            <Col style={{position: "absolute", top: "50%", left: "45%"}}><Loading /></Col>
                                            <Col></Col>
                                        </Row>
                                        }
                                        {!loadingBills &&
                                        <Row>
                                            <Col style={{fontSize: 40, marginTop: 25, color: "white"}}>
                                                <b>+ {billReceive}</b>
                                            </Col>
                                        </Row>
                                        }
                                    </Card.Body>
                                </Card>
                            </Col>
                            <Col xs={12} lg={4} style={{marginBottom: 7}}>
                                <Card style={{height: 200, background: "rgba(255, 99, 90, 0.5)", color: "white"}}>
                                    <Card.Body>
                                        <Card.Title>
                                            Contas a Pagar
                                            <i className="material-icons float-left">attach_money</i>
                                        </Card.Title>
                                        <Row>
                                            <Col style={{fontSize: 40, marginTop: 25, color: "white"}}>
                                                <b> - R$ {billPay}</b>
                                            </Col>
                                        </Row>
                                    </Card.Body>
                                </Card>
                            </Col>
                            <Col xs={12} lg={4} style={{marginBottom: 7}}>
                                <Card style={{height: 200, background: "rgba(0, 99, 255, 0.5)", color: "white"}}>
                                    <Card.Body>
                                        <Card.Title>
                                            Saldo da Construtora
                                            <i className="material-icons float-left">attach_money</i>
                                        </Card.Title>
                                        <Row>
                                            <Col style={{fontSize: 40, marginTop: 25, color: "white"}}>
                                                <b> + R$ {balance}</b>
                                            </Col>
                                        </Row>
                                    </Card.Body>
                                </Card>
                            </Col>
                        </Row>
                    </Col>
                    
                    <Col xs={12} lg={4}>
                        {!loadingBills &&
                        <Row>
                            {nextBillsReceive.map((bill) => 
                            <Col xs={12} style={{marginBottom: 10}}>
                                <Card>
                                    <Card.Body>
                                        <Card.Title>
                                            Conta a Receber em {formatDate(bill.desired_date)}
                                            <i className="material-icons float-left">timeline</i>
                                        </Card.Title>
                                        <Row>
                                            <Col xs={12}>
                                                <b>Cliente: </b>{bill.contract.proposal.client.name}
                                            </Col>
                                            <Col xs={12}>
                                                <b>Descrição: </b>{bill.description}
                                            </Col>
                                            <Col xs={12}>
                                                <b>Valor: </b>{formatMoney(Math.abs(bill.value_performed - bill.value).toString())}
                                            </Col>
                                        </Row>
                                    </Card.Body>
                                </Card>
                            </Col>
                            )}
                        </Row>
                        }
                    </Col>

                    {proposals.length > 0 &&
                    <Col xs={12} lg={4}>
                        <Card className="main-card">
                            <Card.Body>
                                <Card.Title>
                                    Propostas
                                    <i className="material-icons float-left">work</i>
                                </Card.Title>
                                {!loadingProposals &&
                                <Pie
                                    width={"80%"}
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
                    }

                    {proposals.length > 0 &&
                    <Col xs={12} lg={4}>
                        <Card className="main-card">
                            <Card.Body>
                                <Card.Title>
                                    Propostas
                                    <i className="material-icons float-left">work</i>
                                </Card.Title>
                                {!loadingProposals &&
                                <Pie
                                    width={"80%"}
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
                    }
                </Row>
                {/* <Row>
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
                </Row> */}

            </Container>
        </>
    );
};