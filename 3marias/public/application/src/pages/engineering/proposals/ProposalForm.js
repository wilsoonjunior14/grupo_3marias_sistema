import Container from "react-bootstrap/esm/Container";
import VHeader from "../../../components/vHeader/vHeader";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Error from '../../../components/error/Error';
import Success from '../../../components/success/Success';
import Loading from "../../../components/loading/Loading";
import CustomInput from "../../../components/input/CustomInput";
import '../../../App.css';
import { useEffect, useReducer, useState } from "react";
import Table from "react-bootstrap/Table";
import CustomButton from "../../../components/button/Button";
import { performRequest } from "../../../services/Api";
import NoEntity from "../../../components/table/NoEntity";
import "./Proposal.css";
import CustomSelect from "../../../components/input/CustomSelect";
import Button from "react-bootstrap/esm/Button";
import Modal from 'react-bootstrap/Modal';
import ClientForm from '../../admin/clients/ClientForm';
import TableButton from "../../../components/button/TableButton";
import { formatDate, formatDateToServer, formatDoubleValue } from "../../../services/Format";

const ProposalForm = ({}) => {
    const [loading, setLoading] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [showAddClientModal, setShowAddClientModal] = useState(false);
    const [clients, setClients] = useState([]);
    const [projects, setProjects] = useState([]);
    const [cpfs, setCpfs] = useState([]);
    const [emails, setEmails] = useState([]);
    const [reloadFields, setReloadFields] = useState(false);
    const [initialState, setInitialState] = useState({global_value: "0"});

    const [paymentsClient, setPaymentsClient] = useState([]);
    const [paymentsBank, setPaymentsBank] = useState([]);

    const reducer = (state, action) => {
        if (action.type === "reset") {
            return initialState;
        }

        if (action.type === "data") {
            return action.data;
        }
    
        const result = { ...state };
        result[action.type] = action.value;

        if (action.type === "project_name") {
            const project = projects.filter((p) => p.name === action.value)[0];
            result["description"] = project.description;
            result["project_id"] = project.id;   
        }
        return result;
    };
    const [state, dispatch] = useReducer(reducer, initialState);

    const [step, setStep] = useState(1);
    const initialSteps = [
        {
            id: 1,
            name: "1. Informações Gerais",
            class: "proposal-menu-item-active"
        },
        {
            id: 2,
            name: "2. Proposta",
            class: ""
        },
        {
            id: 3,
            name: "3. Negociação",
            class: ""
        }
    ];
    const [steps, setSteps] = useState(initialSteps);

    useEffect(() => {
        getClients();
        getProjects();
    }, []);

    const getClients = () => {
        performRequest("GET", "/v1/clients", null)
        .then(successGetClients)
        .catch(errorResponse);
    }

    const successGetClients = (res) => {
        setClients(res.data);
    }

    const errorResponse = (err) => {
        if (err.data && err.data.message) {
            setHttpError(err.data.message);
        } else {
            setHttpError(err.data);
        }
        setLoading(false);
    }

    const getProjects = () => {
        performRequest("GET", "/v1/projects", null)
        .then(successGetProjects)
        .catch(errorResponse);
    }

    const successGetProjects = (res) => {
        setProjects(res.data);
    }

    const onSetStep = (s) => {
        steps[s.id - 1].class = "proposal-menu-item-active";
        steps[step - 1].class = "";
        setStep(s.id);
        setSteps(steps);
        setHttpError(null);
    }

    const onChangeField = (e) => {
        const { name, value } = e.target;
        dispatch({ type: name, value });
    };

    const onChangeName = (evt) => {
        const { name, value } = evt.target;
        dispatch({ type: name, value });

        const cpfsFiltered = clients
        .filter((client) => client.name === evt.target.value)
        .map((client) => {
            if (client.name === evt.target.value) {
                return client.cpf;
            }
        });
        setCpfs(cpfsFiltered);

        if (cpfsFiltered.length === 1) {
            onChangeField({target: {name: "client_cpf", value: cpfsFiltered[0]}});
            onUpdateClientFields(value, cpfsFiltered[0]);
        }

        setReloadFields(true);
        setTimeout(() => {
            setReloadFields(false);
        }, 1);
    }

    const onChangeCPF = (evt) => {
        const { name, value } = evt.target;
        dispatch({ type: name, value });

        onUpdateClientFields(state.client_name, value);
    }

    const onUpdateClientFields = (name, cpf) => {
        var client = clients.filter((client) => client.name === name && client.cpf === cpf)[0];
        const number = client.number && client.number > 0 ? client.number : 1;

        onChangeField({target: {name: "city_id", value: client.city_id}});
        onChangeField({target: {name: "neighborhood", value: client.neighborhood}});
        onChangeField({target: {name: "address", value: client.address}});
        onChangeField({target: {name: "zipcode", value: client.zipcode}});
        onChangeField({target: {name: "number", value: number}});
        onChangeField({target: {name: "complement", value: client.complement}});
    }

    const onRefreshClients = (evt) => {
        setStep(0);
        setTimeout(() => {
            setStep(1);
        }, 50);
    }

    const onNext = () => {
        onValidateFirstStep();
        onValidateSecondStep();
    }

    const onValidateFirstStep = () => {
        if (step !== 1) {
            return;
        }
        if (!state.client_name || state.client_name === "") {
            setHttpError({message: "Cliente não informado."});
            return;
        }
        if (!state.client_cpf || state.client_cpf === "") {
            setHttpError({message: "CPF do Cliente não informado."});
            return;
        }
        onSetStep(steps[1]);
    }

    const onValidateSecondStep = () => {
        if (step !== 2) {
            return;
        }
        if (!state.project_id || state.project_id === "") {
            setHttpError({message: "Projeto não informado."});
            return;
        }
        if (!state.construction_type || state.construction_type === "") {
            setHttpError({message: "Tipo de Empreendimento não informado."});
            return;
        }
        if (!state.proposal_type || state.proposal_type === "") {
            setHttpError({message: "Tipo de Proposta não informado."});
            return;
        }
        if (!state.global_value || state.global_value === "") {
            setHttpError({message: "Valor Global da Proposta não informado."});
            return;
        }
        const gValue = state.global_value.replace(/,\d{2}/g, "").replace(".", "")
        if (gValue <= 0) {
            setHttpError({message: "Valor Global da Proposta inválida."});
            return;
        }
        if (!state.proposal_date || state.proposal_date === "") {
            setHttpError({message: "Data da Proposta não informado."});
            return;
        }
        if (!state.zipcode || state.zipcode === "") {
            setHttpError({message: "CEP não informado."});
            return;
        }
        if (!state.proposal_date || state.proposal_date === "") {
            setHttpError({message: "Data da Proposta não informado."});
            return;
        }
        const zipcodeRegex = RegExp(/\d{5}-\d{3}/g);
        if (!zipcodeRegex.test(state.zipcode)) {
            setHttpError({message: "CEP inválido."});
            return;
        }
        if (!state.city_id || state.city_id === "") {
            setHttpError({message: "Cidade não informada."});
            return;
        }
        if (!state.address || state.address === "") {
            setHttpError({message: "Endereço não informado."});
            return;
        }
        if (state.address.length > 255) {
            setHttpError({message: "Endereço não pode conter mais que 255 caracteres."});
            return;
        }
        if (state.address.length < 3) {
            setHttpError({message: "Endereço deve conter mais que 3 caracteres."});
            return;
        }
        if (!state.neighborhood || state.neighborhood === "") {
            setHttpError({message: "Bairro não informado."});
            return;
        }
        if (state.neighborhood.length > 100) {
            setHttpError({message: "Bairro não pode conter mais que 100 caracteres."});
            return;
        }
        if (state.neighborhood.length < 3) {
            setHttpError({message: "Bairro deve conter mais que 3 caracteres."});
            return;
        }
        const numberRegex = RegExp(/^\d+$/g);
        if (state.number && state.number !== "" && !numberRegex.test(state.number)) {
            setHttpError({message: "Número do endereço inválido."});
            return;
        }
        if (state.complement && state.complement !== "" && state.complement.length > 255) {
            setHttpError({message: "Complemento do endereço não pode conter mais que 255 caracteres."});
            return;
        }
        if (!state.description || state.description === "") {
            setHttpError({message: "Descrição da Proposta não informado."});
            return;
        }
        if (state.description.length > 1000) {
            setHttpError({message: "Descrição da Proposta não pode conter mais que 1000 caracteres."});
            return;
        }
        if (state.description.length < 3) {
            setHttpError({message: "Descrição da Proposta deve conter mais que 3 caracteres."});
            return;
        }
        onSetStep(steps[2]);
    }

    const onAddClientPayment = () => {
        console.log("adicionando pagamento do cliente");
        setHttpError(null);
        const paymentObj = {
            code: paymentsClient.length,
            type: state.client_payment_type,
            value: state.client_payment_value,
            desired_date: state.client_payment_date,
            description: state.client_payment_description,
            source: "Cliente" 
        };
        const validated = onValidatePayment(paymentObj, false);
        if (validated) {
            paymentsClient.push(paymentObj);
            setPaymentsClient(paymentsClient);
            onChangeField({target: {name: "client_payment_type", value: ""}});
            onChangeField({target: {name: "client_payment_date", value: ""}});
            onChangeField({target: {name: "client_payment_description", value: ""}});
            setHttpSuccess({message: "Pagamento Adicionado com Sucesso!"});
        }
    }

    const onValidatePayment = (obj, isBank) => {
        if (isBank) {
            if (!obj.bank || obj.bank === "") {
                setHttpError({message: "Banco não informado."});
                return;
            }
        }
        if (!obj.type || obj.type === "") {
            setHttpError({message: "Tipo de Pagamento não informado."});
            return;
        }
        if (!obj.value || obj.value === "") {
            setHttpError({message: "Valor do Pagamento não informado."});
            return;
        }
        const gValue = obj.value.replace(/,\d{2}/g, "").replace(".", "")
        if (gValue <= 0) {
            setHttpError({message: "Valor do Pagamento inválido."});
            return;
        }
        if (!isBank) {
            if (!obj.desired_date || obj.desired_date === "") {
                setHttpError({message: "Data Prevista de Pagamento não informado."});
                return;
            }
        }
        if (!obj.description || obj.description === "") {
            setHttpError({message: "Descrição de Pagamento não informado."});
            return;
        }
        if (obj.description.length > 255) {
            setHttpError({message: "Descrição do Pagamento não pode conter mais que 1000 caracteres."});
            return;
        }
        if (obj.description.length < 3) {
            setHttpError({message: "Descrição do Pagamento deve conter mais que 3 caracteres."});
            return;
        }
        return true;
    }

    const onDeleteClientPayment = (payment) => {
        const newPayments = paymentsClient.filter((p) => p.code !== payment.code);
        setPaymentsClient(newPayments);
        setHttpSuccess({message: "Pagamento Excluído!"});
    }

    const onAddBankPayment = () => {
        setHttpError(null);
        const paymentObj = {
            code: paymentsBank.length,
            bank: state.bank,
            type: state.bank_payment_type,
            value: state.bank_payment_value,
            description: state.bank_payment_description,
            source: "Banco" 
        };
        const validated = onValidatePayment(paymentObj, true);
        if (validated) {
            paymentsBank.push(paymentObj);
            setPaymentsBank(paymentsBank);
            onChangeField({target: {name: "bank_payment_type", value: ""}});
            onChangeField({target: {name: "bank_payment_value", value: ""}});
            onChangeField({target: {name: "bank", value: ""}});
            onChangeField({target: {name: "bank_payment_description", value: ""}});
            setHttpSuccess({message: "Pagamento Adicionado com Sucesso!"});
        }
    }

    const onDeleteBankPayment = (payment) => {
        const newPayments = paymentsBank.filter((p) => p.code !== payment.code);
        setPaymentsBank(newPayments);
        setHttpSuccess({message: "Pagamento Excluído!"});
    }

    const onSubmit = () => {
        if (!state.discount || state.discount === "") {
            setHttpError({message: "Valor do Desconto não informado."});
            return;            
        }

        const globalValue = Number(state.global_value.replace(".", "").replace(",", "."));
        const discount = Number(state.discount.replace(".", "").replace(",", "."));

        var payments = 0;
        var clientPayments = [];
        paymentsClient.forEach((p) => {
            const value = Number(p.value.replace(".", "").replace(",", "."));
            payments = payments + value;

            const cPay = Object.assign({}, p);
            cPay.value = value;
            cPay.desired_date = formatDateToServer(p.desired_date);
            clientPayments.push(cPay);
        });
        var bankPayments = [];
        paymentsBank.forEach((p) => {
            const value = Number(p.value.replace(".", "").replace(",", "."));
            payments = payments + value;

            const bPay = Object.assign({}, p);
            bPay.value = value;
            bankPayments.push(bPay);
        });

        if (globalValue - discount !== payments) {
            const diff = (globalValue - discount) - payments;
            const diffFormated = formatDoubleValue(diff);
            setHttpError({message: "Pagamentos e Valor Global estão diferentes. Diferença de: R$ "+diffFormated});
            return;
        }
        
        setHttpSuccess(null);
        setHttpError(null);

        const payload = Object.assign({}, state);
        payload.global_value = globalValue;
        payload.discount = discount;
        payload.proposal_date = formatDateToServer(payload.proposal_date);
        payload.clientPayments = clientPayments;
        payload.bankPayments = bankPayments;
        
        setLoading(true);

        performRequest("POST", "/v1/proposals", payload)
        .then(onSuccessProposal)
        .catch(errorResponse);
    }

    const onSuccessProposal = (res) => {
        setHttpSuccess({message: "Proposta salva com sucesso!"});
        setLoading(false);
        dispatch({ type: "reset" });
        setStep(1);
        setSteps(initialSteps);
    }
    
    return (
        <>
        <VHeader />

        <Modal fullscreen={true} show={showAddClientModal} onExit={onRefreshClients}
            onHide={() => {setShowAddClientModal(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>Cadastrar Novo Cliente</Modal.Title>
                </Modal.Header>
            <Modal.Body>
                <ClientForm disableHeader={true} />
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowAddClientModal(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            {!loading && httpError &&
                <Error message={httpError.message} />
            }
            {!loading && httpSuccess &&
                <Success message={httpSuccess.message} />
            }
            <Row className="proposal-menu">
                {steps.map((s) => 
                    <Col className={s.class + " proposal-menu-item"}>
                        {s.name}
                    </Col>
                )}
            </Row>
            <Row>
                <Col>
                    <Form>
                        <Card>
                            <Card.Body>
                                {step === 1 &&
                                <>
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Proposta - Informações Gerais
                                </Card.Title>
                                <Row>
                                    <Col lg={4}>
                                        <CustomInput key="client_name" type="select2"
                                            onChange={onChangeName}
                                            endpoint="clients" endpoint_field="name" value={state.client_name}
                                            placeholder="Nome do Cliente *" name="client_name" />
                                    </Col>
                                    {!reloadFields &&
                                    <>
                                    <Col lg={4}>
                                        <CustomInput key="client_cpf" type="select" 
                                            data={cpfs} onChange={onChangeCPF} value={state.client_cpf}
                                            placeholder="CPF do Cliente *" name="client_cpf" />
                                    </Col>
                                    </>
                                    }
                                </Row>
                                <Row>
                                    <Col xs={3}>
                                        <CustomButton color="success" 
                                            onClick={() => setShowAddClientModal(true)} name={"+ Cadastrar Cliente"} />
                                    </Col>
                                    <Col xs={9}></Col>
                                </Row>
                                <Row>
                                    <Col xs={10}></Col>
                                    <Col xs={2}>
                                        <CustomButton color="success" onClick={onNext} name={"Próximo"} />
                                    </Col>
                                </Row>
                                </>
                                }

                                {step === 2 &&
                                <>
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Proposta - Informações da Proposta
                                </Card.Title>
                                <Row>
                                    <Col lg={4}>
                                        <CustomInput key="project_name" type="select2"
                                            endpoint={"projects"} endpoint_field={"name"}
                                            placeholder="Projeto *" name="project_name" value={state.project_name}
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="construction_type" type="select"
                                            data={["Residencial", "Comercial", "Predial"]} value={state.construction_type}
                                            placeholder="Tipo de Empreendimento *" name="construction_type"
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="proposal_type" type="select"
                                            data={["Ouro", "Prata", "Bronze"]} value={state.proposal_type}
                                            placeholder="Tipo de Proposta*" name="proposal_type"
                                            onChange={onChangeField} />
                                    </Col>
                                </Row>
                                <Row style={{marginBottom: 12}}>
                                    <Col lg={4}>
                                        <CustomInput key="global_value" type="money" value={state.global_value2}
                                            placeholder="Valor Global *" name="global_value"
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="proposal_date" 
                                            type="mask" value={state.proposal_date} mask={"99/99/9999"}
                                            onChange={onChangeField}
                                            placeholder="Data da Proposta *" name="proposal_date" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="zipcode" type="mask" mask={"99999-999"}
                                            placeholder="CEP *" name="zipcode" value={state.zipcode}
                                            onChange={onChangeField} />
                                    </Col>
                                </Row>
                                <Row>
                                    <Col lg={4}>
                                        <CustomInput key="city_id" type="select"
                                            endpoint={"cities"} endpoint_field={"name"} value={state.city_id}
                                            placeholder="Cidade *" name="city_id"
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="address" type="text" value={state.address}
                                            placeholder="Endereço *" name="address"
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="neighborhood" type="text" value={state.neighborhood}
                                            placeholder="Bairro *" name="neighborhood"
                                            onChange={onChangeField} />
                                    </Col>
                                </Row>
                                <Row>
                                    <Col lg={4}>
                                        <CustomInput key="number" type="text" value={state.number}
                                            placeholder="Número" name="number"
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={8}>
                                        <CustomInput key="complement" type="text" maxlength={255} value={state.complement}
                                            placeholder="Complemento" name="complement"
                                            onChange={onChangeField} />
                                    </Col>
                                    <Col lg={6}>
                                        <CustomInput key="description" type="textarea" maxlength={1000} value={state.description}
                                            placeholder="Descrição da Proposta *" name="description"
                                            onChange={onChangeField} />
                                    </Col>
                                </Row>
                                <Row>
                                    <Col xs={2}>
                                        <CustomButton color="success" onClick={() => onSetStep(steps[0])} name={"Voltar"} />
                                    </Col>
                                    <Col xs={8}></Col>
                                    <Col xs={2}>
                                        <CustomButton color="success" onClick={() => onNext()} name={"Próximo"} />
                                    </Col>
                                </Row>
                                </>
                                }
                                {step === 3 &&
                                <>
                                <Card.Title>
                                    <i className="material-icons float-left">add</i>
                                    Proposta - Negociação
                                </Card.Title>
                                <Row>
                                    <Col>
                                        <Card.Title>
                                            <i className="material-icons float-left">attach_money</i>
                                                Atribuição de Descontos
                                        </Card.Title>
                                        <Row>
                                            <Col xs={4}>
                                                <CustomInput 
                                                    type={"money"} 
                                                    placeholder={"Desconto *"} 
                                                    name="discount"
                                                    onChange={onChangeField}
                                                    value={state.discount}
                                                     />
                                            </Col>
                                        </Row>
                                    </Col>
                                </Row>
                                <br></br>
                                <Row>
                                    <Col>
                                        <Card.Title>
                                            <i className="material-icons float-left">attach_money</i>
                                                Pagamentos - Entrada do Cliente
                                        </Card.Title>
                                        <Row>
                                            <Col>
                                                <CustomSelect name="client_payment_type" 
                                                    placeholder="Tipo de Pagamento *" 
                                                    data={["À Vista", "Parcela"]}
                                                    value={state.client_payment_type}
                                                    onChange={onChangeField} />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"money"} 
                                                placeholder={"Valor R$ *"}
                                                value={state.client_payment_value_2}
                                                onChange={onChangeField} 
                                                name="client_payment_value" />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"date"} 
                                                placeholder={"Data Prevista *"}
                                                value={state.client_payment_date}
                                                onChange={onChangeField} 
                                                name="client_payment_date" />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"text"} 
                                                placeholder={"Descrição *"} 
                                                name={"client_payment_description"}
                                                onChange={onChangeField}
                                                value={state.client_payment_description}
                                                 />
                                            </Col>
                                            <Col>
                                                <Button variant="success"
                                                    onClick={onAddClientPayment} 
                                                    style={{height: 60, width: 60}}>
                                                    <i className="material-icons">add</i>
                                                </Button>
                                            </Col>
                                        </Row>
                                        <Row>
                                            <Table striped responsive>
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        <th>Tipo de Pagamento</th>
                                                        <th>Valor</th>
                                                        <th>Data Prevista</th>
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {paymentsClient.length === 0 &&
                                                    <NoEntity message={"Nenhuma pagamento adicionado."} />
                                                    }
                                                    {paymentsClient.map((payment) => 
                                                    <tr>
                                                        <td>{payment.description}</td>
                                                        <td>{payment.type}</td>
                                                        <td>R$ {payment.value}</td>
                                                        <td>{payment.desired_date}</td>
                                                        <td>
                                                            <TableButton name="btnDelete" tooltip="Deletar" 
                                                                onClick={() => onDeleteClientPayment(payment)}
                                                                icon="delete" color="light" />
                                                        </td>
                                                    </tr>
                                                    )}
                                                </tbody>
                                            </Table>
                                        </Row>
                                    </Col>
                                </Row>
                                <br></br>
                                <Row>
                                    <Col>
                                        <Card.Title>
                                            <i className="material-icons float-left">attach_money</i>
                                                Pagamentos - Via Instituição Financeira
                                        </Card.Title>
                                        <Row>
                                            <Col>
                                                <CustomSelect name="bank" 
                                                    placeholder="Banco *" 
                                                    data={["Bradesco", "Banco do Brasil", "Santander", "Caixa Econômica"]}
                                                    onChange={onChangeField}
                                                    value={state.bank}
                                                    />
                                            </Col>
                                            <Col>
                                                <CustomSelect name="bank_payment_type" placeholder="Tipo de Pagamento *" 
                                                data={["À Vista", "Medições"]}
                                                onChange={onChangeField}
                                                value={state.bank_payment_type} />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"money"} placeholder={"Valor *"} 
                                                name="bank_payment_value"
                                                onChange={onChangeField}
                                                value={state.bank_payment_value2} />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"text"} placeholder={"Descrição *"} 
                                                name={"bank_payment_description"}
                                                onChange={onChangeField}
                                                value={state.bank_payment_description} />
                                            </Col>
                                            <Col>
                                                <Button variant="success" onClick={onAddBankPayment} 
                                                    style={{height: 60, width: 60}}>
                                                    <i className="material-icons">add</i>
                                                </Button>
                                            </Col>
                                        </Row>
                                        <Row>
                                            <Table striped responsive>
                                                <thead>
                                                    <tr>
                                                        <th>Descrição</th>
                                                        <th>Banco</th>
                                                        <th>Tipo de Pagamento</th>
                                                        <th>Valor</th>
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {paymentsBank.length === 0 &&
                                                    <NoEntity message={"Nenhuma pagamento adicionado."} />
                                                    }
                                                    {paymentsBank.map((payment) => 
                                                    <tr>
                                                        <td>{payment.description}</td>
                                                        <td>{payment.bank}</td>
                                                        <td>{payment.type}</td>
                                                        <td>{payment.value}</td>
                                                        <td>
                                                            <TableButton name="btnDelete" tooltip="Deletar" 
                                                                onClick={() => onDeleteBankPayment(payment)}
                                                                icon="delete" color="light" />
                                                        </td>
                                                    </tr>
                                                    )}
                                                </tbody>
                                            </Table>
                                        </Row>
                                        <Row>
                                            <Col xs={2}>
                                                <CustomButton color="success" onClick={() => onSetStep(steps[1])} name={"Voltar"} />
                                            </Col>
                                            <Col xs={8}></Col>
                                            <Col xs={2}>
                                                <Button variant="success"
                                                    type="button"
                                                    size="lg"
                                                    onClick={onSubmit}
                                                    disabled={loading}>
                                                    {loading ? <Loading />
                                                                : 
                                                                'Concluir'}
                                                </Button>
                                            </Col>
                                        </Row>
                                    </Col>
                                </Row>
                                </>
                                }
                            </Card.Body>
                        </Card>
                    </Form>
                </Col>
            </Row>
        </Container>
        </>
    )
};

export default ProposalForm;
