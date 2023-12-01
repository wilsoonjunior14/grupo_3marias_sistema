import React from "react";
import Card from "react-bootstrap/Card";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import Button from "react-bootstrap/Button";
import { useNavigate } from "react-router-dom";
import "./Style.css";

export default function RegisterSuccess() {

    const navigate = useNavigate();

    return (
        <div className="display">
            <div style={{flex: 1}}></div>
            <div style={{flex: 1}}>
                <Container fluid>
                    <Row style={{textAlign: 'center', color: 'white'}}>
                        <Col>
                            <img src="http://localhost:5000/img/logo_complete.png" />
                            <Card style={{ width: '40rem', margin: '0 auto', border: 'none', backgroundColor: '#31B573' }}>
                                <Card.Body>
                                    <Form>
                                        <Row style={{color: 'white'}}>
                                            <Col xs={12}>
                                            <iframe src="https://embed.lottiefiles.com/animation/99684"></iframe>
                                            </Col>
                                            <Col xs={12}>
                                                <h5 style={{fontSize: '30px', fontWeight: 'bold'}}>Cadastro efetuado com sucesso!</h5>
                                            </Col>
                                            <Col xs={12}>
                                                <h6>
                                                    Obrigado por se cadastrar em nosso website! Agradecemos por fazer parte da nossa comunidade.
                                                    Esperamos que você desfrute da nossa plataforma e encontre valor em nossos serviços. Se tiver alguma dúvida ou precisar de assistência, nossa equipe está pronta para ajudar.
                                                    Bem-vindo(a) e aproveite ao máximo tudo o que temos a oferecer!
                                                </h6>
                                            </Col>
                                            <Col>
                                            <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                                <Button className="custom-btn" variant="success"
                                                    type="submit"
                                                    size="lg"
                                                    onClick={() => navigate("/login")}>
                                                    Acessar a Plataforma
                                                    <i style={{float: 'right', paddingTop: 3}} className="material-icons">trending_up</i>
                                                </Button>
                                            </div>
                                            </Col>
                                        </Row>
                                    </Form>
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