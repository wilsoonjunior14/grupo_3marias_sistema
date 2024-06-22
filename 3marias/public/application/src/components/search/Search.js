import React, { useState } from "react";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import CustomButton from "../button/Button";
import Button from "react-bootstrap/esm/Button";
import Accordion from 'react-bootstrap/Accordion';
import CustomInput from '../input/CustomInput';

const Search = ({fields, onSearch, onReset}) => {

    const [inputData, setInputData] = useState({});

    const changeField = (e) => {
        const name = e.target.name;
        const value = e.target.value;
        setInputData(values => ({...values, [name]: value}));
    };

    return (
        <>
            <Row>
                <Col>
                    <Accordion>
                        <Accordion.Item eventKey="0">
                            <Accordion.Header>
                                <i className="material-icons float-left">search</i>
                                <h5 style={{marginTop: 10}}>Filtros de Pesquisa</h5>
                            </Accordion.Header>
                            <Accordion.Body>
                                <Row>
                                    <Col>
                                        <Card style={{margin: -12, border: "none"}}>
                                        <Card.Body>
                                            <Form>
                                                <Row>
                                                    {fields.map((field) => 
                                                        <Col key={field.id} md={6} lg={4}>
                                                            <CustomInput
                                                                key={field.id + "Input"}
                                                                name={field.id}
                                                                type={field.type}
                                                                maxlength={field.maxlength}
                                                                mask={field.mask}
                                                                maskPlaceholder={field.maskPlaceholder}
                                                                placeholder={field.placeholder}
                                                                onChange={changeField}
                                                            />
                                                        </Col>
                                                    )
                                                    }
                                                </Row>
                                                <br></br>
                                                <Row>
                                                    <Col md={6} lg={2}>
                                                        <CustomButton color="success" onClick={() => onSearch(inputData)} name="Buscar" icon="search" />
                                                    </Col>
                                                    <Col md={6} lg={2}>
                                                        <div className="d-grid gap-2">
                                                            <Button type="reset" size="lg" key="resetButton" 
                                                                onClick={() => {setInputData({}); onReset();}} variant={"light"}>
                                                            <i className="material-icons float-left">refresh</i>
                                                            Limpar
                                                            </Button>
                                                        </div>
                                                    </Col>
                                                </Row>
                                                </Form>
                                            </Card.Body>
                                        </Card>
                                    </Col>
                                </Row>
                            </Accordion.Body>
                        </Accordion.Item>
                    </Accordion>
                    </Col>
                </Row>
            <br></br>
        </>
    );
}

export default React.memo(Search);