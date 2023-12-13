import Container from "react-bootstrap/esm/Container";
import VHeader from "../../../components/vHeader/vHeader";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Error from '../../../components/error/Error';
import Success from '../../../components/success/Success';
import Loading from "../../../components/loading/Loading";
import CustomInput from "../../../components/input/CustomInput";
import '../../../App.css';
import { useEffect, useState } from "react";
import Table from "react-bootstrap/Table";
import CustomButton from "../../../components/button/Button";

const ShoppingOrdersForm = ({}) => {
    const [loading, setLoading] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [itemsSelected, setItemsSelected] = useState([]);

    const [products, setProducts] = useState([]);
    const [productSelected, setProductSelected] = useState({});

    const [leaveHappened, setLeaveHappened] = useState(false);

    useEffect(() => {
        setProducts([
            {
                id: 1,
                name: "Product 1"
            },
            {
                id: 2,
                name: "Product 2"
            },
            {
                id: 3,
                name: "Product 3"
            }
        ]);
    }, []);

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
        const newItems = itemsSelected.filter((p) => p.id !== product.id);
        setItemsSelected(newItems);
    }
    
    return (
        <>
        <VHeader />
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            {!loading && httpError &&
                <Error message={httpError.message} />
            }
            {!loading && httpSuccess &&
                <Success message={httpSuccess.message} />
            }
            <Row>
                <Col>
                    <Form>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Adicionar Ordem de Compra
                                </Card.Title>
                                <Row>
                                    <Col>
                                        <CustomInput key="nrRecibo" type="text" placeholder="Nr. Recibo" name="nr_ticket" maxlength="255" />
                                    </Col>
                                    <Col>
                                        <CustomInput key="partner" placeholder="Fornecedor" type="select" data={["Fornecedor A", "Fornecedor B"]} name="partner_id" />
                                    </Col>
                                    <Col>
                                        <CustomInput key="date" placeholder="Data" type="mask" name="date" mask="99/99/9999" maskPlaceholder="Data" maxlength="255" />
                                    </Col>
                                </Row>
                                <Row>
                                    <Col>
                                        <CustomInput key="status" placeholder="Status" type="select" data={["Em Andamento", "Cancelada"]} name="status" />
                                    </Col>
                                    <Col>
                                        <CustomInput key="cost_center" placeholder="Centro de Custo" type="select" data={["Centro de Custo A", "Centro de Custo B"]} name="cost_center_id" />
                                    </Col>
                                    <Col>
                                        <CustomInput key="attachment" placeholder="Comprovante" type="file" name="attachment" />
                                    </Col>
                                </Row>
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
                                                                                <td>{product.name}</td>
                                                                            </tr>
                                                                        )}
                                                                    </tbody>
                                                                </Table>
                                                            </Col>
                                                        </Row>
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
                                                                <Table 
                                                                    onDragLeave={() => {setLeaveHappened(true)}}>
                                                                    <thead onDragLeave={() => {setLeaveHappened(true)}}>
                                                                        <tr>
                                                                            <th>ID</th>
                                                                            <th>Nome</th>
                                                                            <th>Quantidade</th>
                                                                            <th>Valor Unitário</th>
                                                                            <th>Opções</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody onDragLeave={() => {setLeaveHappened(true)}}>
                                                                        {itemsSelected.map((item) => 
                                                                            <tr>
                                                                                <td>{item.id}</td>
                                                                                <td>
                                                                                    <input type="text" maxLength="255" placeholder="Descrição" value={item.name} />
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min={1} max={9999} placeholder="Quantidade" />
                                                                                </td>
                                                                                <td>
                                                                                    <input type="number" min={1} max={9999} placeholder="Valor Unitário" />
                                                                                </td>
                                                                                <td>
                                                                                    <CustomButton
                                                                                    onClick={() => removeProduct(item)} 
                                                                                    color="light" icon="delete" tooltip="Remover Item de Compra" 
                                                                                    key="btn_delete_shopping_item" name="btn_delete_shopping_item" />
                                                                                </td>
                                                                            </tr>
                                                                        )}
                                                                    </tbody>
                                                                </Table>
                                                            </Col>
                                                        </Row>
                                                    </Card.Body>
                                                </Card>
                                            </Col>
                                        </Row>
                                    </Col>
                                </Row>
                            </Card.Body>
                        </Card>
                    </Form>
                </Col>
            </Row>
        </Container>
        </>
    )
};

export default ShoppingOrdersForm;
