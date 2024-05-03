import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import config from "../../../config.json";
import { performRequest } from "../../../services/Api";
import Success from "../../../components/success/Success";
import Error from "../../../components/error/Error";
import Button from 'react-bootstrap/esm/Button';
import Loading from '../../../components/loading/Loading';
import CustomButton from '../../../components/button/Button';
import Modal from 'react-bootstrap/Modal';

export default function ProposalList() {

    const [isRejectingProposal, setIsRejectingProposal] = useState(false);
    const [showRejectingProposal, setRejectingProposal] = useState(false);
    const [showApprovalModal, setShowApprovalModal] = useState(false);
    const [isApprovingProposal, setIsApprovingProposal] = useState(false);

    const [proposal, setProposal] = useState({});
    const [errorMessage, setErrorMessage] = useState(null);
    const [successMessage, setSuccessMessage] = useState(null);

    const fields = [
        {
            id: 'code',
            placeholder: "Código",
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["Status", "Código", "Cliente", "Descrição", "Tipo", "Tipo da Proposta", "Valor", "Data da Proposta"],
        amountOptions: 1,
        bodyFields: ["icon", "code", "client.name", "description", "construction_type", "proposal_type", "global_value", "proposal_date"]
    };

    // TODO: only admin or developer can approve or cancel a proposal
    const customOptions = [
        {
            name: "approve_proposal",
            tooltip: "Aprovar Proposta",
            icon: "thumb_up",
            onClick: (evt) => {setProposal(evt); setShowApprovalModal(true);}
        },
        {
            name: "cancel_proposal",
            tooltip: "Cancelar Proposta",
            icon: "thumb_down",
            onClick: (evt) => {setProposal(evt); setRejectingProposal(true);}
        },
        {
            name: "see_proposal_contract",
            tooltip: "Ver Proposta",
            icon: "file_download",
            onClick: (evt) => {window.open( config.url + "/proposal/"+evt.id)}
        }
    ];

    const onRejectProposal = () => {
        setIsRejectingProposal(true);
        
        performRequest("POST", "/v1/proposals/reject/"+proposal.id)
        .then(onSuccessResponse)
        .catch((err) => {});
    }

    const onApproveProposal = () => {
        setIsApprovingProposal(true);
        
        performRequest("POST", "/v1/proposals/approve/"+proposal.id)
        .then(onSuccessResponse)
        .catch((err) => {});
    }

    const onSuccessResponse = (res) => {
        setIsRejectingProposal(false);
        setRejectingProposal(false);
        setIsApprovingProposal(false);
        setShowApprovalModal(false);
        setSuccessMessage("Operação realizada com sucesso!");
    }
    
    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <Modal 
                    size={"lg"}
                    centered 
                    show={showRejectingProposal} onHide={() => {setRejectingProposal(false)}}>
                    <Modal.Header closeButton>
                        <Modal.Title>Atenção</Modal.Title>
                    </Modal.Header>
                            
                    <Modal.Body>
                        Você deseja realmente rejeitar a proposta <b>{proposal.code}</b>?
                    </Modal.Body>

                    <Modal.Footer>
                        <CustomButton name="Cancelar" color="light" onClick={() => {setRejectingProposal(false)}}></CustomButton>
                        {!isRejectingProposal &&
                            <CustomButton name="Rejeitar" 
                                color="danger" onClick={onRejectProposal}></CustomButton>
                        }
                        {isRejectingProposal &&
                            <Button variant="danger"
                            size="lg"
                            disabled={isRejectingProposal}>
                            {isRejectingProposal ? <Loading />
                                        : 
                                        'Rejeitar'}
                            </Button>
                        }
                    </Modal.Footer>
                </Modal>

                <Modal 
                    size={"lg"}
                    centered 
                    show={showApprovalModal} onHide={() => {setShowApprovalModal(false)}}>
                    <Modal.Header closeButton>
                        <Modal.Title>Atenção</Modal.Title>
                    </Modal.Header>
                            
                    <Modal.Body>
                        Você deseja realmente aprovar a proposta <b>{proposal.code}</b>?
                    </Modal.Body>

                    <Modal.Footer>
                        <CustomButton name="Cancelar" color="light" onClick={() => {setShowApprovalModal(false)}}></CustomButton>
                        {!isApprovingProposal &&
                            <CustomButton name="Aprovar" 
                                color="succcess" onClick={onApproveProposal}></CustomButton>
                        }
                        {isApprovingProposal &&
                            <Button variant="success"
                            size="lg"
                            disabled={isApprovingProposal}>
                            {isApprovingProposal ? <Loading />
                                        : 
                                        'Aprovar'}
                            </Button>
                        }
                    </Modal.Footer>
                </Modal>

                {successMessage &&
                    <Success message={successMessage} />
                }

                {errorMessage &&
                    <Error message={errorMessage} />
                } 

                {!isRejectingProposal && !isApprovingProposal &&
                <CustomTable 
                    tableName="Propostas" 
                    tableIcon="work" 
                    fieldNameDeletion="name" 
                    url="/proposals"
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />
                }

            </Container>
        </>
    );
}