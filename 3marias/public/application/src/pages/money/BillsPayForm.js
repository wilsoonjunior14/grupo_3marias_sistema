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
import CustomInput from "../../components/input/CustomInput";
import Table from 'react-bootstrap/Table';
import Form from 'react-bootstrap/Form';
import Accordion from 'react-bootstrap/Accordion';
import { processDataBefore } from "../../services/Utils";
import TableButton from "../../components/button/TableButton";
import Button from "react-bootstrap/esm/Button";
import NoEntity from "../../components/table/NoEntity";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut } from 'react-chartjs-2';
import { CategoryScale } from "chart.js";
import { registerables} from 'chart.js';
import { formatDate, formatMoney } from "../../services/Format";
import BackButton from "../../components/button/BackButton";
import { hasPermission } from "../../services/Storage";
import Forbidden from "../../components/error/Forbidden";

ChartJS.register(...registerables);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(CategoryScale);

const BillsPayForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const params = useParams();
    const [loading, setLoading] = useState(false);
    const [loadingTicket, setLoadingTicket] = useState(false);
    const [billPay, setBillPay] = useState({tickets: []});
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

    const onGetBillPayById = (id) => {
        setHttpError(null);
        setLoading(true);

        performRequest("GET", "/v1/billsPay/"+id)
        .then(onSuccessGet)
        .catch(onError);
    }

    const onSuccessGet = (res) => {
        setLoading(false);
        setBillPay(res.data);
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

        setLoadingTicket(true);
        setHttpError(null);
        let payload = Object.assign({}, state);
        payload.bill_pay_id = params.id;
        payload = processDataBefore(payload);

        performRequest("POST", "/v1/billsTicket", payload)
        .then(onSuccessTicket)
        .catch(onErrorResponse);
    }

    const onSuccessTicket = (res) => {
        setLoadingTicket(false);
        setHttpSuccess({message: "Recibo de Pagamento Salvo com Sucesso!"});
        dispatch({type: "reset"});
        onGetBillPayById(params.id);
    }

    const onErrorResponse = (response) => {
        setLoading(false);
        setLoadingTicket(false);
        if (response.response) {
            if (response.response.status === 404) {
                setHttpError("Não foi possível conectar-se com o servidor.");
                return;
            }
            if (response.response.status === 401) {
                setHttpError("Você não tem permissão para realizar essa operação.");
                return;
            }
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    const onDeleteTicket = (ticket) => {
        setLoading(true);

        performRequest("DELETE", "/v1/billsTicket/" + ticket.id)
        .then(onSuccessDelete)
        .catch(onErrorResponse);
    }

    const onSuccessDelete = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Recibo de Pagamento Excluído com Sucesso!"});
        onGetBillPayById(params.id);
    }

    useEffect(() => {
        onGetBillPayById(params.id);
    }, []);

    return (
        <>
        <VHeader />
        {(isDeveloper || isAdmin) &&
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col>
                {!loading && httpError &&
                    <Error message={httpError.message} />
                }
                {!loading && httpSuccess &&
                    <Success message={httpSuccess.message} />
                }
                </Col>
            </Row>
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
                            <p>   <b>Conta a Pagar: {billPay.description}</b></p>
                            </Card.Title>
                            {!loading && 
                            <>
                            <Row>
                                <Col xs={12}>
                                    <b>Valor: </b> {formatMoney(billPay.value)}
                                </Col>
                                <Col xs={12}>
                                    <b>Valor Pago:</b> {formatMoney((billPay.value_performed))} 
                                </Col>
                                <Col xs={12}>
                                    <b>Quantidade de Pagamentos Efetuados:</b> {billPay.tickets.length} 
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
                                    label: 'Conta a Pagar',
                                    data: [billPay.value - billPay.value_performed, billPay.value_performed],
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
                                    text: "Conta a Pagar"
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
                                        disabled={loadingTicket}>
                                        {loadingTicket ? <Loading />
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
                            <h5>Recibos de Pagamentos ({billPay.tickets.length})</h5>
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
                                                        <th>Valor</th>
                                                        <th>Data</th>
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {billPay.tickets.map((ticket) => 
                                                        <tr>
                                                            <td>{ticket.id}</td>
                                                            <td>{ticket.description}</td>
                                                            <td>{formatMoney(ticket.value)}</td>
                                                            <td>{formatDate(ticket.date)}</td>
                                                            <td>
                                                                <TableButton key={"delete" + ticket.id} name="btnDelete" tooltip="Deletar" onClick={() => onDeleteTicket(ticket)}
                                                                    icon="delete" color="light" />
                                                            </td>
                                                        </tr>
                                                    )}
                                                    {billPay.tickets.length === 0 &&
                                                        <NoEntity message={"Nenhum produto encontrado."} count={5} />
                                                    }
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
        }

        {!(isDeveloper || isAdmin) &&
            <Forbidden />
        }
        </>
    )
};

export default BillsPayForm;
