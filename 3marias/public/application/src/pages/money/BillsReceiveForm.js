import React, { useEffect, useReducer, useState } from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Error from '../../components/error/Error';
import Success from '../../components/success/Success';
import '../../App.css';
import Loading from "../../components/loading/Loading";
import { performRequest } from "../../services/Api";
import { useParams } from "react-router-dom";
import CustomButton from "../../components/button/Button";
import CustomInput from "../../components/input/CustomInput";
import Table from 'react-bootstrap/Table';
import Form from 'react-bootstrap/Form';
import Accordion from 'react-bootstrap/Accordion';
import { getMoney } from "../../services/Utils";
import TableButton from "../../components/button/TableButton";
import Button from "react-bootstrap/esm/Button";
import NoEntity from "../../components/table/NoEntity";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut, Line, Pie } from 'react-chartjs-2';
import { CategoryScale } from "chart.js";
import { registerables} from 'chart.js';
import { formatMoney } from "../../services/Format";
import CustomForm from "../../components/form/Form";
import BackButton from "../../components/button/BackButton";

ChartJS.register(...registerables);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(CategoryScale);

const BillsReceiveForm = ({}) => {

    const params = useParams();
    const [loading, setLoading] = useState(false);
    const [billReceive, setBillReceive] = useState({});
    const initialState = {};

    const reducer = (state, action) => {
        if (action.type === "reset") {
            return initialState;
        }
        if (action.type === "data") {
            return action.data;
        }
        const result = { ...state };
        result[action.type] = action.value;

        return result;
    };
    const [state, dispatch] = useReducer(reducer, initialState);

    const onChangeField = (e) => {
        const { name, value } = e.target;
        dispatch({ type: name, value });
    };

    const onGetBillReceiveById = (id) => {
        setLoading(true);

        performRequest("GET", "/v1/billsReceive/"+id)
        .then(onSuccessGet)
        .catch(onError);
    }

    const onSuccessGet = (res) => {
        setLoading(false);
        setBillReceive(res.data);
    }

    const onError = (err) => {
        setLoading(false);
    }

    const onSubmitTicket = (e) => {
        e.preventDefault();

        // Form Validation
        const form = document.getElementById("formTicket");
        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        } else {
            form.classList.remove("was-validated");
        }

        // setLoading(true);
        // const payload = {
        //     cost_center_id: state.cost_center_id,
        //     products: shareProducts
        // };

        // performRequest("POST", "/v1/stocks/share", payload)
        // .then(onSuccessShareProducts)
        // .catch(onErrorResponse);
    }

    useEffect(() => {
        onGetBillReceiveById(params.id);
    }, []);

    return (
        <>
        <VHeader />
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col>
                    <BackButton />
                </Col>
            </Row>
            <Row>
                <Col xs={8}>
                    <Card style={{height: 320}}>
                        <Card.Body>
                            <Card.Title>
                            <p>   <b>Conta a Receber: {billReceive.description}</b></p>
                            </Card.Title>
                            {!loading && 
                            <>
                            <Row>
                                <Col xs={12}>
                                    <b>Valor: </b> {formatMoney(billReceive.value)}
                                </Col>
                                <Col xs={12}>
                                    <b>Valor Pago:</b> {formatMoney((billReceive.value_performed))} 
                                </Col>
                            </Row>
                            <Row>
                                <Col>
                                    Nesta página você pode gerenciar os produtos do centro de custo.
                                </Col>
                            </Row>
                            </>
                            }

                            {loading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center', marginTop: "15%"}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                        </Card.Body>
                    </Card>
                </Col>
                {loading &&
                <Col xs={4}>
                    <Card style={{height: 320}}>
                        <Card.Body>
                            <Row>
                                <Col></Col>
                                <Col style={{textAlign: 'center', marginTop: "35%"}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                            </Row>
                        </Card.Body>
                    </Card>
                </Col>
                }
                {!loading &&
                <Col xs={4}>
                    <Card style={{height: 320}}>
                        <Card.Body style={{margin: "0 auto"}}>
                            <Doughnut
                                style={{maxWidth: 280, maxHeight: 280}}
                                data={{
                                    labels: [
                                    'Saldo Restante',
                                    'Valor Pago'
                                    ],
                                    datasets: [{
                                    label: 'Conta a Receber',
                                    data: [billReceive.value - billReceive.value_performed, billReceive.value_performed],
                                    backgroundColor: [
                                        'rgba(255, 150, 150, 0.5)',
                                        'rgba(54, 162, 0, 0.5)'
                                    ],
                                    hoverOffset: 4
                                    }]
                                }}
                                options={{
                                plugins: {
                                    title: {
                                    display: true,
                                    text: "Conta a Receber"
                                    }
                                }
                                }}
                            />
                        </Card.Body>
                    </Card>
                </Col>
                }
            </Row>
            <br></br>
            <Accordion>
                <Accordion.Item eventKey="0" style={{marginBottom: 22}}>
                        <Accordion.Header>
                            <i style={{marginTop: -8}} className="material-icons float-left">add</i>
                            <h5>Adicionar Recibo de Pagamento</h5>
                        </Accordion.Header>
                        <Accordion.Body>
                            {loading &&
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!loading &&
                            <>
                            <Form id="formTicket" onSubmit={onSubmitTicket} noValidate={true}>
                            <Row>
                                <Col xs={4}>
                                    <CustomInput key="description" type="text"
                                        placeholder="Descrição *" name="description" 
                                        required={true}
                                        maxlength={255}
                                        value={state.description}
                                        onChange={onChangeField} />
                                </Col>
                                <Col xs={4}>
                                    <CustomInput key="value" type="money"
                                        placeholder="Valor *" name="value" 
                                        required={true}
                                        maxlength={255}
                                        value={state.value}
                                        onChange={onChangeField} />
                                </Col>
                                <Col xs={4}>
                                    <CustomInput key="date" type="mask"
                                        mask={"99/99/9999"}
                                        maskPlaceholder={"Data de Pagamento *"}
                                        placeholder="Data de Pagamento *" name="date" 
                                        required={true}
                                        value={state.date}
                                        onChange={onChangeField} />
                                </Col>
                            </Row>
                            <Row>
                                <Col xs={2}>
                                    <Button variant="success"
                                        type="submit"
                                        size="lg"
                                        disabled={loading}>
                                        {loading ? <Loading />
                                                    : 
                                                    'Adicionar'}
                                    </Button>
                                </Col>
                            </Row>
                            </Form>
                            </>
                            }
                        </Accordion.Body>
                    </Accordion.Item>

                    <Accordion.Item eventKey="1" style={{marginBottom: 22}}>
                        <Accordion.Header>
                            <i style={{marginTop: -8}} className="material-icons float-left">assignment</i>
                            <h5>Recibos de Pagamentos</h5>
                        </Accordion.Header>
                        <Accordion.Body>
                            <Row>
                                <Col>
                                    {loading &&
                                        <>
                                        <Col></Col>
                                        <Col style={{textAlign: 'center'}}>
                                            <Loading />
                                        </Col>
                                        <Col></Col>
                                        </>
                                    }
                                    {!loading &&
                                    <Row>
                                        <Col>
                                            <Table responsive striped>
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Descrição</th>
                                                        <th>Quantidade</th>
                                                        <th>Valor Unitário</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {/* {stock.services.length === 0 &&
                                                        <NoEntity message={"Nenhum produto encontrado."} count={5} />
                                                    } */}
                                                </tbody>
                                            </Table>
                                        </Col>
                                    </Row>
                                    }
                                </Col>
                            </Row>
                        </Accordion.Body>
                    </Accordion.Item>
                </Accordion>
        </Container>
        </>
    )
};

export default BillsReceiveForm;
