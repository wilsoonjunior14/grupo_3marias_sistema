import React, { useEffect, useState } from "react";
import Card from "react-bootstrap/Card";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Alert from 'react-bootstrap/Alert';
import Button from "react-bootstrap/Button";
import Loading from '../../../components/loading/Loading';
import CustomInput from '../../../components/input/CustomInput';
import { useNavigate } from "react-router-dom";
import "../Style.css";
import { performRequest } from "../../../services/Api";
import { formatDateToServer } from "../../../services/Format";
import { BIRTHDATE_PATTERN, EMAIL_PATTERN, NAME_PATTERN, PHONE_PATTERH } from "../../../services/Pattern";
import config from "../../../config.json";

export default function RegisterUser() {
    const navigate = useNavigate();

    const [isLoading, setIsLoading] = useState(false);
    const [inputData, setInputData] = useState({});
    const [httpError, setHttpError] = useState(null);
    const [step, setStep] = useState(1);
    const [messageScreen, setMessageScreen] = useState("");

    useEffect(() => {
        onSetMessageScreen(step);
    }, []);

    const changeField = (e) => {
        const name = e.target.name;
        const value = e.target.value;
        setInputData(values => ({...values, [name]: value}));
    };

    const onSubmit = (e) => {
        e.preventDefault();

        if (step === 6) {
            console.log("chegou ao final...");
            setIsLoading(true);
            doSubmit();
            return;
        }

        const nextStep = step + 1;
        setStep(nextStep);
        onSetMessageScreen(nextStep);
    };

    const doSubmit = () => {
        inputData.group_id = "1";
        inputData.birthdate = formatDateToServer(inputData.birthdate);
        performRequest("POST", "/users", inputData)
        .then(successResponse)
        .catch(errorResponse);
    };

    const successResponse = (response) => {
        setIsLoading(false);
        navigate("/register/success");
    };

    const errorResponse = (response) => {
        setIsLoading(false);
        if (response.response) {
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    const onBack = () => {
        setStep(step - 1);
        onSetMessageScreen(step - 1);
    }

    const onSetMessageScreen = (step) => {
        if (step === 1) {
            setMessageScreen("Qual seu nome?");
        }
        if (step === 2) {
            setMessageScreen("Qual seu email?");
        }
        if (step === 3) {
            setMessageScreen("Em que ano você nasceu?");
        }        
        if (step === 4) {
            setMessageScreen("Qual número do seu telefone?");
        }
        if (step === 5) {
            setMessageScreen("Informe uma senha de acesso.");
        }
        if (step === 6) {
            setMessageScreen("Confirme a senha informada anteriormente.");
        }
    }

    return (
        <div className="display">
            <div style={{flex: 1}}></div>
            <div style={{flex: 1}}>
                <Container fluid>
                    <Row style={{textAlign: 'center', color: 'white'}}>
                        <Col>
                            <img src={config.url + "/img/logo_complete.png"} />
                            <h5 style={{fontSize: '30px', fontWeight: 'bold'}}>{messageScreen}</h5>
                            <Card style={{ width: '25rem', margin: '0 auto', border: 'none', backgroundColor: '#31B573' }}>
                                <Card.Body>
                                    {!isLoading && httpError && 
                                    <Alert key={'danger'} variant={'danger'}>
                                        {httpError.message}
                                    </Alert>
                                    }
                                        
                                    <Form onSubmit={onSubmit}>

                                        {step === 1 &&
                                        <CustomInput 
                                            name="name"
                                            placeholder="Nome Completo *"
                                            type="text"
                                            required={true}
                                            maxlength={255}
                                            pattern={NAME_PATTERN}
                                            onChange={changeField}
                                            value={inputData.name} />}

                                        {step === 2 &&
                                        <CustomInput 
                                            name="email"
                                            placeholder="Email *"
                                            type="email"
                                            required={true}
                                            maxlength={255} 
                                            pattern={EMAIL_PATTERN}
                                            onChange={changeField}
                                            value={inputData.email} />}

                                        {step === 3 &&
                                        <>
                                        <CustomInput 
                                            style={{marginBottom: 20}}
                                            name="birthdate"
                                            placeholder="Data de Nascimento *"
                                            type="mask"
                                            mask={"99/99/9999"}
                                            required={true}
                                            maxlength={10} 
                                            pattern={BIRTHDATE_PATTERN}
                                            onChange={changeField}
                                            value={inputData.birthdate} />
                                        <br></br>
                                        </>
                                        }

                                        {step === 4 &&
                                        <>
                                        <CustomInput 
                                            name="phoneNumber"
                                            placeholder="Telefone *"
                                            type="mask"
                                            mask={"(99)99999-9999"}
                                            required={true}
                                            maxlength={14}
                                            onChange={changeField}
                                            value={inputData.phoneNumber} />
                                        <br></br>
                                        </>
                                        }

                                        {step === 5 &&
                                        <CustomInput 
                                            name="password"
                                            placeholder="Senha *"
                                            type="password"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.password} />}

                                        {step === 6 &&
                                        <CustomInput 
                                            name="confPassword"
                                            placeholder="Confirmar Senha *"
                                            type="password"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.confPassword} />}

                                        <Row>
                                            <Col xs={5}>
                                            {step > 1 &&
                                            <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                                <Button className="custom-btn" variant="success"
                                                    type="button"
                                                    onClick={onBack}
                                                    disabled={isLoading}
                                                    size="lg">
                                                    <i style={{float: 'left', paddingTop: 3}} className="material-icons">arrow_back</i>
                                                    Voltar
                                                </Button>
                                            </div>
                                            }
                                            </Col>
                                            <Col xs={2}></Col>
                                            <Col xs={5}>
                                            <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                                <Button className="custom-btn" variant="success"
                                                    type="submit"
                                                    disabled={isLoading}
                                                    size="lg">
                                                    
                                                    {step === 6 && !isLoading &&
                                                    <>
                                                    Concluir
                                                    <i style={{float: 'right', paddingTop: 3}} className="material-icons">done</i>
                                                    </>
                                                    }
                                                    {step === 6 && isLoading &&
                                                        <Loading />
                                                    }
                                                    {step !== 6 &&
                                                    <>
                                                    Próximo
                                                    <i style={{float: 'right', paddingTop: 3}} className="material-icons">arrow_forward</i>
                                                    </>
                                                    }
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