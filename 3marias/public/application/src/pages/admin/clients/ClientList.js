import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import { useState } from 'react';
import CustomButton from '../../../components/button/Button';
import Modal from 'react-bootstrap/Modal';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import CustomInputFile from '../../../components/input/CustomInputFile';
import CustomInput from '../../../components/input/CustomInput';
import Table from 'react-bootstrap/esm/Table';
import Form from 'react-bootstrap/Form';
import TableButton from '../../../components/button/TableButton';
import Error from '../../../components/error/Error';
import Button from 'react-bootstrap/esm/Button';
import { Tooltip } from 'react-tooltip';
import Loading from '../../../components/loading/Loading';
import { performCustomRequest, performRequest } from '../../../services/Api';
import Success from '../../../components/success/Success';
import NoEntity from '../../../components/table/NoEntity';
import { formatDate, formatDateTime } from '../../../services/Format';
import { useNavigate } from 'react-router-dom';
import config from "../../../config.json";

export default function ClientList() {

    const [showModal, setShowModal] = useState(false);
    const [showDeleteModal, setShowDeleteModal] = useState(false);
    const [showDocumentsModal, setShowDocumentsModal] = useState(false);
    const [showClientModal, setShowClientModal] = useState(false);
    const [client, setClient] = useState({});
    const [documents, setDocuments] = useState([]);
    const [document, setDocument] = useState({});
    const [state, setState] = useState({});
    const [errorMessage, setErrorMessage] = useState(null);
    const [successMessage, setSuccessMessage] = useState(null);
    const [isLoadingDocuments, setIsLoadingDocuments] = useState(false);
    const [show, setShow] = useState(true);
    const navigate = useNavigate();

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'email',
            placeholder: 'Email',
            type: 'email',
            maxlength: 100
        },
        {
            id: 'cpf',
            placeholder: 'CPF',
            type: 'text',
            maxlength: 14
        }
    ];

    const table = {
        fields: ["#", "Nome", "CPF", "Email"],
        amountOptions: 1,
        bodyFields: ["id", "name", "cpf", "email"]
    };

    const customOptions = [
        {
            name: "see_clientdata",
            tooltip: "Ver Ficha de Cadastro do Cliente",
            icon: "visibility",
            onClick: (evt) => {setClient(evt); window.open(config.url + "/clientData/" + evt.id)}
        },
        {
            name: "see_documents",
            tooltip: "Ver Documentos",
            icon: "assignment_ind",
            onClick: (evt) => {setClient(evt); setShowDocumentsModal(true)}
        },
        {
            name: "upload_documents",
            tooltip: "Upload de Documentos",
            icon: "file_upload",
            onClick: (evt) => {setClient(evt); setShowModal(true);}
        }
    ];

    const changeField = (e) => {
        const { name, value } = e.target;
        if (e.target.name === "document") {
            state[name] = e.target.files[0];
        } else {
            state[name] = value;
        }
        setState(state);
    }

    const onAddDoc = (e) => {
        e.preventDefault();
        if (!state.description || state.description.length === 0) {
            setErrorMessage("Campo Descrição está inválido.");
            return;
        }

        const exists = documents.some((doc) => 
            doc.description.toString().toLowerCase() === state.description.toString().toLowerCase());
        if (exists) {
            setErrorMessage("Documento com mesma Descrição já utilizado.");
            return;
        }
        
        if (!state.document) {
            setErrorMessage("Campo Documento não adicionado.");
            return;
        }

        documents.push(state);
        setDocuments(documents);
        setState({});
        setErrorMessage(null);
        e.target.reset();
    }

    const onDeleteDoc = (doc) => {
        const newDocs = documents.filter((document) => 
            document.description !== doc.description);
        setDocuments(newDocs);
    }

    const onFinishUpload = () => {
        setIsLoadingDocuments(true);
        setErrorMessage(null);
        setSuccessMessage(null);

        var payload = new FormData();
        documents.forEach((doc) => {
            payload.append("descriptions[]", doc.description);
            payload.append("file[]", doc.document);
        });
        payload.append("client_id", client.id);

        performCustomRequest("POST", "/v1/clients/docs", payload)
        .then(successPostDocs)
        .catch(errorResponse);
    }

    const successPostDocs = (res) => {
        setIsLoadingDocuments(false);
        setShowModal(false);
        setDocuments([]);
        setSuccessMessage("Arquivos salvos com sucesso!");
        setShow(false);
        setTimeout(() => {
            setShow(true);
        }, 1000);
    }

    const errorResponse = (err) => {
        setIsLoadingDocuments(false);
        setDocuments([]);
        setShowModal(false);
        setShowDeleteModal(false);
        setErrorMessage("Não foi possível realizar o upload dos documentos.");
    }

    const onConfirmDeleteDocument = () => {
        setIsLoadingDocuments(true);
        setErrorMessage(null);
        setSuccessMessage(null);

        performRequest("DELETE", "/v1/clients/deleteDocs/"+document.id, null)
        .then(successDeleteDocs)
        .catch(errorResponse);
    }

    const successDeleteDocs = (res) => {
        setIsLoadingDocuments(false);
        setSuccessMessage("Arquivo excluído com sucesso!");
        setShowDeleteModal(false);
        setShow(false);
        setTimeout(() => {
            setShow(true);
        }, 1000);
    }
    
    return (
        <>
            <VHeader />

            <Modal 
                show={showModal} 
                onHide={() => {setShowModal(false)}} 
                size={"lg"}
                centered>
                <Modal.Header closeButton>
                    <Modal.Title>Adicionar Documentos de {client.name}</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    {errorMessage &&
                    <Error message={errorMessage} />
                    }
                    <Form onSubmit={onAddDoc}>
                        <Row>
                            
                            <Col xs={5}>
                                <CustomInput type={"text"} name={"description"} maxlength={100} required={true}
                                    value={state.description} onChange={changeField} 
                                    placeholder={"Descrição *"}  />
                            </Col>
                            <Col xs={5}>
                                <CustomInputFile name={"document"} required={true}
                                    value={state.document} onChange={changeField} 
                                    accept="application/pdf"
                                    placeholder={"Documento *"}  />
                            </Col>
                            <Col xs={2}>
                                <Button type={"submit"} style={{maxWidth: '80px'}} variant={"success"} data-tooltip-id={"Excluir"} data-tooltip-content={"Excluir"}>
                                    <i className="material-icons">add</i>
                                </Button>
                                <Tooltip id={"Excluir"} />
                            </Col>
                        </Row>
                    </Form>
                    <Row>
                        <Col>
                            <Table striped responsive>
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {documents.map((doc) => 
                                        <tr>
                                            <td>{doc.description}</td>
                                            <td>
                                                <TableButton color={"light"} tooltip={"Excluir"} 
                                                name={"Excluir"}
                                                icon={"delete"} onClick={() => onDeleteDoc(doc)} />
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </Table>
                        </Col>
                    </Row>
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Cancelar" color="light" onClick={() => {setShowModal(false)}}></CustomButton>
                    
                    {!isLoadingDocuments &&
                        <CustomButton disabled={documents.length <= 0} name="Salvar" 
                            color="success" onClick={onFinishUpload}></CustomButton>
                    }
                    {isLoadingDocuments &&
                        <Button variant="success"
                        type="submit"
                        size="lg"
                        disabled={isLoadingDocuments}>
                        {isLoadingDocuments ? <Loading />
                                    : 
                                    'Salvar'}
                        </Button>
                    }
                </Modal.Footer>
            </Modal>

            <Modal 
                show={showDocumentsModal} 
                onHide={() => {setShowDocumentsModal(false)}} 
                size={"lg"}
                centered>
                <Modal.Header closeButton>
                    <Modal.Title>Documentos de {client.name}</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    {errorMessage &&
                    <Error message={errorMessage} />
                    }
                    <Row>
                        <Col>
                            <Table striped responsive>
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Data de Criação</th>
                                        <th>Opções</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {client.files && client.files.map((doc) => 
                                        <tr>
                                            <td>
                                                <a target='_blank' href={"https://3marias-terraform-dev.s3.amazonaws.com/clients/" + doc.filename}>
                                                <i className="material-icons float-left">insert_drive_file</i>{doc.description}
                                                </a>
                                            </td>
                                            <td>{formatDateTime(doc.created_at)}</td>
                                            <td>
                                                <TableButton color={"light"} tooltip={"Excluir"} 
                                                name={"Excluir"}
                                                icon={"delete"} onClick={() => {setDocument(doc); setShowDocumentsModal(false); setShowDeleteModal(true);}} />
                                            </td>
                                        </tr>
                                    )}
                                    {!client.files || client.files.length === 0 && 
                                        <NoEntity count={3} message={"Nenhum documento foi encontrado."} />    
                                    }
                                </tbody>
                            </Table>
                        </Col>
                    </Row>
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Cancelar" color="light" onClick={() => {setShowDocumentsModal(false)}}></CustomButton>
                </Modal.Footer>
            </Modal>

            <Modal 
                size={"lg"}
                centered 
                show={showDeleteModal} onHide={() => {setShowDeleteModal(false)}}>
                <Modal.Header closeButton>
                    <Modal.Title>Atenção</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    Você deseja realmente excluir o documento <b>{document.description}</b> de <b>{client.name}</b>?
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Cancelar" color="light" onClick={() => {setShowDeleteModal(false)}}></CustomButton>
                    {!isLoadingDocuments &&
                        <CustomButton name="Deletar" 
                            color="danger" onClick={onConfirmDeleteDocument}></CustomButton>
                    }
                    {isLoadingDocuments &&
                        <Button variant="danger"
                        size="lg"
                        disabled={isLoadingDocuments}>
                        {isLoadingDocuments ? <Loading />
                                    : 
                                    'Deletar'}
                        </Button>
                    }
                </Modal.Footer>
            </Modal>

            <Modal 
                size={"lg"}
                centered 
                show={showClientModal} onHide={() => {setShowClientModal(false)}}>
                <Modal.Header closeButton>
                    <Modal.Title>
                        Informações do Cliente - {client.name}</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    <Row>
                        <Col><b>Nome: </b>{client.name}</Col>
                        <Col><b>CPF: </b>{client.cpf}</Col>
                        <Col><b>Email: </b>{client.email}</Col>
                    </Row>
                    <Row>
                        <Col><b>Data de Aniversário: </b>{formatDate(client.birthdate)}</Col>
                        <Col><b>Estado Civil: </b>{client.state}</Col>
                    </Row>
                    <Row>
                        <Col><b>Telefone: </b>{client.phone}</Col>
                        <Col><b>Profissão: </b>{client.ocupation}</Col>
                        <Col><b>RG: </b>{client.rg}</Col>
                    </Row>
                    <Row>
                        <Col><b>Òrgão Expedidor do RG: </b>{client.rg_organ}</Col>
                        <Col><b>Data de Emissão do RG: </b>{formatDate(client.rg_date)}</Col>
                    </Row>
                    <Row>
                        <Col><b>Nacionalidade: </b>{client.nationality}</Col>
                        <Col><b>Cidade: </b>{client.city}</Col>
                        <Col><b>CEP: </b>{client.zipcode}</Col>
                    </Row>
                    <Row>
                        <Col><b>Bairro: </b>{client.neighborhood}</Col>
                        <Col><b>Número: </b>{client.number}</Col>
                    </Row>
                    <Row>
                        <Col><b>Complemento: </b>{client.complement}</Col>
                    </Row>
                    {client.state === "Casado" &&
                    <>
                        <Row>
                            <Col><b>Nome do Cônjugue: </b>{client.name_dependent}</Col>
                            <Col><b>CPF do Cônjugue: </b>{client.cpf_dependent}</Col>
                            <Col><b>Email do Cônjugue: </b>{client.rg_dependent}</Col>
                        </Row>
                        <Row>
                            <Col><b>Profissão do Cônjugue: </b>{client.ocupation_dependent}</Col>
                            <Col><b>Telefone do Cônjugue: </b>{client.phone_dependent}</Col>
                        </Row>
                        <Row>
                            <Col><b>Nacionalidade do Cônjugue: </b>{client.nationality_dependent}</Col>
                            <Col><b>Data de Aniversário do Cônjugue: </b>{client.birthdate_dependent}</Col>
                        </Row>
                    </>
                    }
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Cancelar" color="light" onClick={() => {setShowClientModal(false)}}></CustomButton>
                </Modal.Footer>
            </Modal>

            {show &&
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                {successMessage &&
                    <Success message={successMessage} />
                }

                {errorMessage &&
                    <Error message={errorMessage} />
                }           

                <CustomTable 
                    tableName="Clientes" 
                    tableIcon="people" 
                    fieldNameDeletion="name" 
                    url="/clients" 
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
            }
        </>
    );
}