import React, { useEffect, useState } from "react";
import VHeader from "../../components/vHeader/vHeader";
import Container from "react-bootstrap/Container";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import "../../App.css";
import { performRequest } from '../../services/Api';
import Loading from "../../components/loading/Loading";
import Error from "../../components/error/Error";
import Success from "../../components/success/Success";
import { retrieveUserData } from "../../services/Storage";
import CustomInput from "../../components/input/CustomInput";
import Button from "react-bootstrap/esm/Button";
import Form from 'react-bootstrap/Form';
import MD5 from "crypto-js/md5";

export default function AccountForm() {

    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [loading, setLoading] = useState(false);
    const [userdata, setUserdata] = useState({});
    const [data, setData] = useState({});

    useEffect(() => {
        onGetUser();
    }, []);

    const onGetUser = () => {
        const userdata = retrieveUserData();
        if (!userdata) {
            return;
        }
        setData(userdata.user);
        setUserdata(userdata.user);
    }

    const onChangeField = (evt) => {
        const {name, value} = evt.target;
        data[name] = value;
        setData(data);
    }

    const onSubmit = (evt) => {
        evt.preventDefault();

        // Form Validation
        const form = document.getElementById("form");
        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        } else {
            form.classList.remove("was-validated");
        }

        setLoading(true);

        const payload = {
            id: userdata.id,
            name: userdata.name,
            email: userdata.email,
            active: true,
            group_id: userdata.group_id,
            password: MD5(data.new_password).toString(),
            conf_password: MD5(data.conf_password).toString()
        }

        performRequest("PUT", "/v1/users/" + data.id, payload)
        .then(successUpdatePassword)
        .catch(errorUpdatePassword);
    }

    const successUpdatePassword = (res) => {
        setLoading(false);
        setHttpSuccess({message: "Senha alterada com sucesso!"});
        onGetUser();
    }

    const errorUpdatePassword = (response) => {
        setLoading(false);
        // TODO: the lines below can be improved
        if (response.response) {
            if (response.response.status === 404) {
                setHttpError("Não foi possível conectar-se com o servidor.");
                return;
            }
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    }

    return (
        <>
            <VHeader />
            <Container id='app-container' className="home-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <Row>
                    <Col>
                        {httpError &&
                        <Error message={httpError.message} />
                        }
                        {httpSuccess &&
                        <Success message={httpSuccess.message} />
                        }
                    </Col>
                </Row>
                <Row>
                    <Col>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                    <i className="material-icons float-left">account_circle</i>
                                    Minha Conta
                                </Card.Title>
                                <Row>
                                    <Col xs={4}>
                                        <CustomInput name={"name"} type={"text"} 
                                            disabled={"true"} placeholder={"Nome Completo"}
                                            value={data.name} />
                                    </Col>
                                    <Col xs={4}>
                                        <CustomInput name={"email"} type={"text"} 
                                            disabled={"true"} placeholder={"Email"}
                                            value={data.email} />
                                    </Col>
                                </Row>
                            </Card.Body>
                        </Card>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                    <i className="material-icons float-left">lock</i>
                                    Alterar Senha
                                </Card.Title>
                                <Form id={"form"} onSubmit={onSubmit} noValidate={true}>
                                    <Row>
                                        <Col xs={4}>
                                            <CustomInput name={"new_password"} type={"password"} 
                                                required={true} placeholder={"Senha *"}
                                                onChange={onChangeField}
                                                value={data.new_password} />
                                        </Col>
                                        <Col xs={4}>
                                            <CustomInput name={"conf_password"} type={"password"} 
                                                required={true} placeholder={"Confirmar Senha *"}
                                                onChange={onChangeField}
                                                value={data.conf_password} />
                                        </Col>
                                    </Row>
                                    <Row>
                                            <Col lg={2}>
                                                <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                                    <Button variant="success"
                                                        type="submit"
                                                        size="lg"
                                                        disabled={loading}>
                                                        {loading ? <Loading />
                                                                    : 
                                                                    'Salvar'}
                                                    </Button>
                                                </div>
                                            </Col>
                                            <Col lg={10}></Col>
                                    </Row>
                                </Form>
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>

            </Container>
        </>
    );
};