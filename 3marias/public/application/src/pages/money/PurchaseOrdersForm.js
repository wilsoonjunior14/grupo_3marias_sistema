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
import { validateDate, validateMoney, validateMoneyWithoutAllPatterns, validateNumber, validateRequired } from "../../services/Validation";
import { useParams } from "react-router-dom";

const PurchaseOrdersForm = ({}) => {
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

    useEffect(() => {
        setHttpError(null);
        getProducts();
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

    const getProducts = () => {
        setLoading(true);
        performRequest("GET", "/v1/products", null)
        .then((successGetProducts))
        .catch((err) => setLoading(false));
    }

    const successGetProducts = (response) => {
        setLoading(false);
        setProducts(response.data);
    }

    const onDragStart = (product) => {
        const element = document.getElementById("product"+product.id);
        element.style.border = "2px solid red";
        element.style.backgroundColor = "red";

        setProductSelected(product);
    }

    const onDragEnd = (product) => {
        const element = document.getElementById("product"+product.id);
        element.style.border = "none";
        element.style.borderBottom = "1px solid lightgray";
        element.style.backgroundColor = "none";

        setProductSelected({});
        if (leaveHappened) {
            onDragLeave();
            setLeaveHappened(false);
        }
    }

    const onDragLeave = () => {
        if (!productSelected.id) {
            return;
        }
        const exists = itemsSelected.some((p) => p.id === productSelected.id);
        if (exists) {
            // shows a modal message.
            return;
        }

        itemsSelected.push(productSelected);
        setItemsSelected(itemsSelected);
        setProductSelected({});
    }

    const removeProduct = (product) => {
        setResetTable(true);
        const newItems = itemsSelected.filter((p) => p.id !== product.id);
        setItemsSelected(newItems);
        setTimeout(() => {
            setResetTable(false);
        },10);
    }

    const onChangeProductItem = (item, evt) => {
        itemsSelected.forEach((itemSelected) => {
            if (itemSelected.id.toString() === item.id.toString()) {
                itemSelected[evt.target.name] = evt.target.value;
            }
        });
        setItemsSelected(itemsSelected);
    }

    const validateData = () => {
        setHttpError(null);
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
        if (!itemsSelected || itemsSelected.length === 0) {
            setHttpError({message: "Lista de produtos selecionados está vazia."});
            return false;
        }

        var hasInvalidProductSelected = itemsSelected.some((product) => {
            if (parameters.id) {
                const moneyValidation = validateMoneyWithoutAllPatterns(product, "value", "value", "Valor Unitário");
                if (moneyValidation) {
                    setHttpError(moneyValidation);
                    return true;
                }
            } else {
                const moneyValidation = validateMoney(product, "value", "Valor Unitário", true);
                if (moneyValidation) {
                    setHttpError(moneyValidation);
                    return true;
                }
            }
            const quantityRequired = validateRequired(product, "quantity", "Quantidade");
            if (quantityRequired) {
                setHttpError(quantityRequired);
                return true;
            }
            const quantityValidation = validateNumber(product, "quantity", "Quantidade");
            if (quantityValidation) {
                setHttpError(quantityValidation);
                return true;
            }
        });

        return !hasInvalidProductSelected;

    }
    
    const onSubmit = (e) => {
        e.preventDefault();

        const validation = validateForm("purchaseOrderForm");
        if (!validation) {
            return;
        }

        const dataValidation = validateData();
        if (!dataValidation) {
            return;
        }

        var payload = Object.assign({}, state);
        payload.products = itemsSelected.map((p) => {
            return processDataBefore({
                product_id: p.id,
                quantity: p.quantity,
                value: p.value
            })
        });
        payload = processDataBefore(payload);

        if (parameters.id) {
            setLoading(true);
            performRequest("PUT", "/v1/purchaseOrders/" + parameters.id, payload)
            .then(onSuccessPutPurchaseResponse)
            .catch(onErrorResponse);
            return;
        }

        setLoading(true);
        setHttpError(null);
        setHttpSuccess(null);
        
        performRequest("POST", "/v1/purchaseOrders", payload)
        .then(onSuccessPurchaseResponse)
        .catch(onErrorResponse);
    };

    const onSuccessPutPurchaseResponse = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Ordem de Compra Salva com Sucesso!"});
    };

    const onSuccessPurchaseResponse = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Ordem de Compra Salva com Sucesso!"});
        dispatch({type: "reset"});
        setResetScreen(true);
        setItemsSelected([]);
        setProductSelected({});
        setTimeout(() => {
            setResetScreen(false);
        },10);
    };

    const onErrorResponse = (response) => {
        setLoading(false);
        if (response.response) {
            if (response.response.status === 404) {
                setHttpError("Não foi possível conectar-se com o servidor.");
                return;
            }
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

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
                <Col>
                    <Form id="purchaseOrderForm" noValidate={true} onSubmit={onSubmit}>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Detalhes da Compra
                                </Card.Title>
                                <Row>
                                    <Col>
                                        <CustomInput 
                                            key="nrRecibo" 
                                            type="text" 
                                            placeholder="Descrição" 
                                            name="description" 
                                            maxlength="255"
                                            required={false}
                                            value={state.description}
                                            onChange={changeField} />
                                    </Col>
                                    <Col>
                                        <CustomInput 
                                            key="partner_id" 
                                            placeholder="Fornecedor *" 
                                            type="select"
                                            required={true} 
                                            name="partner_id"
                                            endpoint={"partners"}
                                            endpoint_field={"fantasy_name"}
                                            value={state.partner_id}
                                            onChange={changeField} />
                                    </Col>
                                    <Col>
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
                                <hr></hr>
                                <br></br>
                                <br></br>
                                <Row>
                                    <Col>
                                        <Card.Title>
                                            <i className="material-icons float-left">add</i>
                                            Adicionar Itens da Compra
                                        </Card.Title>
                                        <Row>
                                            <Col xs={3}>
                                                <Card>
                                                    <Card.Body>
                                                        <Card.Title>
                                                            Produtos
                                                        </Card.Title>
                                                        {loading &&
                                                            <Row>
                                                                <Col xs={4}></Col>
                                                                <Col xs={4}>
                                                                    <Loading />
                                                                </Col>
                                                                <Col xs={4}></Col>
                                                            </Row>
                                                        }
                                                        {!loading &&
                                                        <Row>
                                                            <Col>
                                                                <Table>
                                                                    <thead>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>Nome</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        {products.map((product) => 
                                                                            <tr id={"product" + product.id} draggable={true} 
                                                                                onDragStart={() => onDragStart(product)} 
                                                                                onDragEnd={() => onDragEnd(product)}>
                                                                                <td>{product.id}</td>
                                                                                <td>{product.product}</td>
                                                                            </tr>
                                                                        )}
                                                                    </tbody>
                                                                </Table>
                                                            </Col>
                                                        </Row>
                                                        }
                                                    </Card.Body>
                                                </Card>
                                            </Col>
                                            <Col xs={9}>
                                                <Card>
                                                    <Card.Body>
                                                        <Card.Title>
                                                            Produtos Selecionados
                                                        </Card.Title>
                                                        <Row>
                                                            <Col>
                                                                {!resetTable &&
                                                                <Table 
                                                                    onDragLeave={() => {setLeaveHappened(true)}}>
                                                                    <thead onDragLeave={() => {setLeaveHappened(true)}}>
                                                                        <tr>
                                                                            <th>Nome</th>
                                                                            <th>Quantidade</th>
                                                                            <th>Valor Unitário</th>
                                                                            <th>Opções</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody onDragLeave={() => {setLeaveHappened(true)}}>
                                                                        {itemsSelected.map((item) => 
                                                                            <tr>
                                                                                <td>
                                                                                    <CustomInput
                                                                                        placeholder="Produto" 
                                                                                        type="text" 
                                                                                        name="product_name"
                                                                                        disabled={"true"}
                                                                                        value={item.product}
                                                                                        required={true} />
                                                                                </td>
                                                                                <td>
                                                                                    <CustomInput
                                                                                        placeholder="Quantidade" 
                                                                                        type="number" 
                                                                                        name="quantity"
                                                                                        required={true}
                                                                                        onChange={(evt) => onChangeProductItem(item, evt)}
                                                                                        value={item.quantity} />
                                                                                </td>
                                                                                <td>
                                                                                    <CustomInput  
                                                                                        placeholder="Valor Unitário" 
                                                                                        type="money"
                                                                                        name="value"
                                                                                        required={true}
                                                                                        onChange={(evt) => onChangeProductItem(item, evt)}
                                                                                        value={item.value} />
                                                                                </td>
                                                                                <td>
                                                                                    <CustomButton
                                                                                    onClick={() => removeProduct(item)} 
                                                                                    color="light" icon="delete" tooltip="Remover Item de Compra" 
                                                                                    key="btn_delete_shopping_item" name="btn_delete_shopping_item" />
                                                                                </td>
                                                                            </tr>
                                                                        )}
                                                                        {itemsSelected.length === 0 &&
                                                                            <NoEntity count={4} message="Nenhum produto adicionado." />
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
                                <hr></hr>
                                {!loadingPurchase &&
                                <Row>
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
                                    <Col xs={9}></Col>
                                </Row>
                                }
                            </Card.Body>
                        </Card>
                    </Form>
                </Col>
            </Row>
        </Container>
        }
        </>
    )
};

export default PurchaseOrdersForm;
