import React, { useEffect, useReducer, useState } from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Error from '../../../components/error/Error';
import Success from '../../../components/success/Success';
import '../../../App.css';
import Loading from "../../../components/loading/Loading";
import { performRequest } from "../../../services/Api";
import { useParams } from "react-router-dom";
import CustomButton from "../../../components/button/Button";
import CustomInput from "../../../components/input/CustomInput";
import Table from 'react-bootstrap/Table';
import Form from 'react-bootstrap/Form';
import Accordion from 'react-bootstrap/Accordion';
import { getMoney } from "../../../services/Utils";
import TableButton from "../../../components/button/TableButton";
import Button from "react-bootstrap/esm/Button";
import NoEntity from "../../../components/table/NoEntity";
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js';
import { Doughnut, Line, Pie } from 'react-chartjs-2';
import { CategoryScale } from "chart.js";
import { registerables} from 'chart.js';

ChartJS.register(...registerables);
ChartJS.register(ArcElement, Tooltip, Legend);
ChartJS.register(CategoryScale);

function StockItems() {

    const parameters = useParams();
    const [initialState, setInitialState] = useState({items: []});
    const [stock, setStock] = useState({items: [], services: []});
    const [total, setTotal] = useState(0);
    const [loading, setLoading] = useState(false);
    const [products, setProducts] = useState([]);
    const [shareProducts, setShareProducts] = useState([]);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);

    useEffect(() => {
        getStock(parameters.id);
    }, []);

    const calculateTotalValue = (stock) => {
        let value = 0;
        stock.items.forEach((item) => {
            value = value + (item.quantity * item.value);
        });
        stock.services.forEach((item) => {
            value = value + (item.quantity * item.value);
        });
        setTotal(value);
    }

    const getStock = (id) => {
        setLoading(true);
        setHttpSuccess(null);
        setHttpError(null);

        performRequest("GET", "/v1/stocks/" + id)
        .then(onSuccessGetResponse)
        .catch(onErrorResponse);
    }

    const onSuccessGetResponse = (response) => {
        setLoading(false);
        setStock(response.data);
        calculateTotalValue(response.data);
        getProducts(response.data);
    }

    const getProducts = (res) => {
        const products = res.items.map((item) => {return item.product.product;});
        setProducts(products);
    }

    const onErrorResponse = (response) => {
        setLoading(false);
        const res = response.response;
        if (res) {
            if (res.status === 401) {
                setHttpError({message: "Você precisa estar logado na aplicação para efetuar essa operação.", statusCode: res.status});
                return;
            }
            if (res.status === 404) {
                setHttpError({message: "Operação desconhecida não pode ser executada.", statusCode: res.status});
                return;
            }
            setHttpError({message: res.data.message, statusCode: res.status});
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    }

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

    const onAddProductToShare = () => {
        setHttpError(null);
        
        const validation = validateProductShare(state.product_name, state.quantity, false);
        if (!validation) {
            return;
        }

        let productsSelected = stock.items.filter((item) => item.product.product === state.product_name);
        let product = productsSelected[0];
        product.quantity = state.quantity;
        product.editing = false;
        shareProducts.push(product);
        setShareProducts(shareProducts);
        onChangeField({target:{name: "product_name", value: ""}});
        onChangeField({target:{name: "quantity", value: ""}});
    }

    const onSaveItem = (item) => {
        setHttpError(null);
        const validated = validateProductShare(item.product.product, item.quantity, true);
        if (!validated) {
            return;
        }
        onSetEditItem(item);
    }

    const onSetEditItem = (item) => {
        const index = shareProducts.findIndex((product) => product.id === item.id);
        shareProducts[index].editing = !shareProducts[index].editing;
        const newShareProducts = Object.assign([], shareProducts);
        onDeleteItem(item);
        setShareProducts(newShareProducts);
    }

    const onDeleteItem = (item) => {
        const newProducts = shareProducts.filter((p) => p.id !== item.id);
        setShareProducts(newProducts);
    }

    const onChangeQuantity = (evt, item) => {
        const index = shareProducts.findIndex((product) => product.id === item.id);
        shareProducts[index].quantity = evt.target.value;
        const newShareProducts = Object.assign([], shareProducts);
        onDeleteItem(item);
        setShareProducts(newShareProducts);
    }

    const validateProductShare = (productName, quantity, isEditing) => {
        if (!productName || productName.length === 0) {
            setHttpError({message: "Campo Produto é obrigatório."});
            return false;
        }
        let productsSelected = stock.items.filter((item) => item.product.product === productName);
        if (!productsSelected || !productsSelected[0]) {
            setHttpError({message: "Campo Produto não identificado."});
            return false;
        }
        if (!quantity || quantity === 0) {
            setHttpError({message: "Campo Quantidade é obrigatório."});
            return false;
        }
        let product = productsSelected[0];
        if (quantity > product.quantity) {
            setHttpError({message: "Campo Quantidade contém valor superior ao valor existente para o produto."});
            return false; 
        }
        if (!isEditing && shareProducts.some((p) => p.id.toString() === product.id.toString())) {
            setHttpError({message: "Produto já foi adicionado anteriormente."});
            return false; 
        }
        return true;
    }

    const onSubmitProductsToShare = (e) => {
        e.preventDefault();

        // Form Validation
        const form = document.getElementById("formShareItems");
        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        } else {
            form.classList.remove("was-validated");
        }

        setLoading(true);
        const payload = {
            cost_center_id: state.cost_center_id,
            products: shareProducts
        };

        performRequest("POST", "/v1/stocks/share", payload)
        .then(onSuccessShareProducts)
        .catch(onErrorResponse);
    }

    const onSuccessShareProducts = (res) => {
        setLoading(false);
        getStock(parameters.id);
        setShareProducts([]);
        setHttpSuccess({message: "Produtos Transferidos com Sucesso!"});
    }

    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                {!loading && httpError &&
                    <Error message={httpError.message} />
                }
                {!loading && httpSuccess &&
                    <Success message={httpSuccess.message} />
                }
                <Row>
                    <Col xs={6}>
                        <Card style={{height: 350}}>
                            <Card.Body>
                                <Card.Title>
                                {stock.status === "Ativo" &&
                                    <i className="material-icons float-left" style={{color: "green"}}>brightness_1</i>
                                }
                                {stock.status !== "Ativo" &&
                                    <i className="material-icons float-left" style={{color: "red"}}>brightness_1</i>
                                } 
                                
                                <p>   <b>Centro de Custo: {stock.name}</b></p>
                                </Card.Title>
                                {!loading && 
                                <>
                                <Row>
                                    <Col xs={12}>
                                        <b>Valor do Contrato: </b> 
                                    </Col>
                                    <Col xs={12}>
                                        <b>Valor do Orçamento Atual: </b>{getMoney(total.toString().replace(".", ","))} 
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
                                    <Col style={{textAlign: 'center'}}>
                                        <Loading />
                                    </Col>
                                    <Col></Col>
                                    </>
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                    <Col xs={3}>
                        <Card style={{height: 350}}>
                            <Card.Body>
                                <Doughnut
                                    height={250}
                                    width={250}
                                    data={{
                                        labels: [
                                        'Orçamento Disponível',
                                        'Orçamento Gasto',
                                        ],
                                        datasets: [{
                                        label: 'Orçamento',
                                        data: [50000 - 32500, 32500],
                                        backgroundColor: [
                                            'rgba(54, 162, 0, 0.5)',
                                            'rgba(255, 20, 20, 0.5)',
                                        ],
                                        hoverOffset: 4
                                        }]
                                    }}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Contrato"
                                        }
                                    }
                                    }}
                                />
                            </Card.Body>
                        </Card>
                    </Col>
                    <Col xs={3}>
                        <Card style={{height: 350}}>
                            <Card.Body>
                                <Pie
                                    height={250}
                                    width={250}
                                    data={{
                                        labels: [
                                        'Orçamento Disponível',
                                        'Serviços',
                                        'Compras'
                                        ],
                                        datasets: [{
                                        label: 'Orçamento',
                                        data: [50000, 22500, 15200],
                                        backgroundColor: [
                                            'rgba(54, 162, 0, 0.5)',
                                            'rgba(255, 150, 150, 0.5)',
                                            'rgba(255, 0, 0, 0.5)',
                                        ],
                                        hoverOffset: 4
                                        }]
                                    }}
                                    options={{
                                    plugins: {
                                        title: {
                                        display: true,
                                        text: "Contrato"
                                        }
                                    }
                                    }}
                                />
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>

                <br></br>

                <Accordion>
                <Accordion.Item eventKey="0" style={{marginBottom: 22}}>
                        <Accordion.Header>
                            <i className="material-icons float-left">build</i>
                            <h5>Serviços</h5>
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
                                                    {stock.services.map((item) => 
                                                        <tr>
                                                            <td>{item.id}</td>
                                                            <td>{item.description}</td>
                                                            <td>{item.quantity}</td>
                                                            <td>{getMoney(item.value.toString().replace(".", ","))}</td>
                                                            <td>{getMoney((item.value * item.quantity).toString().replace(".", ","))}</td>
                                                        </tr>
                                                    )}
                                                    {stock.services.length === 0 &&
                                                        <NoEntity message={"Nenhum serviço encontrado."} count={5} />
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

                    <Accordion.Item eventKey="1" style={{marginBottom: 22}}>
                        <Accordion.Header>
                            <i className="material-icons float-left">assignment</i>
                            <h5>Lista de Produtos</h5>
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
                                                    {stock.items.map((item) => 
                                                        <tr>
                                                            <td>{item.id}</td>
                                                            <td>{item.product.product}</td>
                                                            <td>{item.quantity}</td>
                                                            <td>{getMoney(item.value.toString().replace(".", ","))}</td>
                                                            <td>{getMoney((item.value * item.quantity).toString().replace(".", ","))}</td>
                                                        </tr>
                                                    )}
                                                    {stock.services.length === 0 &&
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

                    <Accordion.Item eventKey="2">
                        <Accordion.Header>
                            <i className="material-icons float-left">sync</i>
                            <h5>Transferência de Produtos</h5>
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
                            <Form id="formShareItems" onSubmit={onSubmitProductsToShare} noValidate={true}>
                            <Row>
                                <Col xs={4}>
                                    <CustomInput key="cost_center_id" type="select"
                                        endpoint={"stocks"} endpoint_field={"name"}
                                        placeholder="Centro de Custo (Destino) *" name="cost_center_id" 
                                        required={true}
                                        value={state.cost_center_id}
                                        onChange={onChangeField} />
                                </Col>
                                <Col xs={4}>
                                    <CustomInput key="date" type="mask"
                                        mask={"99/99/9999"}
                                        maskPlaceholder={"Data de Transferência *"}
                                        placeholder="Data de Transferência *" name="date" 
                                        required={true}
                                        value={state.date}
                                        onChange={onChangeField} />
                                </Col>
                            </Row>
                            <Row>
                                <hr></hr>
                            </Row>
                            <Row>
                                <Col xs={4}>
                                    <CustomInput key="product_name" type="select"
                                                placeholder="Produto *" name="product_name"
                                                data={products}
                                                value={state.product_name}
                                                onChange={onChangeField} />
                                </Col>
                                <Col xs={4}>
                                    <CustomInput key="quantity" type="number"
                                            placeholder="Quantidade *" name="quantity"
                                            value={state.quantity}
                                            onChange={onChangeField} />
                                </Col>
                                <Col xs={2}>
                                    <CustomButton
                                        color={"success"}
                                        icon={"add"}
                                        style={{height: 55}}
                                        tooltip={"Adicionar"}
                                        name={"add_product_transfer"}
                                        onClick={onAddProductToShare}
                                    />
                                </Col>
                            </Row>
                            <Row>
                                <hr></hr>
                            </Row>
                            <Row>
                                <Col xs={12}>
                                    <Table responsive striped>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Descrição</th>
                                                <th>Quantidade</th>
                                                <th colSpan={2}>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {shareProducts.map((p) => 
                                                <tr>
                                                    <td>{p.editing}</td>
                                                    {!p.editing &&
                                                    <>
                                                    <td>{p.product.product}</td>
                                                    <td>{p.quantity}</td>
                                                    </>
                                                    }
                                                    {p.editing &&
                                                    <>
                                                    <td>
                                                        <CustomInput key={"product_name" + p.id} type="text"
                                                            placeholder="Produto *" name={"product_name" + p.id}
                                                            required={true}
                                                            disabled={"true"}
                                                            value={p.product.product} />
                                                    </td>
                                                    <td>
                                                        <CustomInput key={"quantity" + p.id} type="text"
                                                            placeholder="Quantidade *" name={"quantity" + p.id}
                                                            required={true}
                                                            value={p.quantity}
                                                            onChange={(evt) => onChangeQuantity(evt, p)} />
                                                    </td>
                                                    </>
                                                    }

                                                    <td>
                                                        {p.editing &&
                                                        <TableButton name="btnAdd" tooltip="Salvar" onClick={() => onSaveItem(p)}
                                                                icon="done" color="light" />
                                                        }
                                                        {!p.editing &&
                                                        <TableButton name="btnEdit" tooltip="Editar" onClick={() => onSetEditItem(p)}
                                                                icon="edit" color="light" />
                                                        }
                                                        <TableButton name="btnDelete" tooltip="Remover" onClick={() => onDeleteItem(p)}
                                                                icon="delete" color="light" />
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </Table>
                                </Col>
                                <Col xs={2}>
                                    <Button variant="success"
                                        type="submit"
                                        size="lg"
                                        disabled={loading}>
                                        {loading ? <Loading />
                                                    : 
                                                    'Concluir'}
                                    </Button>
                                </Col>
                            </Row>
                            </Form>
                            </>
                            }
                        </Accordion.Body>
                    </Accordion.Item>
                </Accordion>
            </Container>
        </>
    );
}

export default StockItems;