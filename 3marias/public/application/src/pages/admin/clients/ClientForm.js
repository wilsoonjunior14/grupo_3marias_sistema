import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import { useParams } from "react-router-dom";
import { useEffect, useReducer, useState } from "react";
import Error from "../../../components/error/Error";
import Success from "../../../components/success/Success";
import Loading from "../../../components/loading/Loading";
import CustomInput from "../../../components/input/CustomInput";
import Button from "react-bootstrap/Button";
import { performCustomRequest, performRequest } from "../../../services/Api";
import { formatDateToServer } from "../../../services/Format";

const ClientForm = ({}) => {

    const [loading, setLoading] = useState(false);
    const [isLoadingData, setIsLoadingData] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const parameters = useParams();
    const [item, setItem] = useState({});
    const initialState = {};
    const [endpoint, setEndpoint] = useState("/v1/clients");

    useEffect(() => {
        if (parameters.id && !isLoadingData) {
            setIsLoadingData(true);
            performRequest("GET", endpoint + "/"+parameters.id)
            .then(successGet)
            .catch(errorResponse);
        }
    }, []);

    const successGet = (response) => {
        setItem(response.data);
        const data = response.data;
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

        const hasField = fields.some((item) => {return (item.name === "conjugue")});
        if (name.toString() === "state" && value === "Casado" && !hasField) {
            fields.concat(dependentFields);

            dependentFields.forEach((f) => fields.push(f));
            setFields(fields);
        } else if(name.toString() === "state" && value !== "Casado") {
            var remainingFields = fields;
            dependentFields.forEach((f) => {
                remainingFields = remainingFields.filter((field) => field.name !== f.name);
            });
            setFields(remainingFields);
        }
    };

    const onSubmit = (e) => {
        e.preventDefault();
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
            Object.keys(data).forEach((key) => {
                formData.append(key, data[key]);
            });
            formData.delete("image");
            formData.append("image", document.getElementById("imageInput").files[0]);

            const payload = Object.assign(item, state);
            performCustomRequest("POST", endpoint, formData)
            .then(successPut)
            .catch(errorResponse);
            return;
        }

        const payload = Object.assign(item, state);
        performRequest("PUT", endpoint + "/"+parameters.id, payload)
        .then(successPut)
        .catch(errorResponse);
        return;
    }

    const performPost = (data, e) => {
        const payload = processDataBefore(state);
        performRequest("POST", endpoint, payload)
        .then((response) => successPost(response, e))
        .catch(errorResponse);
    }

    const processDataBefore = (data) => {
        const keys = Object.keys(data);
        keys.forEach((key) => {
            if (key === "birthdate") {
                data[key] = formatDateToServer(data[key]);
            }
        });
        return data;
    }

    const successPut = (response) => {
        setLoading(false);
        setHttpSuccess({message: "Item salvo com sucesso!"});
    };

    const successPost = (response, e) => {
        e.target.reset();
        setLoading(false);
        dispatch({type: "reset"});
        setHttpSuccess({message: "Cliente salvo com sucesso!"});
    };

    const errorResponse = (response) => {
        setLoading(false);
        setIsLoadingData(false);
        if (response.response) {
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    const [fields, setFields] = useState([
        {
            name: 'name',
            placeholder: 'Nome Completo *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'rg',
            placeholder: 'RG *',
            type: 'mask',
            mask: '9999999999999',
            maxlength: 100,
            required: true
        },
        {
            name: 'cpf',
            placeholder: 'CPF *',
            type: 'mask',
            maxlength: 14,
            required: true,
            mask: "999.999.999-99"
        },
        {
            name: 'ocupation',
            placeholder: 'Profissão *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'email',
            placeholder: 'Email *',
            type: 'email',
            maxlength: 100,
            required: true
        },
        {
            name: 'phone',
            placeholder: 'Telefone *',
            type: 'mask',
            maxlength: 14,
            required: true,
            mask: "(99)99999-9999"
        },
        {
            name: 'nationality',
            placeholder: 'Nacionalidade *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: "state",
            placeholder: "Estado Civil *",
            type: "select",
            required: true,
            data: ["Solteiro", "Casado", "Divorciado", "Viúvo"]
        },
        {
            name: 'birthdate',
            placeholder: 'Data de Nascimento',
            type: 'mask',
            maxlength: 10,
            required: false,
            mask: "99/99/9999"
        },
        {
            name: 'city_id',
            placeholder: 'Cidade *',
            type: 'select',
            required: true,
            endpoint: "cities",
            endpoint_field: "name"
        },
        {
            name: 'zipcode',
            placeholder: 'CEP *',
            type: 'mask',
            required: true,
            mask: '99999-999'
        },        
        {
            name: 'address',
            placeholder: 'Endereço *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'neighborhood',
            placeholder: 'Bairro *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'number',
            placeholder: 'Número *',
            type: 'number',
            maxlength: 4,
            required: true
        },
        {
            name: 'complement',
            placeholder: 'Complemento',
            type: 'text',
            maxlength: 255,
            required: false
        },
    ]);

    const dependentFields = [
        {
            name: 'name_dependent',
            placeholder: 'Nome Completo do Cônjugue *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'rg_dependent',
            placeholder: 'RG do Cônjugue *',
            type: 'mask',
            mask: '9999999999999',
            maxlength: 13,
            required: true
        },
        {
            name: 'cpf_dependent',
            placeholder: 'CPF do Cônjugue *',
            type: 'mask',
            maxlength: 14,
            required: true,
            mask: "999.999.999-99"
        },
        {
            name: 'ocupation_dependent',
            placeholder: 'Profissão do Cônjugue *',
            type: 'text',
            maxlength: 255,
            required: true,
            mask: "999.999.999-99"
        },
        {
            name: 'email_dependent',
            placeholder: 'Email do Cônjugue',
            type: 'email',
            maxlength: 100,
            required: false
        },
        {
            name: 'phone_dependent',
            placeholder: 'Telefone do Cônjugue',
            type: 'mask',
            maxlength: 14,
            required: false,
            mask: "(99)99999-9999"
        },
        {
            name: 'birthdate_dependent',
            placeholder: 'Data de Nascimento do Cônjugue',
            type: 'mask',
            maxlength: 10,
            required: false,
            mask: "99/99/9999"
        }
    ];

    return (
        <>
        <VHeader />
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col>
                    {!loading && httpError &&
                        <Error message={httpError.message} />
                    }

                    {!loading && httpSuccess &&
                        <Success message={httpSuccess.message} />
                    }
                    <Card>
                        <Card.Body>
                            <Card.Title>
                                {parameters.id &&
                                <>
                                <i className="material-icons float-left">edit</i>
                                <p>Editar Cliente</p>
                                </>
                                }
                                {!parameters.id &&
                                <>
                                <i className="material-icons float-left">add</i>
                                <p>Adicionar Cliente</p>
                                </>
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
                                <Form onSubmit={onSubmit}>
                                    <Row>
                                        <Col>
                                            <small>Campos com * são obrigatórios.</small>
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
                                                mask={field.mask} />
                                        </Col>
                                        )}
                                    </Row>
                                    <br></br>
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
    )
};

export default ClientForm;
