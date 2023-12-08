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
import { useState } from "react";
import Table from "react-bootstrap/Table";
import CustomButton from "../../../components/button/Button";

const ShoppingOrdersForm = ({}) => {
    const [loading, setLoading] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    
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
                                            <Col>
                                                <CustomInput key="category_service" placeholder="Categoria do Serviço" type="select" data={["Alvenaria", "Pintura", "Acabamento"]} name="category_service_id" />
                                            </Col>
                                            <Col>
                                                <CustomInput key="service" placeholder="Serviço" type="select" data={["S1", "S2", "S3", "S4"]} name="service_id" />
                                            </Col>
                                            <Col>
                                                <CustomButton style={{height: 58}} color="success" icon="add" tooltip="Adicionar Item de Compra" key="btn_add_shopping_item" name="btn_add_shopping_item" />
                                            </Col>
                                        </Row>
                                        <Row>
                                            <Col>
                                                <Table striped responsive>
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Descrição</th>
                                                            <th>Quantidade</th>
                                                            <th>Valor Unitátio</th>
                                                            <th>Valor Total</th>
                                                            <th>Opções</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>
                                                                <input type="text" maxLength="255" placeholder="Descrição" />
                                                            </td>
                                                            <td>
                                                                <input type="number" placeholder="Quantidade" />
                                                            </td>
                                                            <td>
                                                                <input type="number" placeholder="Valor Unitário" />
                                                            </td>
                                                            <td>
                                                                R$ 100
                                                            </td>
                                                            <td>
                                                            <CustomButton color="light" icon="delete" tooltip="Remover Item de Compra" key="btn_delete_shopping_item" name="btn_delete_shopping_item" />
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </Table>
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
