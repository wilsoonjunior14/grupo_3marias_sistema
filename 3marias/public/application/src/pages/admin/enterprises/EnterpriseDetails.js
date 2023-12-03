import React, { useEffect, useState } from "react";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import "../../../App.css";
import Container from "react-bootstrap/esm/Container";
import VHeader from "../../../components/vHeader/vHeader";
import Accordion from 'react-bootstrap/Accordion';
import Table from "react-bootstrap/Table";
import CustomButton from "../../../components/button/Button";
import NoEntity from "../../../components/table/NoEntity";

function EnterpriseDetails() {

    const [accounters, setAccounters] = useState([]);

    useEffect(() => {
        setAccounters([
            {
                id: 1,
                name: "John Doe",
                phone: "(00)00000-0000",
                address: "Main Avenue"
            }
        ])
    }, []);

    return (
        <>
        <VHeader />
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col>
                    <Card>
                        <Card.Body>
                            <Card.Title>
                            <i className="material-icons float-left">business_center</i>
                            Grupo 3 Marias
                            </Card.Title>
                            <Row>
                                <Col><b>Nome Fantasia:</b> Grupo 3 Marias</Col>
                            </Row>
                            <Row>
                                <Col><b>Razão Social:</b> Grupo 3 Marias</Col>
                            </Row>
                            <Row>
                                <Col><b>CNPJ:</b> 281039810293\0001</Col>
                            </Row>
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Row>
                <Col>
                <Accordion>
                    <Accordion.Item eventKey="0">
                        <Accordion.Header>Informações Gerais</Accordion.Header>
                        <Accordion.Body>
                            <Row>
                                <Col><b>Creci:</b> </Col>
                            </Row>
                            <Row>
                                <Col><b>Inscrição Estadual:</b> </Col>
                                <Col><b>Inscrição Municipal:</b> </Col>
                            </Row>
                            <Row>
                                <Col><b>Telefone:</b> </Col>
                            </Row>
                            <Row>
                                <Col><b>Endereço:</b> </Col>
                                <Col><b>Complemento:</b> </Col>
                            </Row>
                            <Row>
                                <Col><b>Bairro:</b> </Col>
                                <Col><b>CEP:</b> </Col>
                            </Row>
                            <Row>
                                <Col><b>Cidade:</b> </Col>
                                <Col><b>Estado:</b> </Col>
                            </Row>
                            
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="1">
                        <Accordion.Header>Contador</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            <Col xs="12">
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Telefone</th>
                                                <th>Endereço</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {accounters.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>{item.phone}</td>
                                                <td>{item.address}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton name="btnEdit" tooltip="Editar"
                                                                icon="edit" color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton name="btnDelete" tooltip="Deletar"
                                                                icon="delete" color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }

                                            {accounters.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="2">
                        <Accordion.Header>Sócios</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            <Col xs="12">
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Estado Civil</th>
                                                <th>Profissão</th>
                                                <th>Endereço</th>
                                                <th>Telefone</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {accounters.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>email@gmail.com</td>
                                                <td>Casado</td>
                                                <td>Administrador</td>
                                                <td>{item.address}</td>
                                                <td>{item.phone}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton name="btnEdit" tooltip="Editar"
                                                                icon="edit" color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton name="btnDelete" tooltip="Deletar"
                                                                icon="delete" color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }

                                            {accounters.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                            </Col>
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="3">
                        <Accordion.Header>Representantes Legais da Empresa</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            <Col xs="12">
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Estado Civil</th>
                                                <th>Profissão</th>
                                                <th>Endereço</th>
                                                <th>Telefone</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {accounters.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>email@gmail.com</td>
                                                <td>Casado</td>
                                                <td>Administrador</td>
                                                <td>{item.address}</td>
                                                <td>{item.phone}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton name="btnEdit" tooltip="Editar"
                                                                icon="edit" color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton name="btnDelete" tooltip="Deletar"
                                                                icon="delete" color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }

                                            {accounters.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="4">
                        <Accordion.Header>Filiais</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            <Col xs="12">
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>CNPJ</th>
                                                <th>Endereço</th>
                                                <th>Telefone</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <NoEntity message="Nenhum resultado encontrado." />
                                        </tbody>
                                    </Table>
                                </Col>
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="5">
                        <Accordion.Header>Arquivos</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            <Col xs="12">
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <NoEntity message="Nenhum resultado encontrado." />
                                        </tbody>
                                    </Table>
                                </Col>
                        </Accordion.Body>
                    </Accordion.Item>
                </Accordion>
                </Col>
            </Row>
        </Container>
        </>
    )
};

export default EnterpriseDetails;
