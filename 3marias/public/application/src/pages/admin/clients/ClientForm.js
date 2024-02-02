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
import { performGetCEPInfo, performRequest } from "../../../services/Api";
import { formatDateToServer, formatDoubleValue } from "../../../services/Format";
import { clearForm, validateForm } from '../../../services/Utils';

const ClientForm = ({disableHeader}) => {

    const [loading, setLoading] = useState(false);
    const [isLoadingData, setIsLoadingData] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [resetScreen, setResetScreen] = useState(false);
    const parameters = useParams();
    const [item, setItem] = useState({});
    const initialState = {};
    const [endpoint, setEndpoint] = useState("/v1/clients");
    const [containerStyle, setContainerStyle] = useState({});

    useEffect(() => {
        if (parameters.id && !isLoadingData) {
            setIsLoadingData(true);
            performRequest("GET", endpoint + "/"+parameters.id)
            .then(successGet)
            .catch(errorResponse);
        }

        if (disableHeader) {
            setContainerStyle({});
        } else {
            setContainerStyle({marginLeft: 90, width: "calc(100% - 100px)"});
        }
    }, []);

    const successGet = (response) => {
        setItem(response.data);
        const data = response.data;
        setIsLoadingData(false);
        dispatch({ type: "data", data });
    }; 

    const reducer = (state, action) => {
        const value = action.value;
        if (action.type === "reset") {
            return initialState;
        }

        if (action.type === "data") {
            return action.data;
        }
    
        const result = { ...state };
        result[action.type] = value;
        return result;
    };

    const [state, dispatch] = useReducer(reducer, initialState);

    const changeField = (e) => {
        const { name, value } = e.target;
        dispatch({ type: name, value });

        if (name === "zipcode") {
            var zipCodeRegex = new RegExp(/\d{5}-\d{3}/g);
            if (zipCodeRegex.test(value)) {
                onChangeZipCode(value);
            }
        }

        const hasField = fields.some((item) => {return (item.name === "conjugue")});
        if (name.toString() === "state" && value === "Casado" && !hasField) {
            fields.concat(dependentFields);

            dependentFields.forEach((f) => fields.push(f));
            setFields(fields);
        } else if(name.toString() === "state" && value !== "Casado") {
            removeDependentFields();
        }
    };

    const removeDependentFields = () => {
        var remainingFields = fields;
        dependentFields.forEach((f) => {
            remainingFields = remainingFields.filter((field) => field.name !== f.name);
        });
        setFields(remainingFields);
    }

    const onChangeZipCode = (zipcode) => {
        performGetCEPInfo(zipcode)
        .then(onSuccessGetZipCodeResponse)
        .catch((err) => {});
    }

    const onSuccessGetZipCodeResponse = (res) => {
        const response = res.data;
        const address = response.logradouro;
        const neighborhood = response.bairro;
        const complement = response.complemento;
        changeField({target: {name: "address", value: address}});
        changeField({target: {name: "neighborhood", value: neighborhood}});
        changeField({target: {name: "complement", value: complement}});
    };

    const onSubmit = (e) => {
        e.preventDefault();

        const validation = validateForm("clientForm");
        if (!validation) {
            return;
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
        var payload = Object.assign(item, state);
        Object.keys(payload).forEach((key) => {
            if (payload[key] === null || payload[key] === "") {
                delete payload[key];
            }
        });
        performRequest("PUT", endpoint + "/"+parameters.id, payload)
        .then(successPut)
        .catch(errorResponse);
        return;
    }

    const performPost = (data, e) => {
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
            if (key.indexOf("salary") !== -1) {
                data[key] = formatDoubleValue(data[key]);
            }
        });
        return data;
    }

    const successPut = (response) => {
        setLoading(false);
        setHttpSuccess({message: "Cliente salvo com sucesso!"});
    };

    const successPost = (response, e) => {
        e.target.reset();
        setLoading(false);
        dispatch({type: "reset"});
        removeDependentFields();
        setHttpSuccess({message: "Cliente salvo com sucesso!"});
        setResetScreen(true);
        setTimeout(() => {
            setResetScreen(false);
        }, 100);
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

    const initialFields = [
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
            name: 'rg_organ',
            placeholder: 'Órgão Expedidor do RG *',
            type: 'text',
            maxlength: 10,
            required: true
        },
        {
            name: 'rg_date',
            placeholder: 'Data de Emissão do RG *',
            type: 'mask',
            maxlength: 10,
            mask: "99/99/9999",
            required: true
        },
        {
            name: "sex",
            placeholder: "Sexo *",
            type: "select",
            required: true,
            data: ["Masculino", "Feminino"]
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
            name: 'naturality',
            placeholder: 'Naturalidade *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'salary',
            placeholder: 'Renda Bruta (R$)',
            type: 'money',
            maxlength: 255,
            required: false
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
            name: 'zipcode',
            placeholder: 'CEP *',
            type: 'mask',
            required: true,
            mask: '99999-999'
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
            placeholder: 'Número',
            type: 'number',
            maxlength: 4,
            required: false
        },
        {
            name: 'complement',
            placeholder: 'Complemento',
            type: 'text',
            maxlength: 255,
            required: false
        },
        {
            name: 'person_service',
            placeholder: 'Atendimento',
            type: 'select',
            required: false,
            data: ["Whatsapp", "Instagram", "Presencial"]
        },   
        {
            name: 'indication',
            placeholder: 'Indicação',
            type: 'text',
            maxlength: 255,
            required: false
        },
        {
            name: "is_public_employee",
            placeholder: "É Funcionário Público?",
            type: "select",
            required: false,
            data: ["Sim", "Não"]
        },
        {
            name: "has_fgts",
            placeholder: "Possui 3 anos de trabalho sob regime do FGTS?",
            type: "select",
            required: false,
            data: ["Sim", "Não"]
        },
        {
            name: "has_many_buyers",
            placeholder: "Possui mais de um comprador ou dependente?",
            type: "select",
            required: false,
            data: ["Sim", "Não"]
        },
    ];

    const [fields, setFields] = useState(initialFields);

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
            name: 'rg_dependent_organ',
            placeholder: 'Órgão Expedidor do RG do Cônjugue *',
            type: 'text',
            maxlength: 10,
            required: true
        },
        {
            name: 'rg_dependent_date',
            placeholder: 'Data de Emissão do RG do Cônjugue *',
            type: 'mask',
            maxlength: 10,
            required: true,
            mask: "99/99/9999"
        },
        {
            name: "sex_dependent",
            placeholder: "Sexo",
            type: "select",
            required: true,
            data: ["Masculino", "Feminino"]
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
            name: 'nationality_dependent',
            placeholder: 'Nacionalidade do Cônjugue *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'naturality_dependent',
            placeholder: 'Naturalidade do Cônjugue *',
            type: 'text',
            maxlength: 255,
            required: true
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
            name: 'salary_dependent',
            placeholder: 'Renda Bruta do Cônjugue (R$)',
            type: 'money',
            maxlength: 255,
            required: false
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
        },
        {
            name: "is_public_employee_dependent",
            placeholder: "Cônjugue é Funcionário Público?",
            type: "select",
            required: false,
            data: ["Sim", "Não"]
        },
        {
            name: "has_fgts_dependent",
            placeholder: "O Cônjugue possui 3 anos de trabalho sob regime do FGTS?",
            type: "select",
            required: false,
            data: ["Sim", "Não"]
        },
        {
            name: "has_many_buyers_dependent",
            placeholder: "O Cônjugue possui mais de um dependente?",
            type: "select",
            required: false,
            data: ["Sim", "Não"]
        },
    ];

    return (
        <>
        {!disableHeader &&
        <VHeader />
        }
        {!resetScreen &&
        <Container id='app-container' style={containerStyle} fluid>
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
                                <Form id='clientForm' onSubmit={onSubmit} noValidate={true}>
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
        }
        </>
    )
};

export default ClientForm;
