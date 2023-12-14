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
import { useEffect, useState } from "react";
import Table from "react-bootstrap/Table";
import CustomButton from "../../../components/button/Button";
import { performRequest } from "../../../services/Api";
import NoEntity from "../../../components/table/NoEntity";
import "./Proposal.css";
import CustomSelect from "../../../components/input/CustomSelect";
import Button from "react-bootstrap/esm/Button";
import Modal from 'react-bootstrap/Modal';
import ClientForm from '../../admin/clients/ClientForm';

const ProposalForm = ({}) => {
    const [loading, setLoading] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [showAddClientModal, setShowAddClientModal] = useState(false);

    const [step, setStep] = useState(1);
    const [steps, setSteps] = useState([
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
    ]);

    const onSetStep = (s) => {
        steps[s.id - 1].class = "proposal-menu-item-active";
        steps[step - 1].class = "";
        setStep(s.id);
        setSteps(steps);
    }

    const onChangeName = (evt) => {

    }

    const onChangeCPF = (evt) => {

    }

    const onChangeEmail = (evt) => {

    }
    
    return (
        <>
        <VHeader />

        <Modal fullscreen={true} show={showAddClientModal} onHide={() => {setShowAddClientModal(false)}}>
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
                    <Col onClick={() => {onSetStep(s)}} className={s.class + " proposal-menu-item"}>
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
                                            endpoint="clients" endpoint_field="name"
                                            placeholder="Nome do Cliente *" name="client_name" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="client_cpf" type="select" 
                                            data={[]} onChange={onChangeEmail}
                                            placeholder="CPF do Cliente *" name="client_cpf" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="client_email" type="select" 
                                            data={[]} onChange={onChangeEmail}
                                            placeholder="Email do Cliente *" name="client_email" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="proposal_date" type="mask" mask={"99/99/9999"} 
                                            onChange={() => {}}
                                            placeholder="Data da Proposta *" name="proposal_date" />
                                    </Col>
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
                                        <CustomButton color="success" onClick={() => {}} name={"Próximo"} />
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
                                        <CustomInput key="proposal_building_type" type="select"
                                            data={["Residencial", "Comercial", "Predial"]}
                                            placeholder="Tipo de Empreendimento *" name="proposal_building_type" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="proposal_type" type="select"
                                            data={["Ouro", "Prata", "Bronze"]}
                                            placeholder="Tipo de Proposta*" name="proposal_type" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="global_value" type="text"
                                            placeholder="Valor Global *" name="global_value" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="zipcode" type="mask" mask={"99999-999"}
                                            placeholder="CEP *" name="zipcode" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="city_id" type="select2"
                                            endpoint={"cities"} endpoint_field={"name"}
                                            placeholder="Cidade *" name="city_id" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="address" type="text"
                                            placeholder="Endereço *" name="address" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="neighborhood" type="text"
                                            placeholder="Bairro *" name="neighborhood" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="number" type="text"
                                            placeholder="Número *" name="number" />
                                    </Col>
                                    <Col lg={4}>
                                        <CustomInput key="complement" type="text"
                                            placeholder="Complemento" name="complement" />
                                    </Col>
                                </Row>
                                <Row>
                                    <Col xs={2}>
                                        <CustomButton color="success" onClick={() => {}} name={"Voltar"} />
                                    </Col>
                                    <Col xs={8}></Col>
                                    <Col xs={2}>
                                        <CustomButton color="success" onClick={() => {}} name={"Próximo"} />
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
                                                <CustomInput type={"text"} placeholder={"Desconto R$ *"} name="discount" />
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
                                                <CustomSelect name="payment_type" placeholder="Tipo de Pagamento *" data={["À Vista", "Parcela"]} />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"text"} placeholder={"Valor R$ *"} name="global_value" />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"text"} placeholder={"Descrição *"} name={"description"} />
                                            </Col>
                                            <Col>
                                                <Button variant="success" style={{height: 60, width: 60}}>
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
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <NoEntity message={"Nenhuma pagamento adicionado."} />
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
                                                <CustomSelect name="payment_type" placeholder="Tipo de Pagamento *" data={["À Vista"]} />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"text"} placeholder={"Valor R$ *"} name="global_value" />
                                            </Col>
                                            <Col>
                                                <CustomInput type={"text"} placeholder={"Descrição *"} name={"description"} />
                                            </Col>
                                            <Col>
                                                <Button variant="success" style={{height: 60, width: 60}}>
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
                                                        <th>Opções</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <NoEntity message={"Nenhuma pagamento adicionado."} />
                                                </tbody>
                                            </Table>
                                        </Row>
                                        <Row>
                                            <Col xs={2}>
                                                <CustomButton color="success" onClick={() => {}} name={"Voltar"} />
                                            </Col>
                                            <Col xs={8}></Col>
                                            <Col xs={2}>
                                                <CustomButton color="success" onClick={() => {}} name={"Concluir"} />
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
