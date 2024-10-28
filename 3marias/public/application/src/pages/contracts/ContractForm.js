import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import { useParams } from "react-router-dom";
import { useEffect, useReducer, useState } from "react";
import Error from "../../components/error/Error";
import Success from "../../components/success/Success";
import Loading from "../../components/loading/Loading";
import CustomInput from "../../components/input/CustomInput";
import Button from "react-bootstrap/Button";
import { performRequest } from "../../services/Api";
import { getMoney, validateForm } from '../../services/Utils';
import { validateAddress, validateCPF, validateRequired, validateRequiredString, validateRequiredStringWithoutPattern } from '../../services/Validation';
import { hasPermission } from '../../services/Storage';
import Forbidden from '../../components/error/Forbidden';

const ContractForm = ({}) => {

    const isAdmin = hasPermission("PROPRIETÁRIO");
    const isDeveloper = hasPermission("DESENVOLVEDOR");

    const [loading, setLoading] = useState(false);
    const [isLoadingData, setIsLoadingData] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const parameters = useParams();
    const [item, setItem] = useState(null);
    const initialState = {};
    const [endpoint, setEndpoint] = useState("/v1/contracts");
    const [resetScreen, setResetScreen] = useState(false);
    
    const [proposals, setProposals] = useState([]);
    const [proposalsLabels, setProposalsLabels] = useState([]);

    useEffect(() => {
        getProposals();

        if (parameters.id && !isLoadingData) {
            setIsLoadingData(true);
            performRequest("GET", endpoint + "/"+parameters.id)
            .then(successGet)
            .catch(errorResponse);
        }
    }, []);

    useEffect(() => {
        if (item) {
            changeField({target: {name: "proposal_code", value: item.code}});
            changeField({target: {name: "value2", value: getMoney(item.value)}});
            changeField({target: {name: "value", value: item.value}});
            changeField({target: {name: "city_id", value: item.address.city_id}});
            changeField({target: {name: "neighborhood", value: item.address.neighborhood}});
            changeField({target: {name: "address", value: item.address.address}});
            changeField({target: {name: "zipcode", value: item.address.zipcode}});
            if (item.address.number > 0) {
                changeField({target: {name: "number", value: item.address.number}});
            }
            changeField({target: {name: "complement", value: item.address.complement}});
        }
    }, [item]);

    const getProposals = () => {
        setIsLoadingData(true);

        performRequest("GET", "/v1/proposals")
        .then(onSuccessGetProposals)
        .catch(errorResponse);
    }

    const onSuccessGetProposals = (res) => {
        setIsLoadingData(false);
        const proposalsApproved = res.data.filter((p) => p.status === 2 && !p.has_contract);
        setProposals(proposalsApproved);

        const approvedProposals = proposalsApproved.map((p) => p.code);
        setProposalsLabels(approvedProposals);
    }

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
        if (parameters.id && e.target.name === "proposal_code") {
            return;
        }

        if (e.target.name === "proposal_code") {
            if (e.target.value && e.target.value !== "") {
                const proposal = proposals.filter((p) => p.code === e.target.value)[0];
                changeField({target: {name: "description", value: proposal.description}});
                changeField({target: {name: "proposal_id", value: proposal.id}});
                changeField({target: {name: "value2", value: getMoney(proposal.global_value)}});
                changeField({target: {name: "value", value: proposal.global_value}});
            }
        }
    };

    const validatePayload = (payload) => {
        const validateProposal = validateRequired(payload, "proposal_id", "Código da Proposta");
        if (validateProposal) {
            return validateProposal;
        }
        const validateBuildingType = validateRequiredStringWithoutPattern(payload, "building_type", 255, "Tipo de Obra");
        if (validateBuildingType) {
            return validateBuildingType;
        }
        const validateDescription = validateRequiredStringWithoutPattern(payload, "description", 255, "Descrição");
        if (validateDescription) {
            return validateDescription;
        }
        const validateMeters = validateRequiredStringWithoutPattern(payload, "meters", 255, "Metros Quadrados da Obra");
        if (validateMeters) {
            return validateMeters;
        }
        const validationAddress = validateAddress(payload);
        if (validationAddress) {
            return validationAddress;
        }
        const validateWitnessOne = validateRequiredString(payload, "witness_one_name", 255, "Nome da Testemunha 1");
        if (validateWitnessOne) {
            return validateWitnessOne;
        }
        const validateWitnessOneCPF = validateCPF(payload, "witness_one_cpf", "CPF da Testemunha 1");
        if (validateWitnessOneCPF) {
            return validateWitnessOneCPF;
        }
        const validateWitnessTwo = validateRequiredString(payload, "witness_two_name", 255, "Nome da Testemunha 2");
        if (validateWitnessTwo) {
            return validateWitnessTwo;
        }
        const validateWitnessTwoCPF = validateCPF(payload, "witness_two_cpf", "CPF da Testemunha 2");
        if (validateWitnessTwoCPF) {
            return validateWitnessTwoCPF;
        }
    };

    const onSubmit = (e) => {
        e.preventDefault();
        const validation = validateForm("contractForm");
        if (!validation) {
            return;
        }
        const validationFields = validatePayload(state);
        if (validationFields) {
            setHttpError(validationFields);
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
        const payload = userdata;
        performRequest("POST", endpoint, payload)
        .then((response) => successPost(response, e))
        .catch(errorResponse);
    }

    const successPut = (response) => {
        setLoading(false);
        setHttpSuccess({message: "Contrato salvo com sucesso!"});
    };

    const successPost = (response, e) => {
        e.target.reset();
        setLoading(false);
        dispatch({type: "reset"});
        setHttpSuccess({message: "Contrato salvo com sucesso!"});

        setResetScreen(true);
        setTimeout(() => {
            setResetScreen(false);
            setProposals([]);
            setProposalsLabels([]);
            getProposals();
        }, 10);
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

    return (
        <>
        <VHeader />
        
        {!resetScreen && (isDeveloper || isAdmin) &&
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
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
                                <p>Editar Contrato</p>
                                </>
                                }
                                {!parameters.id &&
                                <>
                                <i className="material-icons float-left">add</i>
                                <p>Adicionar Contrato</p>
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
                                <Form id='contractForm' onSubmit={onSubmit} noValidate={true}>
                                    <Row>
                                        <Col>
                                            <small>Campos com * são obrigatórios.</small>
                                        </Col>
                                        <br></br>
                                        <br></br>
                                    </Row>
                                    <Row>
                                        {!parameters.id &&
                                        <Col lg={3}>
                                            <CustomInput key="proposal_code" type="select"
                                                placeholder="Código da Proposta *" name="proposal_code"
                                                data={proposalsLabels} 
                                                required={true}
                                                value={state.proposal_code}
                                                onChange={changeField} />
                                        </Col>
                                        }
                                        {parameters.id &&
                                        <Col lg={3}>
                                            <CustomInput key="proposal_code" type="text"
                                                placeholder="Código da Proposta *" name="proposal_code"
                                                maxlength={255}
                                                required={true}
                                                disabled={"true"}
                                                value={state.proposal_code} />
                                        </Col>
                                        }
                                        <Col lg={3}>
                                            <CustomInput key="building_type" type="text"
                                                placeholder="Tipo de Obra *" name="building_type"
                                                maxlength={255}
                                                required={true}
                                                value={state.building_type}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={3}>
                                            <CustomInput key="value2" type="text"
                                                placeholder="Valor *" name="value2"
                                                value={state.value2}
                                                required={true}
                                                disabled={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={3}>
                                            <CustomInput key="engineer_id" type="select"
                                                placeholder="Engenheiro *" name="engineer_id"
                                                endpoint={"engineers"} endpoint_field={"name"}
                                                value={state.engineer_id}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={12}>
                                            <CustomInput key="description" type="text"
                                                placeholder="Descrição da Obra *" name="description"
                                                maxlength={1000}
                                                required={true}
                                                value={state.description}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={12}>
                                            <CustomInput key="meters" type="text"
                                                placeholder="Metros Quadrados da Obra *" name="meters"
                                                maxlength={1000}
                                                required={true}
                                                value={state.meters}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={4}>
                                            <CustomInput key="zipcode" type="mask"
                                                mask={"99999-999"}
                                                placeholder="CEP *" name="zipcode"
                                                value={state.zipcode}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={4}>
                                            <CustomInput key="city_id" type="select"
                                                endpoint={"cities"} endpoint_field={"name"} value={state.city_id}
                                                placeholder="Cidade *" name="city_id"
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={4}>
                                            <CustomInput key="address" type="text" value={state.address}
                                                placeholder="Endereço *" name="address"
                                                maxlength={255}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={4}>
                                            <CustomInput key="neighborhood" type="text" value={state.neighborhood}
                                                placeholder="Bairro *" name="neighborhood"
                                                maxlength={100}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={4}>
                                            <CustomInput key="number" type="text" value={state.number}
                                                placeholder="Número" name="number" maxlength={4}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={4}>
                                            <CustomInput key="complement" type="text" 
                                                maxlength={255} value={state.complement}
                                                placeholder="Complemento" name="complement"
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={3}>
                                            <CustomInput key="witness_one_name" type="text"
                                                maxlength={255}
                                                placeholder="Testemunha 1 *" name="witness_one_name"
                                                value={state.witness_one_name}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={3}>
                                            <CustomInput key="witness_one_cpf" type="mask"
                                                mask={"999.999.999-99"}
                                                placeholder="CPF da Testemunha 1 *" name="witness_one_cpf"
                                                value={state.witness_one_cpf}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={3}>
                                            <CustomInput key="witness_two_name" type="text"
                                                maxlength={255}
                                                placeholder="Testemunha 2 *" name="witness_two_name"
                                                value={state.witness_two_name}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
                                        <Col lg={3}>
                                            <CustomInput key="witness_two_cpf" type="mask"
                                                mask={"999.999.999-99"}
                                                placeholder="CPF da Testemunha 2 *" name="witness_two_cpf"
                                                value={state.witness_two_cpf}
                                                required={true}
                                                onChange={changeField} />
                                        </Col>
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

        {!(isDeveloper || isAdmin) &&
            <Forbidden />
        }
        
        </>
    )
};

export default ContractForm;
