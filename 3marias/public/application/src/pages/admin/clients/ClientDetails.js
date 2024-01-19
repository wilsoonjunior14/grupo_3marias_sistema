import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import { useParams } from 'react-router-dom';
import { useEffect, useState } from 'react';
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from 'react-bootstrap/Card';
import Error from '../../../components/error/Error';
import { performRequest } from '../../../services/Api';
import Loading from '../../../components/loading/Loading';
import config from "../../../config.json";
import CustomButton from '../../../components/button/Button';
export const logo = config.url + "/img/logo_document.png";

export default function ClientDetails() {

    const [loading, setLoading] = useState(false);
    const [client, setClient] = useState({});
    const [errorMessage, setErrorMessage] = useState(null);
    const [successMessage, setSuccessMessage] = useState(null);
    const params = useParams();

    useEffect(() => {
        if (params.id) {
            getClient(params.id);
        }
    }, []);

    const getClient = (id) => {
        setLoading(true);

        performRequest("GET", "/v1/clients/"+id)
        .then(onSuccessResponse)
        .catch(onErrorResponse);
    }

    const onSuccessResponse = (res) => {
        setClient(res.data);
        setLoading(false);
        console.log(res);
    }

    const onErrorResponse = (err) => {
        setLoading(false);
        console.err(err);
    }

    return (
        <>
        <VHeader />

        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            {errorMessage &&
            <Error message={errorMessage} />
            }

            <Row id="printer-row">
                <Col>
                    <Card style={{minHeight: 150}}>
                        <Card.Body>
                            <img
                                alt=""
                                height={70}
                                src={logo}
                                style={{marginTop: 0}}
                                className="d-inline-block align-top"
                            />
                            <Card.Title style={{position: "absolute", right: 12, top: 50}}>
                                Ficha de Cadastro do Cliente
                            </Card.Title>
                            {loading && 
                                <>
                                <Col></Col>
                                <Col style={{textAlign: 'center'}}>
                                    <Loading />
                                </Col>
                                <Col></Col>
                                </>
                            }
                            {!loading &&
                            <>
                            <br></br>
                            <br></br>
                            <br></br>
                            <Row>
                                <Col>
                                    <b>Dados Pessoais do Proponente</b>
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}> 
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Nome Completo</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.name}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>CPF</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.cpf}</span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}> 
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Data de Nascimento</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.birthdate}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>N• Documento de Identidade</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.rg}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Tipo de Documento</b> <br></br>
                                    <span style={{fontSize: 12}}>RG</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Órgão de Emissão/UF</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.rg_organ}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Data de Emissão</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.rg_date}</span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}> 
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Naturalidade</b> <br></br>
                                    <span style={{fontSize: 12}}></span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Nacionalidade</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.nationality}</span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Profissão</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.ocupation}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Atendimento</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    | | Presencial | | Whatsapp | | Instagram
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Estado Civil</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.state}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Indicação</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    | | Não | | Sim  Quem? <u>Chiquinho</u>
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Endereço Residencial</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.address}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Complemento</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.complement}
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Bairro</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.neighborhood}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>CEP</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.zipcode }
                                    </span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Cidade</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.city }
                                    </span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>UF</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>DDD</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.neighborhood}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Telefone</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.zipcode }
                                    </span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Email</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.email }
                                    </span> 
                                </Col>
                                <Col></Col>
                                <Col></Col>
                                <Col></Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Renda Bruta</b> <br></br>
                                    <span style={{fontSize: 12}}>R$</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Funcionário Público</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    | | Sim | | Não
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Marque as opções que se aplicam ao seu caso:</b> <br></br>
                                    <b style={{fontSize: 12}}>Possui 3 anos de trabalho sob regime do FGTS, somando-se todos os períodos trabalhados?</b> <br></br>
                                    <span style={{fontSize: 12}}>| | Sim | | Não</span> <br></br>
                                    <b style={{fontSize: 12}}>Mais de um comprador ou dependente?</b> <br></br>
                                    <span style={{fontSize: 12}}>| | Sim | | Não</span> 
                                </Col>
                            </Row>

                            <br></br>
                            <Row>
                                <Col>
                                    <b>Dados Pessoais do Cônjugue do Proponente</b>
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}> 
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Nome Completo</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.name}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>CPF</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.cpf}</span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}> 
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Data de Nascimento</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.birthdate}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>N• Documento de Identidade</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.rg}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Tipo de Documento</b> <br></br>
                                    <span style={{fontSize: 12}}>RG</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Órgão de Emissão/UF</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.rg_organ}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Data de Emissão</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.rg_date}</span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}> 
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Naturalidade</b> <br></br>
                                    <span style={{fontSize: 12}}></span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Nacionalidade</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.nationality}</span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Profissão</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.ocupation}</span> 
                                </Col>
                                <Col></Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Atendimento</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    | | Presencial | | Whatsapp | | Instagram
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>DDD</b> <br></br>
                                    <span style={{fontSize: 12}}>{client.neighborhood}</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Telefone</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.zipcode }
                                    </span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Email</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    {client.email }
                                    </span> 
                                </Col>
                                <Col></Col>
                                <Col></Col>
                                <Col></Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Renda Bruta</b> <br></br>
                                    <span style={{fontSize: 12}}>R$</span> 
                                </Col>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Funcionário Público</b> <br></br>
                                    <span style={{fontSize: 12}}>
                                    | | Sim | | Não
                                    </span> 
                                </Col>
                            </Row>
                            <Row style={{borderBottom: "1px solid lightgray"}}>
                                <Col style={{border: "8px solid", borderColor: "gold", borderTop: 0, borderBottom: 0, borderRight: 0}}>
                                    <b style={{fontSize: 12}}>Marque as opções que se aplicam ao seu caso:</b> <br></br>
                                    <b style={{fontSize: 12}}>Possui 3 anos de trabalho sob regime do FGTS, somando-se todos os períodos trabalhados?</b> <br></br>
                                    <span style={{fontSize: 12}}>| | Sim | | Não</span> <br></br>
                                    <b style={{fontSize: 12}}>Mais de um comprador ou dependente?</b> <br></br>
                                    <span style={{fontSize: 12}}>| | Sim | | Não</span> 
                                </Col>
                            </Row>
                            
                            <br></br>
                            <Row>
                                <Col xs={2}>
                                    <CustomButton
                                        icon="print"
                                        variant="success"
                                        name="Imprimir"
                                        onClick={() => {window.print();}}
                                    />
                                </Col>
                            </Row>

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