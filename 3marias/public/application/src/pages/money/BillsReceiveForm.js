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
import { formatDate, formatMoney, formatStringToNumber } from "../../services/Format";
import BackButton from "../../components/button/BackButton";
import config from "../../config.json";
import CustomButton from "../../components/button/Button";
import Modal from 'react-bootstrap/Modal';

ChartJS.register(...registerables);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(CategoryScale);

const BillsReceiveForm = ({}) => {

    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const params = useParams();
    const [loading, setLoading] = useState(false);
    const [loadingTicket, setLoadingTicket] = useState(false);
    const [billReceive, setBillReceive] = useState({tickets: []});
    const [measurementConfiguration, setMeasurementConfiguration] = useState([]);
    const [measurements, setMeasurements] = useState([]);

    const [showMeasurementItems, setShowMeasurementItems] = useState(false);
    const [measurement, setMeasurement] = useState({items: []});
    const [currentMeasurementValue, setCurrentMeasurementValue] = useState(0);

    const [showAddMeasurement, setShowAddMeasurement] = useState(false);
    const [newMeasurement, setNewMeasurement] = useState([]);
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
        setHttpError(null);
        setLoading(true);

        performRequest("GET", "/v1/billsReceive/"+id)
        .then(onSuccessGet)
        .catch(onError);
    }

    const onSuccessGet = (res) => {
        const billReceive = res.data;
        setLoading(false);
        setBillReceive(billReceive);
        setMeasurementConfiguration(billReceive.measurementConfiguration);

        if (billReceive.source !== "Banco") {
            return;
        }
        if (!billReceive.measurements || billReceive.measurements.length === 0) {
            return;
        }
        let measurements = [];
        let numberMeasurements = billReceive.measurements.length / 20;
        for (var i = 0; i < numberMeasurements; i ++) {
            let items = billReceive.measurements.slice(20 * i, (20 * i) + 20);
            let measure = {
                number: items[0].number,
                total: calculateMeasurementTotal(items, billReceive.value),
                items: items
            }
            measurements.push(measure);
        } 
        setMeasurements(measurements);
    }

    const calculateMeasurementTotal = (items, totalValue) => {
        let total = 0;
        items.forEach((item) => {
            total += (item.incidence * totalValue) / 100;
        });
        return total;
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
        payload.bill_receive_id = params.id;
        payload = processDataBefore(payload);

        performRequest("POST", "/v1/billsTicket", payload)
        .then(onSuccessTicket)
        .catch(onErrorResponse);
    }

    const onSuccessTicket = (res) => {
        setLoadingTicket(false);
        setHttpSuccess({message: "Recibo de Pagamento Salvo com Sucesso!"});
        dispatch({type: "reset"});
        onGetBillReceiveById(params.id);
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
        onGetBillReceiveById(params.id);
    }

    const onPrepareShowMeasurementItems = (measurement) => {
        setMeasurement(measurement);
        setShowMeasurementItems(true);
    }

    const onPrepareAddNewMeasurement = () => {
        setShowAddMeasurement(true);
        if (newMeasurement && newMeasurement.length > 0) {
            return;
        }

        let configuration = [];
        billReceive.measurementConfiguration.forEach((config) => {
            let newConfig = {
                value: 0, 
                measurement_item_id: config.measurement_item_id,
                service: config.measurement_item.service,
                bill_receive_id: params.id,
                incidence: 0
            };
            billReceive.measurements.forEach((item) => {
                if (config.measurement_item_id.toString() === item.measurement_item_id.toString()) {
                    newConfig.value = newConfig.value + parseFloat(item.incidence);
                }
            });
            newConfig.value = config.incidence - newConfig.value;
            configuration.push(newConfig);
        });
        setNewMeasurement(configuration);
    }

    const onUpdateIncidence = (evt, item) => {
        let currentValue = 0;
        newMeasurement.forEach((i) => {
            if (i.measurement_item_id.toString() === item.measurement_item_id.toString()) {
                i.incidence = evt.target.value;
            }
            currentValue += (formatStringToNumber(i.incidence.toString()) * billReceive.value ) / 100;
        });
        setCurrentMeasurementValue(currentValue);
        setNewMeasurement(newMeasurement);
    }

    const onCreateNewMeasurement = () => {
        setShowAddMeasurement(false);
        newMeasurement.forEach((item) => {
            item.incidence = formatStringToNumber(item.incidence.toString());
            item.number = measurements && measurements.length > 0 ? measurements.length + 1 : 1;
        });

        const payload = {
            measurements: newMeasurement
        };

        setLoading(true);
        performRequest("POST", "/v1/measurements", payload)
        .then(() => {
            setLoading(false);
            setHttpSuccess({message: "Medição criada com sucesso!"});
            onGetBillReceiveById(params.id);
        })
        .catch(onErrorResponse);
    }

    useEffect(() => {
        onGetBillReceiveById(params.id);
    }, []);

    return (
        <>

        <Modal size="lg" centered show={showMeasurementItems} onExit={() => {setShowMeasurementItems(false);}}
            onHide={() => {setShowMeasurementItems(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>Medição Nº {measurement.number}</Modal.Title>
                </Modal.Header>
            <Modal.Body>
                <Table hover responsive>
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Incidência</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        {measurement.items.map((item) =>
                            <tr>
                                <td>{item.measurement_item.service}</td>
                                <td>{item.incidence} %</td>
                                <td>{formatMoney(((item.incidence * billReceive.value) / 100).toString())}</td>
                            </tr>
                        )}
                        {measurement.items && measurement.items.length === 0 &&
                            <NoEntity key={"noMeasurementItems"} message={"Nenhuma item de medição encontrado."} count={3} />
                        }
                    </tbody>
                </Table>
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowMeasurementItems(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        <Modal size="lg" centered show={showAddMeasurement} onExit={() => {setShowAddMeasurement(false);}}
            onHide={() => {setShowAddMeasurement(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>+ Adicionar Medição Nº {measurements && measurements.length > 0 ? measurements.length + 1 : 1} (Valor Atual: {formatMoney(currentMeasurementValue.toString())})
                </Modal.Title>
                </Modal.Header>
            <Modal.Body>
                <Table hover responsive>
                    <thead>
                        <tr>
                            <th>Descrição</th>
                            <th>Incidência</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        {newMeasurement.map((item) =>
                            <tr>
                                <td>{item.service}</td>
                                <td>
                                <CustomInput 
                                    key={item.id + "incidence"}
                                    type={"money"} 
                                    placeholder={"Incidência *"}
                                    value={item.incidence}
                                    onChange={(evt) => onUpdateIncidence(evt, item)} 
                                    name="incidence" />
                                </td>
                                <td>
                                    {item.value.toFixed(2)}
                                </td>
                            </tr>
                        )}
                    </tbody>
                </Table>
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Salvar" color="success" onClick={() => {onCreateNewMeasurement()}}></CustomButton>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowAddMeasurement(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        <VHeader />
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
                                {billReceive.source === "Cliente" &&
                                <Col xs={12}>
                                    <b>Quantidade de Pagamentos Recebidos:</b> {billReceive.tickets.length} 
                                </Col>
                                }
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
            {billReceive.source === "Cliente" &&
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
                        <h5>Recibos de Pagamentos ({billReceive.tickets.length})</h5>
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
                                                {billReceive.tickets.map((ticket) => 
                                                    <tr>
                                                        <td>{ticket.id}</td>
                                                        <td>{ticket.description}</td>
                                                        <td>{formatMoney(ticket.value)}</td>
                                                        <td>{formatDate(ticket.date)}</td>
                                                        <td>
                                                            <TableButton key={"file_download" + ticket.id} name="btnEdit" tooltip="Download" onClick={() => {window.open(config.url + "/recibo/" + ticket.id)}}
                                                                icon="file_download" color="light" />
                                                            <TableButton key={"delete" + ticket.id} name="btnDelete" tooltip="Deletar" onClick={() => onDeleteTicket(ticket)}
                                                                icon="delete" color="light" />
                                                        </td>
                                                    </tr>
                                                )}
                                                {billReceive.tickets.length === 0 &&
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
            }
            
            {billReceive.source === "Banco" &&
            <>
            <Accordion>
                <Accordion.Item eventKey="1" style={{marginBottom: 22}}>
                        <Accordion.Header>
                            <i style={{marginTop: -8}} className="material-icons float-left">assignment</i>
                            <h5>Orçamento Padrão Caixa</h5>
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
                                                        <th>Valor</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {measurementConfiguration.map((config) => 
                                                        <tr>
                                                            <td>{config.measurement_item_id}</td>
                                                            <td>{config.measurement_item.service}</td>
                                                            <td>{config.incidence + " %"}</td>
                                                            <td>{formatMoney((config.incidence * billReceive.value / 100).toString())}</td>
                                                        </tr>
                                                    )}
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

            <Accordion>
                <Accordion.Item eventKey="1" style={{marginBottom: 22}}>
                        <Accordion.Header>
                            <i style={{marginTop: -8}} className="material-icons float-left">edit</i>
                            <h5>Medições</h5>
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
                                        <Col xs={11}></Col>
                                        <Col xs={1}>
                                            <CustomButton name="btnAdd" tooltip="Adicionar" icon="add" color="success" onClick={() => {onPrepareAddNewMeasurement();}} />
                                        </Col>
                                        <Col xs={12}>
                                            <Table responsive striped>
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        <th>Valor</th>
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {measurements.map((item) => 
                                                        <tr>
                                                            <td>Medição Nº {item.number}</td>
                                                            <td>{formatMoney(item.total.toString())}</td>
                                                            <td>
                                                                <TableButton 
                                                                    icon={"visibility"}
                                                                    key={"measurement_visibility" + item.number}
                                                                    name={"visibility"}
                                                                    color={"light"}
                                                                    onClick={() => {onPrepareShowMeasurementItems(item);}} 
                                                                    tooltip={"Ver Itens da Medição"} />

                                                                <TableButton 
                                                                    icon={"delete"}
                                                                    key={"measurement" + item.number}
                                                                    name={"delete"}
                                                                    color={"light"}
                                                                    onClick={() => {}} 
                                                                    tooltip={"Excluir Medição"} />
                                                            </td>
                                                        </tr>
                                                    )}
                                                    {measurements && measurements.length === 0 &&
                                                        <NoEntity key={"noMeasurements"} message={"Nenhuma medição encontrada."} count={3} />
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
            </>
            }
        </Container>
        </>
    )
};

export default BillsReceiveForm;
