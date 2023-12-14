import React, { useEffect, useState } from "react";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import "../../../App.css";
import Container from "react-bootstrap/Container";
import Modal from 'react-bootstrap/Modal';
import VHeader from "../../../components/vHeader/vHeader";
import Accordion from 'react-bootstrap/Accordion';
import Table from "react-bootstrap/Table";
import CustomButton from "../../../components/button/Button";
import NoEntity from "../../../components/table/NoEntity";
import { useNavigate, useParams } from "react-router-dom";
import Loading from "../../../components/loading/Loading";
import { performCustomRequest, performRequest } from "../../../services/Api";
import Error from "../../../components/error/Error";

function EnterpriseDetails() {

    const [showDialogModal, setShowDialogModal] = useState(false);
    const [showSuccessModal, setShowSuccessModal] = useState(false);
    const [itemSelected, setItemSelected] = useState({});
    const [httpError, setHttpError] = useState(null);
    const [showDocument, setShowDocument] = useState(false);
    const [document, setDocument] = useState({});
    const [isLoading, setIsLoading] = useState(false);
    const [enterprise, setEnterprise] = useState({accountants: [], partners: [], owners: [], branches: [], files: []});
    const params = useParams();
    const navigate = useNavigate();

    useEffect(() => {
        if (params.id) {
            getEnterprise();
        }
    }, []);

    const getEnterprise = () => {
        setHttpError(null);
        setIsLoading(true);
        performRequest("GET", "/enterprises/1")
        .then(successGet)
        .catch(errorResponse);
    }

    const successGet = (response) => {
        const enterprise = response.data;
        setEnterprise(enterprise);
        setIsLoading(false);
    }

    const errorResponse = (response) => {
        setIsLoading(false);
        if (response.response && response.response.data) {
            setHttpError(response.response.data);
        }
    }

    const onPrepareDelete = (item, url) => {
        item.url = url;
        setItemSelected(item);
        setShowDialogModal(true);
    }

    const onConfirmDelete = () => {
        setHttpError(null);
        setShowDialogModal(false);
        setIsLoading(true);

        performRequest("DELETE", "/v1" + itemSelected.url + "/" + itemSelected.id, null)
        .then(successDelete)
        .catch(errorResponse);
    };

    const successDelete = (response) => {
        setIsLoading(false);
        setShowSuccessModal(true);
        getEnterprise();
    };

    const onUploadDocumentFile = () => {
        const inputFile = document.getElementById("enterpriseDocumentFile");
        if (inputFile) {
            inputFile.onchange = onSelectFile;
            inputFile.click();
        }
    }

    const onSelectFile = (evt) => {
        const inputFile = document.getElementById("enterpriseDocumentFile");
        if (!inputFile) {
            // show error message
            return;
        }
        const files = inputFile.files;
        if (files.length === 0) {
            // show error message
            return;
        }
        setIsLoading(true);
        setHttpError(null);

        const formData = new FormData();
        formData.append("file", inputFile.files[0]);
        formData.append("enterprise_id", params.id);
        performCustomRequest("POST", "/v1/enterpriseFiles", formData)
        .then(successPostFile)
        .catch(errorResponse);
    }

    const successPostFile = (response) => {
        setIsLoading(false);
        setShowSuccessModal(true);
        getEnterprise();
    }

    return (
        <>
        <Modal show={showDialogModal} onHide={() => {setShowDialogModal(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>Atenção</Modal.Title>
            </Modal.Header>
                    
            <Modal.Body>
                Você deseja realmente excluir <b>{itemSelected.name}</b>?
            </Modal.Body>

            <Modal.Footer>
                <CustomButton name="Cancelar" color="light" onClick={() => {setShowDialogModal(false)}}></CustomButton>
                <CustomButton name="Deletar" color="danger" onClick={onConfirmDelete}></CustomButton>
            </Modal.Footer>
        </Modal>

        <Modal show={showSuccessModal} onHide={() => {setShowSuccessModal(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>Atenção</Modal.Title>
                </Modal.Header>
            <Modal.Body>
                Item excluído com sucesso!
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowSuccessModal(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        <Modal show={showDocument} onHide={() => {setShowDocument(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>{document.name}</Modal.Title>
                </Modal.Header>
            <Modal.Body>
                <Row>
                    <Col>
                        <object
                            style={{width: "100%", minHeight: "calc(80vh)"}} 
                            data={document.url} type="application/pdf">
                            <p>Seu navegador não tem um plugin pra PDF</p>
                        </object>
                    </Col>
                </Row>
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowDocument(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        <VHeader />
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            {httpError &&
                <Row>
                    <Col>
                        <Error message={httpError.message} />
                    </Col>
                </Row>
            }
            <Row>
                <Col>
                    <Card>
                        <Card.Body>
                            {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }

                            {!isLoading &&
                            <>
                                <Card.Title>
                                    <Row>
                                        <Col className="col-sm-10">
                                            <i className="material-icons float-left">business_center</i>
                                            {enterprise.name}
                                        </Col>
                                        <Col className="col-sm-2">
                                            <CustomButton
                                                onClick={() => navigate("/admin/enterprises/edit/1")}
                                                style={{width: 40}}
                                                color="success" icon="edit" tooltip="Editar Empresa" />
                                        </Col>
                                    </Row>
                                </Card.Title>
                                <Row>
                                    <Col><b>Nome Fantasia:</b> {enterprise.fantasy_name}</Col>
                                </Row>
                                <Row>
                                    <Col><b>Razão Social:</b> {enterprise.social_reason}</Col>
                                </Row>
                                <Row>
                                    <Col><b>CNPJ:</b> {enterprise.cnpj}</Col>
                                </Row>
                            </>
                            }
                            
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <Row>
                <Col>
                <Accordion>
                    <Accordion.Item eventKey="0">
                        <Accordion.Header>Informações Gerais</Accordion.Header>
                        <Accordion.Body>
                            {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!isLoading &&
                            <>
                            <Row>
                                <Col><b>Creci:</b> {enterprise.creci}</Col>
                            </Row>
                            <Row>
                                <Col><b>Inscrição Estadual:</b> {enterprise.state_registration}</Col>
                                <Col><b>Inscrição Municipal:</b> {enterprise.municipal_registration}</Col>
                            </Row>
                            <Row>
                                <Col><b>Telefone:</b> {enterprise.phone}</Col>
                            </Row>
                            <Row>
                                <Col><b>Endereço:</b> {enterprise.address}</Col>
                                <Col><b>Complemento:</b> {enterprise.complement}</Col>
                            </Row>
                            <Row>
                                <Col><b>Bairro:</b> {enterprise.neighborhood}</Col>
                                <Col><b>CEP:</b> {enterprise.zipcode}</Col>
                            </Row>
                            <Row>
                                <Col><b>Cidade:</b> {enterprise.city_name}</Col>
                                <Col><b>Estado:</b> {enterprise.state_name}</Col>
                            </Row>
                            </>
                            }
                            
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="1">
                        <Accordion.Header>Contador</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!isLoading &&
                            <>
                            <Row>
                                <Col xs="12">
                                    <Row>
                                        <Col xs={11}></Col>
                                        <Col xs={1}>
                                            <CustomButton
                                                tooltip="Adicionar"
                                                icon="add" 
                                                name="Adicionar"
                                                color="success" 
                                                onClick={() => navigate("/admin/enterprises/accountants/add/"+enterprise.id)} />
                                        </Col>
                                    </Row>
                                    <></>
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Telefone</th>
                                                <th>Endereço</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {enterprise.accountants.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>{item.phone}</td>
                                                <td>{item.address.address}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton 
                                                                onClick={() => navigate("/admin/enterprises/accountants/edit/"+item.id)}
                                                                name="btnEdit" 
                                                                tooltip="Editar"
                                                                icon="edit" 
                                                                color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => onPrepareDelete(item, "/accountants")}
                                                                name="btnDelete" 
                                                                tooltip="Deletar"
                                                                icon="delete" 
                                                                color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }

                                            {enterprise.accountants.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                            </Row>
                            </>
                            }
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="2">
                        <Accordion.Header>Sócios</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!isLoading &&
                            <Row>
                                <Col xs="12">
                                    <Row>
                                        <Col xs={11}></Col>
                                        <Col xs={1}>
                                            <CustomButton
                                                tooltip="Adicionar"
                                                icon="add" 
                                                name="Adicionar"
                                                color="success" 
                                                onClick={() => navigate("/admin/enterprises/enterprisePartners/add/"+enterprise.id)} />
                                        </Col>
                                    </Row>
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Estado Civil</th>
                                                <th>Profissão</th>
                                                <th>Endereço</th>
                                                <th>Telefone</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {enterprise.partners.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>{item.email}</td>
                                                <td>{item.state}</td>
                                                <td>{item.ocupation}</td>
                                                <td>{item.address.address}</td>
                                                <td>{item.phone}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton 
                                                                onClick={() => navigate("/admin/enterprises/enterprisePartners/edit/"+item.id)}
                                                                name="btnEdit" 
                                                                tooltip="Editar"
                                                                icon="edit" 
                                                                color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => onPrepareDelete(item, "/enterprisePartners")}
                                                                name="btnDelete" 
                                                                tooltip="Deletar"
                                                                icon="delete" 
                                                                color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }
                                            {enterprise.partners.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                            </Row>
                            }
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="3">
                        <Accordion.Header>Representantes Legais da Empresa</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                        {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!isLoading &&
                            <Row>
                                <Col xs="12">
                                    <Row>
                                        <Col xs={11}></Col>
                                        <Col xs={1}>
                                            <CustomButton
                                                tooltip="Adicionar"
                                                icon="add" 
                                                name="Adicionar"
                                                color="success" 
                                                onClick={() => navigate("/admin/enterprises/enterpriseOwners/add/"+enterprise.id)} />
                                        </Col>
                                    </Row>
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Email</th>
                                                <th>Estado Civil</th>
                                                <th>Profissão</th>
                                                <th>Endereço</th>
                                                <th>Telefone</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {enterprise.owners.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>{item.email}</td>
                                                <td>{item.state}</td>
                                                <td>{item.ocupation}</td>
                                                <td>{item.address.address}</td>
                                                <td>{item.phone}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton 
                                                                onClick={() => navigate("/admin/enterprises/enterpriseOwners/edit/"+item.id)}
                                                                name="btnEdit" 
                                                                tooltip="Editar"
                                                                icon="edit" 
                                                                color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => onPrepareDelete(item, "/enterpriseOwners")}
                                                                name="btnDelete" 
                                                                tooltip="Deletar"
                                                                icon="delete" 
                                                                color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }
                                            {enterprise.owners.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                            </Row>
                            }
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="4">
                        <Accordion.Header>Filiais</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                        {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!isLoading &&
                            <Row>
                                <Col xs="12">
                                    <Row>
                                        <Col xs={11}></Col>
                                        <Col xs={1}>
                                            <CustomButton
                                                tooltip="Adicionar"
                                                icon="add" 
                                                name="Adicionar"
                                                color="success" 
                                                onClick={() => navigate("/admin/enterprises/enterpriseBranches/add/"+enterprise.id)} />
                                        </Col>
                                    </Row>
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>CNPJ</th>
                                                <th>Endereço</th>
                                                <th>Telefone</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {enterprise.branches.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>{item.cnpj}</td>
                                                <td>{item.address.address}</td>
                                                <td>{item.phone}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton 
                                                                onClick={() => navigate("/admin/enterprises/enterpriseBranches/edit/"+item.id)}
                                                                name="btnEdit" 
                                                                tooltip="Editar"
                                                                icon="edit" 
                                                                color="light" />
                                                        </Col>
                                                        
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => onPrepareDelete(item, "/enterpriseBranches")}
                                                                name="btnDelete" 
                                                                tooltip="Deletar"
                                                                icon="delete" 
                                                                color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }
                                            {enterprise.branches.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                            </Row>
                            }
                        </Accordion.Body>
                    </Accordion.Item>
                    <Accordion.Item eventKey="5">
                        <Accordion.Header>Arquivos</Accordion.Header>
                        <Accordion.Body  style={{padding: 0}}>
                            {isLoading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!isLoading &&
                            <Row>
                                <Col xs="12">
                                    <Row>
                                        <Col xs={11}></Col>
                                        <Col xs={1}>
                                            <input type="file" id="enterpriseDocumentFile" style={{display: "none"}} />
                                            <CustomButton
                                                tooltip="Adicionar"
                                                icon="file_upload" 
                                                name="Adicionar"
                                                color="success" 
                                                onClick={() => onUploadDocumentFile()} />
                                        </Col>
                                    </Row>
                                    <Table responsive>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nome</th>
                                                <th>Opções</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {enterprise.files.map((item) => 
                                            <tr key={item.id}>
                                                <td>{item.id}</td>
                                                <td>{item.name}</td>
                                                <td>
                                                    <Row>
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => {setDocument(item); setShowDocument(true);}}
                                                                name="btnView" 
                                                                tooltip="Visualizar"
                                                                icon="remove_red_eye" 
                                                                color="light" />
                                                        </Col>
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => {window.location.href = item.url;}}
                                                                name="btnDownload" 
                                                                tooltip="Download"
                                                                icon="file_download" 
                                                                color="light" />
                                                        </Col>
                                                        <Col>
                                                            <CustomButton
                                                                onClick={() => onPrepareDelete(item, "/enterpriseFiles")}
                                                                name="btnDelete" 
                                                                tooltip="Deletar"
                                                                icon="delete" 
                                                                color="light" />
                                                        </Col>
                                                    </Row>
                                                </td>
                                            </tr>
                                            )
                                            }
                                            {enterprise.files.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                            </Row>
                            }
                        </Accordion.Body>
                    </Accordion.Item>
                </Accordion>
                </Col>
            </Row>
        </Container>
        </>
    )
};

export default EnterpriseDetails;
