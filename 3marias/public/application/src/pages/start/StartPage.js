import React from "react";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Button from 'react-bootstrap/Button';
import Carousel from 'react-bootstrap/Carousel';

import "./StartPage.css";
import logo from "../../assets/logo_complete.png";
import gmail from "../../assets/gmail.png";
import instagram from "../../assets/instagram.png";
import whatsapp from "../../assets/whatsapp.png";
import facebook from "../../assets/facebook.png";

function StartPage() {
    return (
        <Container fluid style={{fontFamily: "Avenir Next"}}>
            <Row className="section1">
                <Col xs={12}>
                    <Row style={{padding: 16}}>
                        <Col xs={8} lg={10}></Col>
                        <Col xs={2} lg={1}>
                            <a href="/login">
                                <Button variant="light" className="btn-sign-in btn btn-outline-light" size="lg" style={{backgroundColor: "transparent"}}>
                                    ENTRAR
                                </Button>
                            </a>
                        </Col>
                        <Col xs={2} lg={1}></Col>
                    </Row>
                </Col>

                <Col xs={12} style={{}}>
                    <Row>
                        <Col xs={12} lg={4}></Col>
                        <Col className="logo-full" xs={12} lg={4}></Col>
                        <Col xs={12} lg={4}></Col>
                    </Row>
                </Col>
            </Row>

            <Row className="section" style={{backgroundColor: "white", paddingTop: 50, minHeight: 320}}>
                <Col xs={12} md={4}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <span>+ de 12 anos</span>
                        </Col>
                        <Col className="text-center" xs={12}><h4></h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-center"}}>
                            <p>
                                na área da construção civil realizando sonhos entregando obras e projetos inovadores e de qualidade.
                                na área da construção civil realizando sonhos entregando obras e projetos inovadores e de qualidade.
                            </p>
                        </Col>
                    </Row>
                </Col>
                <Col xs={12} md={4}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <i className="material-icons" style={{fontSize: 70}}>tag_faces</i>
                        </Col>
                        <Col className="text-center" xs={12}><h4>Compromisso</h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-center"}}>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                        </Col>
                    </Row>
                </Col>
                <Col xs={12} md={4}>
                    <Row>
                        <Col className="text-center" xs={12}>
                            <i className="material-icons" style={{fontSize: 70}}>business_center</i>
                        </Col>
                        <Col className="text-center" xs={12}><h4>Experiência</h4></Col>
                        <Col xs={12}>
                            <p></p>
                        </Col>
                        <Col xs={12} style={{textAlign: "-webkit-center"}}>
                        is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                        </Col>
                    </Row>
                </Col>
            </Row>

            <Row className="section1" style={{height: 330}}>
                <Col xs={12}>
                <Row>
                    <Col xs={12} md={8} className="text-center" 
                        style={{marginTop: 130, fontSize: 30, backgroundColor: "white", opacity: 0.8, padding: 20, borderRadius: "0 50px 50px 0"}}>
                        <p style={{brightness: "20%"}}>A Construtora 3 Marias se destaca pela sua capacidade de se adaptar às mudanças do mercado e de inovar em seus processos e projetos.</p>
                    </Col>
                    <Col xs={12} md={4}></Col>
                </Row>
                </Col>
            </Row>

            <Row className="section1" style={{height: 450}}>
                <Col xs={12}>
                <Row>
                    <Col xs={12} md={4}></Col>
                    <Col xs={12} md={8} className="text-center" 
                    style={{marginTop: 130, fontSize: 30, backgroundColor: "white", opacity: 0.8, padding: 20, borderRadius: "50px 0 0 50px"}}>
                        <p style={{brightness: "20%"}}>A Construtora 3 Marias se destaca pela sua capacidade de se adaptar às mudanças do mercado e de inovar em seus processos e projetos.</p>
                    </Col>
                </Row>
                </Col>
            </Row>

            <Row className="section" style={
                    {background: "white", 
                    minHeight: "500px", 
                    backgroundColor: "white"            
                }}>
                <Col xs={12} className="text-center" style={{padding: 30}}>
                    <h3>Galeria de Fotos</h3>
                </Col>
                <Col xs={12} style={{padding: 0}}>
                    <Carousel>
                        <Carousel.Item style={{
                                minHeight: 400,
                                backgroundImage: "url('https://picsum.photos/seed/picsum/1500/1500')",
                                backgroundSize: "cover",
                                backgroundAttachment: "fixed",
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
                                backgroundAttachment: "fixed",
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
                                backgroundAttachment: "fixed",
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

            <Row className="section text-white text-center" style={{ 
                    background: "#0C3472",
                    minHeight: 320               
                }}>
                <Col xs={12}>
                    <Row>
                        <Col xs={12} md={4}>
                            <img src={logo} style={{maxHeight: 300, maxWidth: 300}} />
                        </Col>
                        <Col className="remove-padding" xs={12} md={4} style={{paddingTop: "10%"}}>
                            <Row>
                                <Col xs={12}>Rua Durval Ferreira de Assis, N 458</Col>
                                <Col xs={12}>Ibiapina, Ceará</Col>
                            </Row>
                        </Col>
                        <Col xs={12} md={4}>
                            <Row className="remove-padding" style={{paddingTop: "30%"}}>
                                <Col xs={2}></Col>
                                <Col xs={1}>
                                    <img src={gmail} width={30} height={30} />
                                </Col>
                                <Col xs={1}></Col>
                                <Col xs={1}>
                                    <img src={whatsapp} width={30} height={30} />
                                </Col>
                                <Col xs={1}></Col>
                                <Col xs={1}>
                                    <img src={instagram} width={30} height={30} />
                                </Col>
                                <Col xs={1}></Col>
                                <Col xs={1}>
                                    <img src={facebook} width={30} height={30} />
                                </Col>
                                <Col xs={1}></Col>
                            </Row>
                        </Col>
                    </Row>
                    <Row>
                        <Col xs={12}>© Todos os direitos reservados</Col>
                    </Row>
                </Col>
            </Row>
        </Container>
    );
}

export default StartPage;