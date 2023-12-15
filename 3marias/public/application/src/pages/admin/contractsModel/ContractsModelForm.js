import Container from "react-bootstrap/Container";
import VHeader from "../../../components/vHeader/vHeader";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Tab from 'react-bootstrap/Tab';
import Tabs from 'react-bootstrap/Tabs';
import React, { useEffect, useState } from 'react';
import CustomInput from "../../../components/input/CustomInput";
import CustomButton from "../../../components/button/Button";
import Button from "react-bootstrap/esm/Button";
import Loading from "../../../components/loading/Loading";
import Editor from 'react-simple-wysiwyg'; // https://www.npmjs.com/package/react-simple-wysiwyg
import { performRequest } from "../../../services/Api";
import Form from 'react-bootstrap/Form';
import config from "../../../config.json";
import { useParams } from "react-router-dom";
import Error from "../../../components/error/Error";
import Success from "../../../components/success/Success";
export const logo = config.url + "/img/logo_document.png";

function ContractsModelForm() {

    const [html, setHtml] = useState('<h3>Adicione o Conteúdo do Contrato Aqui</h3>');
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [httpError, setHttpError] = useState(null);
    const [name, setName] = useState('');
    const [type, setType] = useState('');
    const [loading, setLoading] = useState(false);
    const [isLoadingData, setIsLoadingData] = useState(false);
    const params = useParams();

    useEffect(() => {
        if (params.id) {
            getModel(params.id);
        }
    }, []);

    const getModel = (id) => {
        setIsLoadingData(true);

        performRequest("GET", "/v1/contractsModels/"+id, null)
        .then(successGet)
        .catch((err) => {setIsLoadingData(false); errorResponse(err);});
    }

    const successGet = (response) => {
        setIsLoadingData(false);
        const data = response.data;
        setName(data.name);
        setHtml(data.content);
        setType(data.type);
    }
    
    function onChange(e) {
        setHtml(e.target.value);
    }

    function onChangeName(e) {
        setName(e.target.value);
    }

    function onChangeType(e) {
        setType(e.target.value);
    }

    const onSave = (e) => {
        e.preventDefault();
        setLoading(true);

        const payload = {
            name: name,
            content: html,
            type: type
        };

        if (params.id) {
            performRequest("PUT", "/v1/contractsModels/"+params.id, payload)
            .then(successResponse)
            .catch(errorResponse);
            return;
        }

        performRequest("POST", "/v1/contractsModels", payload)
        .then(successResponse)
        .catch(errorResponse);
    }

    const successResponse = (response) => {
        setLoading(false);
        setHttpSuccess({message: "Modelo salvo com sucesso!"});
    }

    const errorResponse = (response) => {
        setLoading(false);
        setIsLoadingData(false);
        if (response.response) {
            setHttpError(response.response.data);
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    }

    return (
            <>
        <VHeader />
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            {!loading && httpError &&
                <Error message={httpError.message} />
            }

            {!loading && httpSuccess &&
                <Success message={httpSuccess.message} />
            }
            <Row>
                <Col>
                    <Card>
                        <Card.Body>
                            <Card.Title>
                                {!params.id &&
                                <>
                                <i className="material-icons float-left">add</i>
                                Adicionar Modelo de Contrato
                                </>
                                }
                                {params.id &&
                                <>
                                <i className="material-icons float-left">edit</i>
                                Editar Modelo de Contrato
                                </>
                                }
                            </Card.Title>
                                {isLoadingData &&
                                    <Row>
                                        <Col xs={5}></Col>
                                        <Col xs={2}>
                                            <Loading />
                                        </Col>
                                        <Col xs={5}></Col>
                                    </Row>
                                }
                                {!isLoadingData &&
                                <>
                                <Row>
                                    <Col>
                                        <small>Campos com * são obrigatórios.</small>
                                    </Col>
                                    <br></br>
                                    <br></br>
                                </Row>
                                <Form onSubmit={onSave}>
                                <Row>
                                    <Col xs={12} lg={6}>
                                        <CustomInput 
                                            key="name" 
                                            type="text" 
                                            placeholder="Nome do Contrato *" 
                                            name="name"
                                            required={true}
                                            onChange={onChangeName}
                                            maxlength="255"
                                            value={name} />
                                    </Col>
                                    <Col xs={12} lg={6}>
                                        <CustomInput 
                                            key="name" 
                                            type="select" 
                                            data={["Serviço", "Corretagem", "Entrega de Chaves", "Venda"]}
                                            placeholder="Tipo *" 
                                            name="type"
                                            required={true}
                                            onChange={onChangeType}
                                            value={type} />
                                    </Col>
                                    <Col xs={12}>
                                        <Tabs
                                        defaultActiveKey="edit"
                                        id="uncontrolled-tab-example"
                                        className="mb-3">
                                            <Tab eventKey="edit" title="Editar">
                                                <Row>
                                                    <Col xs={2}></Col>
                                                    <Col xs={8}>
                                                        <Editor value={html} onChange={onChange} />
                                                    </Col>
                                                    <Col xs={2}></Col>
                                                </Row>
                                            </Tab>
                                            <Tab eventKey="preview" title="Visualizar">
                                                <Row>
                                                    <Col xs={11}></Col>
                                                    <Col>
                                                        <CustomButton
                                                            icon="print"
                                                            variant="success"
                                                            name="print"
                                                            tooltip="Imprimir"
                                                            onClick={() => {window.print();}}
                                                        />
                                                    </Col>

                                                    <Col xs={2}></Col>
                                                    <Col id="printer" style={{border: '1px solid gray'}} xs={8}>
                                                        <Row>
                                                            <Col>
                                                            <img
                                                                alt=""
                                                                height={70}
                                                                src={logo}
                                                                style={{marginTop: "20px"}}
                                                                className="d-inline-block align-top"
                                                            />
                                                            </Col>
                                                        </Row>
                                                        <div className="rsw-ce" dangerouslySetInnerHTML={{ __html: html }} />
                                                    </Col>
                                                    <Col cs={2}></Col>
                                                </Row>
                                            </Tab>
                                        </Tabs>
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
                                </>
                                }     
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
        </>
        );
}

export default ContractsModelForm;