import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import { useEffect, useState } from 'react';
import { useParams } from 'react-router-dom';
import { performRequest } from '../../../services/Api';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import CustomInput from '../../../components/input/CustomInput';
import CustomButton from '../../../components/button/Button';
import BackButton from '../../../components/button/BackButton';
import Loading from '../../../components/loading/Loading';
import config from "../../../config.json";
export const logo = config.url + "/img/logo_document.png";

const ProposalDownload = ({}) => {

    const params = useParams();
    const [loading, setLoading] = useState(false);
    const [loadingContracts, setLoadingContracts] = useState(false);
    const [contractLabels, setContractLabels] = useState([]);

    const [proposal, setProposal] = useState({});
    const [contractModels, setContractModels] = useState([]);
    const [contract, setContract] = useState(null);

    useEffect(() => {
        getProposal(params.id);
        getContractModels();
    }, []);

    const getContractModels = () => {
        setLoadingContracts(true);

        performRequest("GET", "/v1/contractsModels")
        .then(onSuccessGetContracts)
        .catch(onErrorResponse);
    }

    const onSuccessGetContracts = (res) => {
        setLoadingContracts(false);
        setContractModels(res.data);

        var labels = [];
        res.data.forEach((m) => {
            labels.push(m.name);
        });
        setContractLabels(labels);
    }

    const getProposal = (id) => {
        setLoading(true);

        performRequest("GET", "/v1/proposals/"+id)
        .then(onSuccessGetProposalResponse)
        .catch(onErrorResponse);
    }

    const onSuccessGetProposalResponse = (res) => {
        setLoading(false);
        setProposal(res.data);
    }

    const onErrorResponse = (res) => {
    }

    const onChangeContract = async (evt) => {
        contractModels.forEach((model) => {
            if (model.name === evt.target.value) {
                processKeywords(model);
            }
        });
    }

    const processKeywords = async (model) => {
        var text = model.content;

        text = text.replace(/\[name\]/g, proposal.client.name);
        text = text.replace(/\[nationality\]/g, proposal.client.nationality);
        text = text.replace(/\[cpf\]/g, proposal.client.cpf);
        text = text.replace(/\[rg\]/g, proposal.client.rg);
        text = text.replace(/\[state\]/g, proposal.client.state);
        text = text.replace(/\[ocupation\]/g, proposal.client.ocupation);
        text = text.replace(/\[rg_organ\]/g, proposal.client.rg_organ);
        text = text.replace(/\[address\]/g, proposal.client.address);
        text = text.replace(/\[number\]/g, proposal.client.number);
        text = text.replace(/\[neighborhood\]/g, proposal.client.neighborhood);
        text = text.replace(/\[description\]/g, proposal.description);
        text = text.replace(/\[global_value\]/g, proposal.global_value);
        
        model.content = text;
        setContract(model);
    }

    return (
        <>
        <VHeader />
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Row>
                <Col xs={2}>
                    <BackButton />
                </Col>
            </Row>
            <Row>
                <Col>
                    <Card className='printer-hide'>
                        <Card.Body>
                            <Card.Title>
                                <i className="material-icons float-left">business_center</i>
                                Proposta
                            </Card.Title>
                            {loading &&
                            <Row style={{textAlign: "center"}}>
                                <Col></Col>
                                <Col>
                                    <Loading />
                                </Col>
                                <Col></Col>
                            </Row>
                            }
                            {!loading &&
                            <Row>
                                <Col>
                                    <b>Código: </b>{proposal.code} <br></br>
                                    <b>Tipo de Construção: </b>{proposal.construction_type} <br></br>
                                    <b>Tipo de Proposta: </b>{proposal.proposal_type} <br></br>
                                    <b>Data: </b>{proposal.proposal_date} <br></br>
                                </Col>
                            </Row>
                            }
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
            <br></br>
            <Row>
                <Col>
                    <Card>
                        <Card.Body>
                            <Card.Title>
                                <i className="material-icons float-left">description</i>
                                Contrato
                            </Card.Title>
                            {loadingContracts &&
                            <Row style={{textAlign: "center"}}>
                                <Col></Col>
                                <Col>
                                    <Loading />
                                </Col>
                                <Col></Col>
                            </Row>
                            }
                            {!loadingContracts &&
                            <>
                            <Row>
                                <Col lg={4}>
                                    <CustomInput key="contract" type="select"
                                        data={contractLabels}
                                        placeholder="Cidade *" name="contract"
                                        onChange={async (evt) => {var wait = await onChangeContract(evt)}} />
                                </Col>
                                <Col lg={4}>
                                    <CustomButton
                                        icon="print"
                                        variant="success"
                                        name="print"
                                        tooltip="Imprimir"
                                        onClick={() => {window.print();}}
                                    />
                                </Col>
                            </Row>
                            {contract &&
                            <Row>
                                <Col></Col>
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
                                    <div className="rsw-ce" dangerouslySetInnerHTML={{ __html: contract.content }} />
                                </Col>
                                <Col></Col>
                            </Row>
                            }
                            </>
                            }
                        </Card.Body>
                    </Card>
                </Col>
            </Row>
        </Container>
        </>
    )
};

export default ProposalDownload;
