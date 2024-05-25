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
import logo from "../../assets/logo_complete.png";

function Login() {
    const [isLoading, setLoading] = useState(false);
    const [inputData, setInputData] = useState({});
    const [httpError, setHttpError] = useState(null);
    const [loginValidated, setLoginValidated] = useState(false);
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

    const onSetErrorMessage = () => {
        if (loginValidated) {
            return;
        }
        setLoginValidated(true);
        const message = (new URLSearchParams(window.location.search)).get("message");
        if (message) {
            setHttpError({message: atob(message)});
        }
    }
    setTimeout(() => {
        onSetErrorMessage();
    }, 2000);

    return (
        <div className="Display">
            <div style={{flex: 1}}></div>
            <div style={{flex: 1}}>
            <img 
                width={400} style={{marginBottom: -60}} 
                src={logo}
                alt={"logo"} />
            <Card style={{ width: '25rem', margin: '0 auto', border: 'none', backgroundColor: '#0C3472' }}>
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
                        </Form>
                </Card.Body>
            </Card>
            </div>
            <div style={{flex: 1}}></div>
        </div>
    );
}

export default Login;