import React from "react";
import {useState} from "react";
import Loading from '../../components/loading/Loading';
import Card from "react-bootstrap/Card";
import Button from "react-bootstrap/Button";
import Form from 'react-bootstrap/Form';
import FloatingLabel from 'react-bootstrap/FloatingLabel';
import Alert from 'react-bootstrap/Alert';
import './Recovery.css';
import {performRecoveryPassword} from '../../services/Api';
import config from "../../config.json";

export default function RecoveryPassword() {
    const [isLoading, setLoading] = useState(false);
    const [inputData, setInputData] = useState({});
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);

    const submit = (event) => {
        event.preventDefault();
        setLoading(true);
        setHttpSuccess(null);
        setHttpError(null);

        performRecoveryPassword(inputData)
        .then((response) => successRecoveryPassword(response, event))
        .catch(errorRecoveryPassword);        
    };

    const successRecoveryPassword = function(response, event) {
        event.target.reset();
        setLoading(false);
        setHttpSuccess({message: "Recebemos sua solicitação de recuperação de senha. Em breve, você receberá um e-mail com as instruções necessárias para redefinir sua senha."})
    }

    const errorRecoveryPassword = function(response) {
        setLoading(false);
        if (response.response) {
            setHttpError(response.response.data);
            return;
        }

        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    }

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

                        {!isLoading && httpSuccess && 
                        <Alert key={'success'} variant={'success'}>
                            <i className="material-icons float-left">done</i>
                            {httpSuccess.message}
                        </Alert>
                        }
                        
                        <Form onSubmit={submit}>

                            <h5 style={{color: "white"}}>Informe o email cadastrado para recuperar a senha.</h5>

                            <FloatingLabel
                                controlId="emailInput"
                                label="Email"
                                className="mb-3">
                                <Form.Control type="email" placeholder="Email" name="email" value={inputData.email}
                                onChange={changeField} required={true} maxLength={100} />
                            </FloatingLabel>

                            <div className="d-grid gap-2" style={{marginBottom: '12px'}}>
                                <Button className="custom-btn" variant="success"
                                    type="submit"
                                    size="lg"
                                    disabled={isLoading}>
                                    {isLoading ? <Loading />
                                                : 
                                                'Recuperar'}
                                </Button>
                            </div>
                        </Form>
                </Card.Body>
            </Card>
            </div>
            <div style={{flex: 1}}></div>
        </div>
    );
};