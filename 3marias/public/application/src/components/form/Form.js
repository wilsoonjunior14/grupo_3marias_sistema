import React, {useState, useEffect, useReducer} from "react";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Error from '../../components/error/Error';
import Success from '../../components/success/Success';
import '../../App.css';
import Button from "react-bootstrap/esm/Button";
import Loading from "../../components/loading/Loading";
import CustomInput from "../../components/input/CustomInput";
import { performCustomRequest, performRequest } from "../../services/Api";
import { useParams } from "react-router-dom";
import { formatDateToServer, formatDoubleValue } from "../../services/Format";
import BackButton from "../button/BackButton";
import MD5 from "crypto-js/md5";
import { formatDataFrontend } from "../../services/Utils";

const CustomForm = ({endpoint, nameScreen, fields, validation}) => {
    const [loading, setLoading] = useState(false);
    const [isLoadingData, setIsLoadingData] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [item, setItem] = useState({});
    const parameters = useParams();
    const [randomId] = useState(parseInt(Math.random() * 1000));
    const initialState = {};

    useEffect(() => {
        if (parameters.id && !isLoadingData) {
            if (endpoint === "/users") {
                endpoint = "/v1" + endpoint;
            }
            setIsLoadingData(true);
            performRequest("GET", endpoint + "/"+parameters.id)
            .then(successGet)
            .catch(errorResponse);
        }
    }, []);

    const successGet = (response) => {
        setItem(response.data);
        const data = response.data;
        if (data["image"]) {
            data.image = "";
        }

        formatDataFrontend(data);

        setIsLoadingData(false);
        dispatch({ type: "data", data });
    }; 

    const reducer = (state, action) => {
        if (action.type === "reset") {
            return initialState;
        }

        if (action.type === "data") {
            return action.data;
        }
    
        const result = { ...state };
        result[action.type] = action.value;
        return result;
    };

    const [state, dispatch] = useReducer(reducer, initialState);

    const changeField = (e) => {
        const { name, value } = e.target;
        dispatch({ type: name, value });
    };

    const onSubmit = (e) => {
        e.preventDefault();

        // Form Validation
        const form = document.getElementById("form" + randomId);
        if (!form.checkValidity()) {
            form.classList.add("was-validated");
            return;
        } else {
            form.classList.remove("was-validated");
        }

        // Custom Validation
        if (validation) {
            const fails = validation(state);
            if (fails) {
                setHttpError(fails);
                return;
            }
        }

        setLoading(true);
        setHttpError(null);
        setHttpSuccess(null);

        if (parameters.id) {
            performPut(state, e);
            return;
        }

        performPost(state, e);
    };

    const performPut = (data, e) => {
        const hasImageField = Object.keys(data).some((key) => key === "image");
        if (hasImageField) {
            var formData = new FormData();
            const userdata = processDataBefore(data);
            Object.keys(userdata).forEach((key) => {
                formData.append(key, data[key]);
            });
            if (document.getElementById("imageInput")) {
                formData.delete("image");
                formData.append("image", document.getElementById("imageInput").files[0]);
            }

            performCustomRequest("POST", endpoint, formData)
            .then(successPut)
            .catch(errorResponse);
            return;
        }

        const userdata = Object.assign(item, state);
        const payload = processDataBefore(userdata);
        performRequest("PUT", endpoint + "/"+parameters.id, payload)
        .then(successPut)
        .catch(errorResponse);
        return;
    }

    const performPost = (data, e) => {
        const hasImageField = Object.keys(data).some((key) => key === "image");
        if (hasImageField) {
            var formData = new FormData();
            Object.keys(data).forEach((key) => {
                formData.append(key, data[key]);
            });
            formData.delete("image");
            formData.append("image", document.getElementById("imageInput").files[0]);

            performCustomRequest("POST", endpoint, formData)
            .then((response) => successPost(response, e))
            .catch(errorResponse);
            return;
        }

        const userdata = Object.assign({}, state);
        const payload = processDataBefore(userdata);
        performRequest("POST", endpoint, payload)
        .then((response) => successPost(response, e))
        .catch(errorResponse);
    }

    const processDataBefore = (data) => {
        const keys = Object.keys(data);
        keys.forEach((key) => {
            if (key === "birthdate" || key.indexOf("date") !== -1) {
                data[key] = formatDateToServer(data[key]);
            }
            if (key.indexOf("password") !== -1) {
                data[key] = MD5(data[key]).toString();
            }
            if (key.indexOf("value_performed") !== -1) {
                data[key] = formatDoubleValue(data[key]);
            }
            if (!data[key] || data[key] === null || data[key] === undefined) {
                delete data[key];
            }
        });

        if (parameters.enterpriseId) {
            data["enterprise_id"] = parameters.enterpriseId;
        }

        return data;
    }

    const successPut = (response) => {
        setLoading(false);
        setHttpSuccess({message: nameScreen + " salvo com sucesso!"});
    };

    const successPost = (response, e) => {
        e.target.reset();
        setLoading(false);
        dispatch({type: "reset"});
        setHttpSuccess({message: nameScreen + " criado com sucesso!"});
    };

    const errorResponse = (response) => {
        setLoading(false);
        setIsLoadingData(false);
        if (response.response) {
            if (response.response.status === 404) {
                setHttpError("Não foi possível conectar-se com o servidor.");
                return;
            }
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    return (
        <>
            <Container fluid>
                {!loading && httpError &&
                    <Error message={httpError.message} />
                }

                {!loading && httpSuccess &&
                    <Success message={httpSuccess.message} />
                }

                <Row>
                    <Col>
                        <BackButton />
                    </Col>
                </Row>

                <Row>
                    <Col>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                <i className="material-icons float-left">add</i>
                                
                                {parameters.id &&
                                <p>Editar {nameScreen}</p>
                                }
                                {!parameters.id &&
                                <p>Adicionar {nameScreen}</p>
                                }
                                
                                </Card.Title>

                                {isLoadingData && 
                                    <>
                                    <Col></Col>
                                    <Col style={{textAlign: 'center'}}>
                                        <Loading />
                                    </Col>
                                    <Col></Col>
                                    </>
                                }

                                {!isLoadingData &&
                                <Form id={"form" + randomId} onSubmit={onSubmit} noValidate={true}>
                                    <Row className="required-label">
                                        <Col>
                                            <small className="required-label-content">Campos com * são obrigatórios.</small>
                                        </Col>
                                        <br></br>
                                        <br></br>
                                    </Row>
                                    <Row>
                                        {fields.map((field) => 
                                        <Col key={field.name} md={6} lg={4}>
                                            <CustomInput 
                                                name={field.name} 
                                                placeholder={field.placeholder}
                                                onChange={changeField}
                                                value={state[field.name]} 
                                                maxlength={field.maxlength} 
                                                required={field.required} 
                                                type={field.type}
                                                endpoint={field.endpoint}
                                                endpoint_field={field.endpoint_field}
                                                data={field.data}
                                                mask={field.mask}
                                                dateId={field.dateId}
                                                disabled={field.disabled} />
                                        </Col>
                                        )}
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
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
}

export default CustomForm;