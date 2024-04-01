import React, { useEffect, useState } from "react";
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
import { getMoney } from "../../../services/Utils";

function StockItems() {

    const parameters = useParams();
    const [stock, setStock] = useState({items: []});
    const [total, setTotal] = useState(0);
    const [loading, setLoading] = useState(false);
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
    }

    const onErrorResponse = (response) => {
        setLoading(false);
        const res = response.response;
        if (res) {
            if (res.status === 401) {
                setHttpError({message: "Você precisa estar logado na aplicação para efetuar essa operação.", statusCode: res.status});
                return;
            }
            setHttpError({message: res.data.message, statusCode: res.status});
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
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
                    <Col>
                        <Card>
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
                                <Row>
                                    <Col>
                                        <b>Valor Total de Produtos: </b>{getMoney(total.toString().replace(".", ","))} 
                                    </Col>
                                </Row>

                                {!loading && 
                                <Row>
                                    <Col>
                                        Nesta página você pode gerenciar os produtos do centro de custo.
                                    </Col>
                                </Row>
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
                </Row>

                <Row>
                    <Col>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                <i className="material-icons float-left">description</i>
                                <p>Lista de Produtos</p>
                                </Card.Title>
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
                                            </tbody>
                                        </Table>
                                    </Col>
                                </Row>
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
}

export default StockItems;