import React from "react";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Nav from 'react-bootstrap/Nav';
import Button from 'react-bootstrap/Button';
import Carousel from 'react-bootstrap/Carousel';

import "./StartPage.css";
import background from "../../assets/background1.jpg";
import logoFull from "../../assets/logo-full.png";

function StartPage() {
    return (
        <Container fluid style={{fontFamily: "Avenir Next"}}>
            <Row className="section1" style={{maxHeight: "100vh", marginLeft: -25}}>
                <Nav as="ul" style={{position: "absolute", left: "90vw", maxWidth: 0}}>
                    <Nav.Item as="li">
                        <Nav.Link href="/login" eventKey="link-2" style={{marginTop: 16, zIndex: 1000}}>
                            <Button variant="light" className="btn-sign-in btn btn-outline-light" size="lg" style={{backgroundColor: "transparent"}}>
                                ENTRAR
                            </Button>
                        </Nav.Link>
                    </Nav.Item>
                </Nav>

                <Col xs={12} style={{backgroundColor: "black", zIndex: -2}}>

                <img src={background} 
                    style={{
                    width: "100%",
                    position: "fixed",
                    zIndex: -1,
                    opacity: 0.7
                    }} />

                <Row style={{height: "100%"}}>
                    <Col lg={4}></Col>
                    <Col lg={4}>
                        <img src={logoFull} 
                        style={{transform: "translate(-10%, 45%)"}} />
                    </Col>
                    <Col lg={4}></Col>
                </Row>

                </Col>
            </Row>

            <Row className="section" style={{background: "red", backgroundColor: "white", paddingTop: 50}}>
                <Col xs={12} md={6} lg={3}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <i className="material-icons" style={{fontSize: 70}}>highlight</i>
                        </Col>
                        <Col className="text-center" xs={12}><h4>Qualidade e Inovação</h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-match-parent"}}>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                        </Col>
                    </Row>
                </Col>
                <Col xs={12} md={6} lg={3}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <i className="material-icons" style={{fontSize: 70}}>tag_faces</i>
                        </Col>
                        <Col className="text-center" xs={12}><h4>Compromisso</h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-match-parent"}}>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                        </Col>
                    </Row>
                </Col>
                <Col xs={12} md={6} lg={3}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <i className="material-icons" style={{fontSize: 70}}>business_center</i>
                        </Col>
                        <Col className="text-center" xs={12}><h4>Experiência</h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-match-parent"}}>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                        </Col>
                    </Row>
                </Col>
                <Col xs={12} md={6} lg={3}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <i className="material-icons" style={{fontSize: 70}}>person_pin</i>
                        </Col>
                        <Col className="text-center" xs={12}><h4>Atendimento Personalizado</h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-match-parent"}}>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                        </Col>
                    </Row>
                </Col>
            </Row>

            <Row className="section">

                <Col xs={12} style={{zIndex: -2}}>

                <img src={background} 
                    style={{
                    width: "100%",
                    position: "fixed",
                    zIndex: -1,
                    opacity: 0.7
                    }} />

                <Row>
                    <Col xs={12} className="text-center" style={{marginTop: 190, fontSize: 30}}>
                        A modern responsive front-end framework based on Material Design
                    </Col>
                </Row>

                </Col>
            </Row>

            <Row className="section" style={
                    {background: "white", 
                    minHeight: "500px", 
                    backgroundColor: "white",
                    paddingTop: 50              
                }}>
                <Col xs={12} style={{padding: 0}}>
                    <Carousel>
                        <Carousel.Item style={{
                                minHeight: 400,
                                backgroundImage: "url('https://picsum.photos/seed/picsum/1500/1500')",
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                                backgroundRepeat: "no-repeat"}}>
                        
                            <img
                                style={{
                                maxWidth: "500px",
                                maxheight: "500px",
                            }} />

                            <Carousel.Caption>
                            <h3>First slide label</h3>
                            <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                            </Carousel.Caption>
                        </Carousel.Item>
                        <Carousel.Item style={{
                                minHeight: 400,
                                backgroundImage: "url('https://picsum.photos/seed/picsum/1500/1500')",
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                                backgroundRepeat: "no-repeat"}}>
                        
                            <img
                                style={{
                                maxWidth: "500px",
                                maxheight: "500px",
                            }} />

                            <Carousel.Caption>
                            <h3>First slide label</h3>
                            <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                            </Carousel.Caption>
                        </Carousel.Item>
                        <Carousel.Item style={{
                                minHeight: 400,
                                backgroundImage: "url('https://picsum.photos/seed/picsum/1500/1500')",
                                backgroundSize: "cover",
                                backgroundPosition: "center",
                                backgroundRepeat: "no-repeat"}}>
                        
                            <img
                                style={{
                                maxWidth: "500px",
                                maxheight: "500px",
                            }} />

                            <Carousel.Caption>
                            <h3>First slide label</h3>
                            <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                            </Carousel.Caption>
                        </Carousel.Item>
                    </Carousel>
                </Col>
            </Row>

            <Row className="section">
                <Col xs={12} style={{zIndex: -2}}>
                <img src={background} 
                    style={{
                    width: "100%",
                    position: "fixed",
                    zIndex: -1,
                    opacity: 0.7
                    }} />
                <Row>
                    <Col xs={12} className="text-center" style={{marginTop: 190, fontSize: 30}}>
                        A modern responsive front-end framework based on Material Design
                    </Col>
                </Row>
                </Col>
            </Row>

            <Row className="section" style={
                    {background: "red", 
                    minHeight: "380px", 
                    backgroundColor: "white"                  
                }}>
                <Col xs={12} className="text-center" style={{padding: 30}}>
                    <h3>Venha nos Visitar</h3>
                </Col>
                <Col xs={12} style={{padding: 0}}>
                    <div id="map" style={{width: "100%", height: "380px"}}></div>
                </Col>
            </Row>

            <Row className="section" style={{
                    minHeight: "400px", 
                    background: "#0C3472"                  
                }}>
                <Col xs={12}>
                </Col>
            </Row>
        </Container>
    );
}

export default StartPage;