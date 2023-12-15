import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import Search from "../../../components/search/Search";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import "./Constructions.css";
import Button from "react-bootstrap/esm/Button";

export default function ConstructionsList() {
    
    const fields = [
        {
            id: 'code',
            placeholder: 'Código',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 255
        }
    ];

    const onSearch = () => {

    };

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <Search fields={fields} onSearch={onSearch} />
                <Row className="animate__animated animate__slideInUp">
                    <Col lg={4}>
                        <Card className="constructions">
                            <Card.Body>
                                <Card.Title className={"constructions-row"}>
                                    <i className="material-icons float-left text-danger" style={{float: "left"}}>fiber_manual_record</i>
                                    Empreendimento Descrição
                                    <i className="material-icons float-right" style={{float: "right"}}>edit</i>
                                </Card.Title>
                                <Row className={"constructions-row-divider"}>
                                    <Col className={"constructions-col"}>
                                        <i className={"material-icons"}>location_city</i>
                                    </Col>
                                </Row>
                                <Row className={"constructions-row-divider"}>
                                    <Col>
                                        <i className="material-icons float-left">location_on</i>
                                        Rua Av. Dep. Àlvaro Soares, Ibiapina
                                    </Col>
                                </Row>
                                <Row>
                                    <Col xs={12} className={"constructions-row-divider"}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Global: R$ XXXXXX,XX
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Contratado: R$ XXXXXX,XX
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Pago: <span>R$ XXXXXX,XX</span>
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor A Receber: R$ XXXXXX,XX
                                    </Col>
                                </Row>
                                <Row style={{marginTop: 20}}>
                                    <Col>
                                        <Button variant="success">
                                            Habilitar para Propostas/Vendas
                                        </Button>
                                    </Col>
                                </Row>
                            </Card.Body>
                        </Card>
                    </Col>

                    <Col lg={4}>
                        <Card className="constructions">
                            <Card.Body>
                                <Card.Title className={"constructions-row"}>
                                    <i className="material-icons float-left text-danger" style={{float: "left"}}>fiber_manual_record</i>
                                    Empreendimento Descrição
                                    <i className="material-icons float-right" style={{float: "right"}}>edit</i>
                                </Card.Title>
                                <Row className={"constructions-row-divider"}>
                                    <Col className={"constructions-col"}>
                                        <img src="https://www.pngplay.com/wp-content/uploads/7/Home-House-PNG-Clipart-Background.png"
                                        style={{maxWidth: 300}} />
                                    </Col>
                                </Row>
                                <Row className={"constructions-row-divider"}>
                                    <Col>
                                        <i className="material-icons float-left">location_on</i>
                                        Rua Av. Dep. Àlvaro Soares, Ibiapina
                                    </Col>
                                </Row>
                                <Row>
                                    <Col xs={12} className={"constructions-row-divider"}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Global: R$ XXXXXX,XX
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Contratado: R$ XXXXXX,XX
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Pago: <span>R$ XXXXXX,XX</span>
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor A Receber: R$ XXXXXX,XX
                                    </Col>
                                </Row>
                                <Row style={{marginTop: 20}}>
                                    <Col>
                                        <Button variant="success">
                                            Habilitar para Propostas/Vendas
                                        </Button>
                                    </Col>
                                </Row>
                            </Card.Body>
                        </Card>
                    </Col>

                    <Col lg={4}>
                        <Card className="constructions">
                            <Card.Body>
                                <Card.Title className={"constructions-row"}>
                                    <i className="material-icons float-left text-success" style={{float: "left"}}>fiber_manual_record</i>
                                    Empreendimento Descrição
                                    <i className="material-icons float-right" style={{float: "right"}}>edit</i>
                                </Card.Title>
                                <Row className={"constructions-row-divider"}>
                                    <Col className={"constructions-col"}>
                                        <img src="https://imagensemoldes.com.br/wp-content/uploads/2020/05/Casa-PNG.png" 
                                            style={{maxWidth: 300}} />
                                    </Col>
                                </Row>
                                <Row className={"constructions-row-divider"}>
                                    <Col>
                                        <i className="material-icons float-left">location_on</i>
                                        Rua Av. Dep. Àlvaro Soares, Ibiapina
                                    </Col>
                                </Row>
                                <Row>
                                    <Col xs={12} className={"constructions-row-divider"}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Global: R$ XXXXXX,XX
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Contratado: R$ XXXXXX,XX
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor Pago: <span>R$ XXXXXX,XX</span>
                                    </Col>
                                    <Col xs={12}>
                                        <i className="material-icons float-left">chevron_right</i>
                                        Valor A Receber: R$ XXXXXX,XX
                                    </Col>
                                </Row>
                                <Row style={{marginTop: 20}}>
                                    <Col>
                                        <Button disabled={true} variant="success">
                                            Habilitar para Propostas/Vendas
                                        </Button>
                                    </Col>
                                </Row>
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
}