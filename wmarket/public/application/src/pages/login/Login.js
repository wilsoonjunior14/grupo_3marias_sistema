import React from "react";
import {useState} from "react";
import Loading from '../../components/loading/Loading';
import Card from "react-bootstrap/Card";
import Button from "react-bootstrap/Button";
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Alert from 'react-bootstrap/Alert';
import md5 from 'md5';
import './Login.css';
import {performLogin} from '../../services/Api';
import { storeUserData } from "../../services/Storage";
import { useNavigate } from "react-router-dom";
import config from "../../config.json";

const BASE_URL = config.url + "/api/login";

function Login() {
    const [isLoading, setLoading] = useState(false);
    const [inputData, setInputData] = useState({});
    const [httpError, setHttpError] = useState(null);
    const navigate = useNavigate();

    const submit = (event) => {
        event.preventDefault();
        performRequest(inputData);
    };

    const performRequest = (data) => {
        setLoading(true);
        const payload = {
            email: data.email,
            password: md5(data.password)
        };

        performLogin(payload)
        .then(successResponse)
        .catch(errorResponse)
    };

    const successResponse = (response) => {
        setLoading(false);
        storeUserData(response.data);
        navigate("/home");
    };

    const errorResponse = (response) => {
        setLoading(false);

        if (response.response) {
            setHttpError(response.response.data);
            return;
        }

        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    const changeField = (e) => {
        const name = e.target.name;
        const value = e.target.value;
        setInputData(values => ({...values, [name]: value}));
    };

    return (
        <div className="Display">
            <div style={{flex: 1}}></div>
            <div style={{flex: 1}}>
            <img src={config.url + "/img/logo_complete.png"} />
            <Card style={{ width: '25rem', margin: '0 auto', border: 'none', backgroundColor: '#31B573' }}>
                <Card.Body>
                        {!isLoading && httpError && 
                        <Alert key={'danger'} variant={'danger'}>
                            {httpError.message}
                        </Alert>
                        }
                        
                        <Form onSubmit={submit}>

                            <FloatingLabel
                                controlId="emailInput"
                                label="Email"
                                className="mb-3">
                                <Form.Control type="email" placeholder="Email" name="email" value={inputData.email}
                                onChange={changeField} required={true} maxLength={100} />
                            </FloatingLabel>

                            <FloatingLabel
                                controlId="passwordInput"
                                label="Senha"
                                className="mb-3">
                                <Form.Control type="password" placeholder="Senha" name="password" value={inputData.password}
                                onChange={changeField} required={true} />
                            </FloatingLabel>

                            <div className="d-grid gap-2 " style={{marginBottom: '12px'}}>
                                <Button variant="success"
                                    className="custom-btn"
                                    type="submit"
                                    size="lg"
                                    disabled={isLoading}>
                                    {isLoading ? <Loading />
                                                : 
                                                'Entrar'}
                                </Button>
                            </div>
                            <a href="/recovery" className="no-link">
                                Esqueceu sua senha?
                            </a>

                            <br></br>
                            <a href="/register" className="no-link">
                                Novo por aqui? Faça seu cadastro!
                            </a>
                        </Form>
                </Card.Body>
            </Card>
            </div>
            <div style={{flex: 1}}></div>
        </div>
    );
}

export default Login;