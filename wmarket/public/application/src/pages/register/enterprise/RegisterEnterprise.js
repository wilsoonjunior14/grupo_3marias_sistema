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
import { performGetCEPInfo, performRequest } from "../../../services/Api";
import { formatDateToServer } from "../../../services/Format";
import Error from "../../../components/error/Error";

export default function RegisterEnterprise() {
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

        console.log(inputData);

        if (step === 6) {
            setHttpError(null);
            setIsLoading(true);

            performGetCEPInfo(inputData.zipcode)
            .then(onSucessGetCepResponse)
            .catch(errorResponse);
            return;
        }

        if (step === 13) {
            setIsLoading(true);
            doSubmit();
            return;
        }

        const nextStep = step + 1;
        setStep(nextStep);
        onSetMessageScreen(nextStep);
    };

    const onSucessGetCepResponse = (response) => {
        setIsLoading(false);

        const zipcodeInfo = response.data;
        if (zipcodeInfo.erro) {
            setHttpError({message: "CEP inválido."});
            return;
        }

        changeField({target: {name: "address", value: zipcodeInfo.logradouro}});
        changeField({target: {name: "neighborhood", value: zipcodeInfo.bairro}});
        changeField({target: {name: "complement", value: zipcodeInfo.complement}});
        const nextStep = step + 1;
        setStep(nextStep);
        onSetMessageScreen(nextStep);
    }

    const doSubmit = () => {
        inputData.status = "waiting";

        performRequest("POST", "/enterprises", inputData)
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
            setMessageScreen("Qual o nome do negócio?");
        }
        if (step === 2) {
            setMessageScreen("Como você descreve o seu negócio?");
        }
        if (step === 3) {
            setMessageScreen("Qual o email do negócio?");
        }    
        if (step === 4) {
            setMessageScreen("Qual o telefone de contato?");
        }
        if (step === 5) {
            setMessageScreen("Escolha uma categoria que caracterize o seu negócio...");
        }
        if (step === 6) {
            setMessageScreen("Informe o CEP...");
        }
        if (step === 7) {
            setMessageScreen("Qual a cidade do negócio?");
        }
        if (step === 8) {
            setMessageScreen("Qual o bairro?");
        }
        if (step === 9) {
            setMessageScreen("Qual o logradouro?");
        }
        if (step === 10) {
            setMessageScreen("Qual o número?");
        }
        if (step === 11) {
            setMessageScreen("Agora, crie uma senha de acesso.");
        }
        if (step === 12) {
            setMessageScreen("Confirme a senha que você criou.");
        }
        if (step === 13) {
            setMessageScreen("Adicione a logo do seu negócio.");
        }
    }

    return (
        <div className="display">
            <div style={{flex: 1}}></div>
            <div style={{flex: 1}}>
                <Container fluid>
                    <Row style={{textAlign: 'center', color: 'white'}}>
                        <Col>
                            <img src="http://localhost:5000/img/logo_complete.png" />
                            <h5 style={{fontSize: '30px', fontWeight: 'bold'}}>{messageScreen}</h5>
                            <Card style={{ width: '25rem', margin: '0 auto', border: 'none', backgroundColor: '#31B573' }}>
                                <Card.Body>
                                    {!isLoading && httpError &&
                                    <Error message={httpError.message} />
                                    }
                                        
                                    <Form onSubmit={onSubmit}>

                                        {step === 1 &&
                                        <CustomInput 
                                            name="name"
                                            placeholder="Nome do Negócio *"
                                            type="text"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.name} />}

                                        {step === 2 &&
                                        <CustomInput 
                                            name="description"
                                            placeholder="Descrição do Negócio *"
                                            type="textarea"
                                            required={true}
                                            maxlength={500} 
                                            onChange={changeField}
                                            value={inputData.description} />}

                                        {step === 3 &&
                                        <CustomInput 
                                            name="email"
                                            placeholder="Email *"
                                            type="email"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.email} />}

                                        {step === 4 &&
                                        <>
                                        <CustomInput 
                                            name="phone"
                                            placeholder="Telefone *"
                                            type="mask"
                                            mask={"(99)99999-9999"}
                                            required={true}
                                            maxlength={14} 
                                            onChange={changeField}
                                            value={inputData.phone} />
                                        <br></br>
                                        </>
                                        }

                                        {step === 5 &&
                                        <>
                                        <CustomInput 
                                            name="category_id"
                                            placeholder="Categoria *"
                                            type="select"
                                            required={true}
                                            onChange={changeField}
                                            value={inputData.category_id}
                                            endpoint="categories"
                                            endpoint_field="name" />
                                        <br></br>
                                        </>
                                        }

                                        {step === 6 &&
                                        <>
                                        <CustomInput 
                                            name="zipcode"
                                            placeholder="CEP *"
                                            type="mask"
                                            mask={"99999-999"}
                                            required={true}
                                            maxlength={9} 
                                            onChange={changeField}
                                            value={inputData.zipcode} />
                                        <br></br>
                                        </>
                                        }

                                        {step === 7 &&
                                        <>
                                        <CustomInput 
                                            name="city_id"
                                            placeholder="Cidade *"
                                            type="select"
                                            required={true}
                                            onChange={changeField}
                                            value={inputData.city_id}
                                            endpoint="cities"
                                            endpoint_field="name" />
                                        <br></br>
                                        </>
                                        }

                                        {step === 8 &&
                                        <CustomInput 
                                            name="neighborhood"
                                            placeholder="Bairro *"
                                            type="text"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.neighborhood} />}

                                        {step === 9 &&
                                        <CustomInput 
                                            name="address"
                                            placeholder="Logradouro *"
                                            type="text"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.address} />}

                                        {step === 10 &&
                                        <CustomInput 
                                            name="number"
                                            placeholder="Número *"
                                            type="number"
                                            required={true}
                                            maxlength={4} 
                                            onChange={changeField}
                                            value={inputData.number} />}

                                        {step === 11 &&
                                        <CustomInput 
                                            name="password"
                                            placeholder="Senha *"
                                            type="password"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.password} />}

                                        {step === 12 &&
                                        <CustomInput 
                                            name="confPassword"
                                            placeholder="Confirmar Senha *"
                                            type="password"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.confPassword} />}

                                        {step === 13 &&
                                        <CustomInput 
                                            name="image"
                                            placeholder="Logo *"
                                            type="file"
                                            required={true}
                                            maxlength={255} 
                                            onChange={changeField}
                                            value={inputData.image} />}

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
                                                    
                                                    {step === 13 && !isLoading &&
                                                    <>
                                                    Concluir
                                                    <i style={{float: 'right', paddingTop: 3}} className="material-icons">done</i>
                                                    </>
                                                    }
                                                    {isLoading &&
                                                        <Loading />
                                                    }
                                                    {!isLoading && step !== 13 &&
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