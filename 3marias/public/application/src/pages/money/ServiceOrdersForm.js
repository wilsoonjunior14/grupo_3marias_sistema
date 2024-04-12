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

const ServiceOrdersForm = ({}) => {
    const [loading, setLoading] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [itemsSelected, setItemsSelected] = useState([]);
    const [products, setProducts] = useState([]);
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

    const onAddServiceOrder = () => {
        const findService = services.find((s) => s.id.toString() === state.service_id);
        const findStock = stocks.find((s) => s.id.toString() === state.cost_center_id);
        let payload = {
            service_name: findService.service,
            stock_name: findStock.name
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
        if (parameters.id) {
            getPurchaseOrder(parameters.id);
        }
    }, []);

    useEffect(() => {
        if (!parameters.id) {
            return;
        }
        updateItemsSelected(items);
    }, [products]);

    const updateItemsSelected = (items) => {
        const itemsToBeSelected = items.map((item) => {
            const product = products.filter((p) => p.id === item.product_id);
            if (product[0]) {
                var productName = product[0].product;
            } else {
                var productName = "Não identificado";
            }
            return {
                id: item.product_id,
                product: productName,
                value: item.value.toString().replace(".", ","),
                quantity: item.quantity
            };
        });
        setItemsSelected(itemsToBeSelected);
    }

    const getPurchaseOrder = (id) => {
        setLoadingPurchase(true);
        performRequest("GET", "/v1/purchaseOrders/" + id)
        .then(onSuccessGetPurchase)
        .catch(onErrorResponse);
    }

    const onSuccessGetPurchase = (res) => {
        setLoadingPurchase(false);
        const data = formatDataFrontend(res.data);
        setItems(data.items);
        updateItemsSelected(data.items);
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
        const moneyValidation = validateMoney(state, "value", "Valor Unitário", true);
        if (moneyValidation) {
            setHttpError(moneyValidation);
            return true;
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
        onAddServiceOrder();

        // var payload = Object.assign({}, state);
        // payload.products = itemsSelected.map((p) => {
        //     return processDataBefore({
        //         product_id: p.id,
        //         quantity: p.quantity,
        //         value: p.value
        //     })
        // });
        // payload = processDataBefore(payload);

        // if (parameters.id) {
        //     setLoading(true);
        //     performRequest("PUT", "/v1/purchaseOrders/" + parameters.id, payload)
        //     .then(onSuccessPutPurchaseResponse)
        //     .catch(onErrorResponse);
        //     return;
        // }

        // setLoading(true);
        // setHttpError(null);
        // setHttpSuccess(null);
        
        // performRequest("POST", "/v1/purchaseOrders", payload)
        // .then(onSuccessPurchaseResponse)
        // .catch(onErrorResponse);
    };

    const onSuccessPutPurchaseResponse = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Ordem de Compra Salva com Sucesso!"});
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
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Adicionar Nova Ordem de Serviço
                                </Card.Title>
                                {!refreshAddData &&
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
                                        <CustomInput 
                                            key="value" 
                                            type="money" 
                                            placeholder="Valor Unitário *" 
                                            name="value" 
                                            maxlength="255"
                                            required={false}
                                            value={state.value}
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
                                            endpoint_field={"fantasy_name"}
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
                                <Row>
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
                                </Row>
                            </Card.Body>
                        </Card>
                    </Form>
                </Col>
                <br></br>
                <Col xs={12}></Col>
                <br></br>
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
            </Row>
        </Container>
        }
        </>
    )
};

export default ServiceOrdersForm;
