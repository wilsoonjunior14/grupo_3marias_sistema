import React from "react";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import { useNavigate } from "react-router-dom";
import "./Style.css";

export default function ChooseType() {
    const navigate = useNavigate();

    const redirectToCreateUser = () => {
        navigate("/register/user");
    };

    const redirectToCreateEnterprise = () => {
        navigate("/register/enterprise");
    };

    return (
        <div className="display">
            <div style={{flex: 1}}></div>
            <div style={{flex: 1}}>
                <Container fluid>
                    <Row style={{textAlign: 'center', color: 'white'}}>
                        <Col xs={12}>
                            <h5 style={{fontSize: '30px', fontWeight: 'bold'}}>Que tipo de conta você deseja criar?</h5>
                        </Col>
                        <Col xs={12}>
                            <br></br>
                        </Col>
                        <Col md={5} lg={5}>
                            <Card className="custom-card" onClick={redirectToCreateUser}>
                                <Card.Header>
                                    <i style={{fontSize: '150px'}} className="material-icons">person_pin</i>
                                </Card.Header>
                                <Card.Body>
                                    <Card.Title>
                                        Para mim mesmo
                                    </Card.Title>
                                </Card.Body>
                            </Card>
                        </Col>
                        <Col md={2} lg={2}>
                            
                        </Col>
                        <Col md={5} lg={5}>
                            <Card className="custom-card" onClick={redirectToCreateEnterprise}>
                                <Card.Header>
                                    <i style={{fontSize: '150px'}} className="material-icons">business</i>
                                </Card.Header>
                                <Card.Body>
                                    <Card.Title>
                                        Para meu negócio
                                    </Card.Title>
                                </Card.Body>
                            </Card>
                        </Col>
                    </Row>
                </Container>
            </div>
            <div style={{flex: 1}}></div>    
        </div>
    );
};