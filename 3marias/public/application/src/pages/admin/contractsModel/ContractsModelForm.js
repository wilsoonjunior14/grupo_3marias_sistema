import Container from "react-bootstrap/Container";
import VHeader from "../../../components/vHeader/vHeader";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';
import React, { Component, useState } from 'react';
import Editor from 'react-simple-wysiwyg'; // https://www.npmjs.com/package/react-simple-wysiwyg

function ContractsModelForm() {

    const [html, setHtml] = useState('my <b>HTML</b>');
    
    function onChange(e) {
        setHtml(e.target.value);
    }

    return (
            <>
        <VHeader />
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col>
                    <Card>
                        <Card.Body>
                            <Card.Title>
                                <i className="material-icons float-left">add</i>
                                Adicionar Modelo de Contrato
                            </Card.Title>
                            <Row>
                                <Col>
                                    <Tabs
                                    defaultActiveKey="edit"
                                    id="uncontrolled-tab-example"
                                    className="mb-3">
                                        <Tab eventKey="edit" title="Editar">
                                            <Row>
                                                <Col>
                                                    <Editor value={html} onChange={onChange} />
                                                </Col>
                                            </Row>
                                        </Tab>
                                        <Tab eventKey="preview" title="Visualizar">
                                            <Row>
                                                <Col>
                                                    <object
                                                        style={{width: "100%", minHeight: "calc(80vh)"}} 
                                                        data="https://www.thecampusqdl.com/uploads/files/pdf_sample_2.pdf" type="application/pdf">
                                                        <p>Seu navegador não tem um plugin pra PDF</p>
                                                    </object>
                                                </Col>
                                            </Row>
                                        </Tab>
                                    </Tabs>
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

export default ContractsModelForm;