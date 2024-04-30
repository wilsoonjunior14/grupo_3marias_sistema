import Container from "react-bootstrap/esm/Container";
import VHeader from "../../components/vHeader/vHeader";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Error from '../../components/error/Error';
import Success from '../../components/success/Success';
import Loading from "../../components/loading/Loading";
import CustomInput from "../../components/input/CustomInput";
import '../../App.css';
import { useEffect, useReducer, useState } from "react";
import Table from "react-bootstrap/Table";
import CustomButton from "../../components/button/Button";
import BackButton from "../../components/button/BackButton";
import { performRequest } from "../../services/Api";
import NoEntity from "../../components/table/NoEntity";
import { formatDataFrontend, processDataBefore, validateForm } from "../../services/Utils";
import Button from "react-bootstrap/esm/Button";
import { validateDate, validateMoney, validateMoneyWithoutAllPatterns, validateNumber, validateRequired, validateRequiredString, validateRequiredStringWithoutPattern } from "../../services/Validation";
import { useParams } from "react-router-dom";
import { formatMoney } from "../../services/Format";
import CustomMoney from "../../components/input/CustomMoney";

const ServiceOrdersForm = ({}) => {
    const [loading, setLoading] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [itemsSelected, setItemsSelected] = useState([]);
    const [products, setProducts] = useState([]);
    const [partners, setPartners] = useState([]);
    const [productSelected, setProductSelected] = useState({});
    const [leaveHappened, setLeaveHappened] = useState(false);
    const [resetScreen, setResetScreen] = useState(false);
    const [resetTable, setResetTable] = useState(false);
    const [loadingPurchase, setLoadingPurchase] = useState(false);
    const [items, setItems] = useState([]);
    const parameters = useParams();
    const initialState = {};

    const [servicesSelected, setServicesSelected] = useState([]);
    const [refreshAddData, setRefreshAddData] = useState(false);
    const [services, setServices] = useState([]);
    const [stocks, setStocks] = useState([]);
    const [loadingOrder, setLoadingOrder] = useState(false);
    const [serviceOrder, setServiceOrder] = useState({});


    const onAddServiceOrder = () => {
        const findService = services.find((s) => s.id.toString() === state.service_id);
        const findStock = stocks.find((s) => s.id.toString() === state.cost_center_id);
        const findPartner = partners.find((s) => s.id.toString() === state.partner_id);
        let payload = {
            service_name: findService.service,
            stock_name: findStock.name,
            partner_name: findPartner.fantasy_name
        };

        servicesSelected.push(Object.assign(payload, state));
        setServicesSelected(servicesSelected);
        dispatch({type: "reset"});
        setRefreshAddData(true);
        setTimeout(() => {
            setRefreshAddData(false);
        }, 50);
    }

    useEffect(() => {
        setHttpError(null);
        getServices();
        getStocks();
        getPartners();
        if (parameters.id) {
            getServiceOrder(parameters.id);
        }
    }, []);

    const getServiceOrder = (id) => {
        setLoadingOrder(true);
        performRequest("GET", "/v1/serviceOrders/" + id)
        .then(onSuccessGetPurchase)
        .catch((err) => {onErrorResponse(err); setLoadingOrder(false);});
    }

    const onSuccessGetPurchase = (res) => {
        setLoadingOrder(false);
        setServiceOrder(res.data);
        const data = formatDataFrontend(res.data);
        dispatch({ type: "data", data });
    }

    const reducer = (state, action) => {
        const value = action.value;
        if (action.type === "reset") {
            return initialState;
        }

        if (action.type === "data") {
            return action.data;
        }

        const result = { ...state };
        result[action.type] = value;
        return result;
    };

    const [state, dispatch] = useReducer(reducer, initialState);

    const changeField = (e) => {
        const { name, value } = e.target;
        dispatch({ type: name, value });
    };

    const getStocks = () => {
        performRequest("GET", "/v1/stocks", null)
        .then((successGetStocks))
        .catch((err) => {});
    }

    const getPartners = () => {
        performRequest("GET", "/v1/partners", null)
        .then((successGetPartners))
        .catch((err) => {});
    }

    const successGetPartners = (res) => {
        setPartners(res.data);
    }

    const successGetStocks = (res) => {
        setStocks(res.data);
    }

    const getServices = () => {
        setLoading(true);
        performRequest("GET", "/v1/services", null)
        .then((successGetServices))
        .catch((err) => setLoading(false));
    }

    const successGetServices = (response) => {
        setLoading(false);
        setServices(response.data);
    }

    const removeService = (service) => {
        setResetTable(true);
        const newItems = servicesSelected.filter((p) => p.service_id.toString() !== service.service_id.toString());
        setServicesSelected(newItems);
        setTimeout(() => {
            setResetTable(false);
        },10);
    }

    const validateData = () => {
        setHttpError(null);
        const descriptionValidation = validateRequiredStringWithoutPattern(state, "description", 255, "Descrição");
        if (descriptionValidation) {
            setHttpError(descriptionValidation);
            return false;
        }
        const serviceIdValidation = validateRequired(state, "service_id", "Serviço");
        if (serviceIdValidation) {
            setHttpError(serviceIdValidation);
            return false;
        }
        const costCenterIdValidation = validateRequired(state, "cost_center_id", "Centro de Custo");
        if (costCenterIdValidation) {
            setHttpError(costCenterIdValidation);
            return false;
        }
        const partnerIdValidation = validateRequired(state, "partner_id", "Parceiro/Fornecedor");
        if (partnerIdValidation) {
            setHttpError(partnerIdValidation);
            return false;
        }
        const dateValidation = validateDate(state, "date", "Data", true);
        if (dateValidation) {
            setHttpError(dateValidation);
            return false;
        }            
        const quantityRequired = validateRequired(state, "quantity", "Quantidade");
        if (quantityRequired) {
            setHttpError(quantityRequired);
            return true;
        }

        if (parameters.id) {
            const moneyValidation = validateMoneyWithoutAllPatterns(state, "value", "value");
            if (moneyValidation) {
                setHttpError(moneyValidation);
                return true;
            }
        } else {
            const moneyValidation = validateMoney(state, "value", "Valor Unitário", true);
            if (moneyValidation) {
                setHttpError(moneyValidation);
                return true;
            }
        }
    }
    
    const onSubmit = (e) => {
        e.preventDefault();

        const validation = validateForm("serviceOrderForm");
        if (!validation) {
            return;
        }
        const dataValidation = validateData();
        if (dataValidation) {
            return;
        }

        if (parameters.id) {
            setLoading(true);
            let payload = Object.assign({}, state);
            payload = processDataBefore(payload);
            payload.status = serviceOrder.status;

            performRequest("PUT", "/v1/serviceOrders/" + parameters.id, payload)
            .then(onSuccessPutServiceOrderResponse)
            .catch(onErrorResponse);
            return;
        }
        onAddServiceOrder();
    };

    const onSuccessPutServiceOrderResponse = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Ordem de Serviço Salva com Sucesso!"});
        getServiceOrder(parameters.id);
    };

    const onSuccessServiceOrderResponse = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Ordens de Serviços Salvos com Sucesso!"});
        dispatch({type: "reset"});
        setResetScreen(true);
        setServicesSelected([]);
        setTimeout(() => {
            setResetScreen(false);
        },10);
    };

    const onErrorResponse = (response) => {
        setLoading(false);
        if (response.response) {
            if (response.response.status === 401) {
                setHttpError({message: "Operação não permitida."});
                return;
            }
            if (response.response.status === 404) {
                setHttpError({message: "Não foi possível conectar-se com o servidor."});
                return;
            }
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    const onSaveServiceOrders = () => {
        let payload = {};
        payload.services = servicesSelected.map((p) => {
            return processDataBefore(p);
        });

        setLoading(true);
        setHttpError(null);
        setHttpSuccess(null);
        
        performRequest("POST", "/v1/serviceOrders", payload)
        .then(onSuccessServiceOrderResponse)
        .catch(onErrorResponse);
    }

    return (
        <>
        <VHeader />
        {!resetScreen &&
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col xs={2}>
                    <BackButton />
                </Col>
            </Row>
            {!loading && httpError &&
                <Error message={httpError.message} />
            }
            {!loading && httpSuccess &&
                <Success message={httpSuccess.message} />
            }
            <Row>
                <Col xs={12}>
                    <Form id="serviceOrderForm" noValidate={true} onSubmit={onSubmit}>
                        <Card>
                            <Card.Body>
                                {!parameters.id &&
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Adicionar Nova Ordem de Serviço
                                </Card.Title>
                                }
                                {parameters.id &&
                                <Card.Title>
                                    <i className="material-icons float-left">edit</i>
                                    Editar Ordem de Serviço
                                </Card.Title>
                                }
                                {!refreshAddData && !loadingOrder &&
                                <Row>
                                    <Col xs={4}>
                                        <CustomInput 
                                            key="description" 
                                            type="text" 
                                            placeholder="Descrição" 
                                            name="description" 
                                            maxlength="255"
                                            required={false}
                                            value={state.description}
                                            onChange={changeField} />
                                    </Col>
                                    <Col xs={4}>
                                        <CustomInput 
                                            key="service_id" 
                                            placeholder="Serviço *" 
                                            type="select"
                                            required={true} 
                                            name="service_id"
                                            endpoint={"services"}
                                            endpoint_field={"service"}
                                            value={state.service_id}
                                            onChange={changeField} />
                                    </Col>
                                    <Col xs={4}>
                                        <CustomInput 
                                            key="quantity" 
                                            type="number" 
                                            placeholder="Quantidade *" 
                                            name="quantity" 
                                            required={true}
                                            value={state.quantity}
                                            onChange={changeField} />
                                    </Col>
                                    <Col xs={4}>
                                        {/* <CustomInput 
                                            key="value" 
                                            type="money" 
                                            placeholder="Valor Unitário *" 
                                            name="value" 
                                            maxlength="255"
                                            required={false}
                                            value={state.value}
                                            onChange={changeField} /> */}
                                        <CustomMoney 
                                            key={"value"}
                                            defaultValue={state.value}
                                            placeholder={"Valor Unitário *"}
                                            name={"value"}
                                            onChange={changeField}
                                            required={true}
                                            value={state.value}
                                        />
                                    </Col>
                                    <Col xs={4}>
                                        <CustomInput 
                                            key="partner_id" 
                                            placeholder="Parceiro/Fornecedor *" 
                                            type="select"
                                            required={true} 
                                            name="partner_id"
                                            endpoint={"partners"}
                                            endpoint_field={"fantasy_name"}
                                            value={state.partner_id}
                                            onChange={changeField} />
                                    </Col>
                                    <Col xs={4}>
                                        <CustomInput 
                                            key="cost_center_id" 
                                            placeholder="Centro de Custo *" 
                                            type="select"
                                            required={true} 
                                            name="cost_center_id"
                                            endpoint={"stocks"}
                                            endpoint_field={"name"}
                                            value={state.cost_center_id}
                                            onChange={changeField} />
                                    </Col>
                                    <Col xs={4}>
                                        <CustomInput 
                                            key="date" 
                                            placeholder="Data *" 
                                            type="mask" 
                                            name="date" 
                                            mask="99/99/9999"
                                            required={true}
                                            maskPlaceholder="Data *" 
                                            maxlength="255"
                                            value={state.date}
                                            onChange={changeField} />
                                    </Col>
                                </Row>
                                }
                                <br></br>
                                {loadingOrder &&
                                    <Row>
                                        <Col xs={5}></Col>
                                        <Col xs={2} style={{textAlign: "center"}}>
                                            <Loading />
                                        </Col>
                                        <Col xs={5}></Col>
                                    </Row>
                                }
                                <Row>
                                    {!parameters.id &&
                                    <Col xs={3}>
                                        <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                            <Button variant="success"
                                                type="submit"
                                                size="lg"
                                                disabled={loading}>
                                                Adicionar
                                            </Button>
                                        </div>
                                    </Col>
                                    }
                                    {parameters.id && !loadingOrder &&
                                    <Col xs={3}>
                                        <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                            <Button variant="success"
                                                type="submit"
                                                size="lg"
                                                disabled={loading}>
                                                {loading ? <Loading />
                                                            : 
                                                            'Salvar'}
                                            </Button>
                                        </div>
                                    </Col>
                                    }
                                </Row>
                            </Card.Body>
                        </Card>
                    </Form>
                </Col>
                <br></br>
                <Col xs={12}></Col>
                <br></br>
                {!parameters.id &&
                <Col xs={12}>
                    <Card>
                        <Card.Body>
                            <Row>
                                    <Col>
                                        <Row>
                                            <Col xs={12}>
                                                <Card>
                                                    <Card.Body>
                                                        <Card.Title>
                                                            Ordens de Serviço
                                                        </Card.Title>
                                                        <Row>
                                                            <Col>
                                                                {!resetTable &&
                                                                <Table>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Descrição</th>
                                                                            <th>Serviço</th>
                                                                            <th>Centro de Custo</th>
                                                                            <th>Parceiro/Fornecedor</th>
                                                                            <th>Quantidade</th>
                                                                            <th>Valor Unitário</th>
                                                                            <th>Opções</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        {servicesSelected.map((item) => 
                                                                            <tr>
                                                                                <td>{item.description}</td>
                                                                                <td>{item.service_name}</td>
                                                                                <td>{item.stock_name}</td>
                                                                                <td>{item.partner_name}</td>
                                                                                <td>{item.quantity}</td>
                                                                                <td>R$ {item.value}</td>
                                                                                <td>
                                                                                    <CustomButton
                                                                                    onClick={() => removeService(item)} 
                                                                                    color="light" icon="delete" tooltip="Remover Serviço" 
                                                                                    key="btn_delete_shopping_item" name="btn_delete_shopping_item" />
                                                                                </td>
                                                                            </tr>
                                                                        )}
                                                                        {servicesSelected.length === 0 &&
                                                                            <NoEntity count={4} message="Nenhum serviço adicionado." />
                                                                        }
                                                                    </tbody>
                                                                </Table>
                                                                }
                                                            </Col>
                                                        </Row>
                                                    </Card.Body>
                                                </Card>
                                            </Col>
                                        </Row>
                                    </Col>
                                </Row>
                                <br></br>
                                <br></br>
                                {!loadingPurchase &&
                                <Row>
                                    <Col xs={3}>
                                        <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                            <Button variant="success"
                                                type="button"
                                                size="lg"
                                                disabled={loading}
                                                onClick={onSaveServiceOrders}>
                                                {loading ? <Loading />
                                                            : 
                                                            'Salvar'}
                                            </Button>
                                        </div>
                                    </Col>
                                    <Col xs={9}></Col>
                                </Row>
                                }
                        </Card.Body>
                    </Card>
                </Col>
                }
            </Row>
        </Container>
        }
        </>
    )
};

export default ServiceOrdersForm;
