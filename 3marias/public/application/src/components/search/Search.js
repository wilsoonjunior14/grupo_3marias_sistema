import React, { useState } from "react";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import CustomButton from "../button/Button";

const Search = ({fields, onSearch}) => {

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
                    <Card>
                        <Card.Body>
                            <Card.Title>
                                <i className="material-icons float-left">search</i>
                                Pesquisa
                            </Card.Title>
                            <Form>
                                <Row>
                                    {fields.map((field) => 
                                        <Col key={field.id} md={6} lg={4}>
                                            <FloatingLabel
                                                controlId={field.id + "Input"}
                                                label={field.placeholder}
                                                className="mb-3">
                                                <Form.Control type={field.type} placeholder={field.placeholder} 
                                                name={field.id} onChange={changeField}
                                                maxLength={field.maxlength} />
                                            </FloatingLabel>
                                        </Col>
                                    )
                                    }
                                </Row>
                                <Row>
                                    <Col md={6} lg={2}>
                                        <CustomButton color="success" onClick={() => onSearch(inputData)} name="Buscar" icon="search" />
                                    </Col>
                                </Row>
                                </Form>
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            <br></br>
        </>
    );
}

export default React.memo(Search);