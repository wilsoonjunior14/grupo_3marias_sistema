import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import { useNavigate } from "react-router-dom";

export default function ProposalList() {

    const navigate = useNavigate();
    const fields = [
        {
            id: 'code',
            placeholder: "Código",
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["Status", "#", "Descrição", "Tipo de Empreendimento", "Tipo da Proposta", "Valor Global", "Data da Proposta"],
        amountOptions: 1,
        bodyFields: ["icon", "code", "description", "construction_type", "proposal_type", "global_value", "proposal_date"]
    };

    const customOptions = [
        {
            name: "approve_proposal",
            tooltip: "Aprovar Proposta",
            icon: "thumb_up",
            onClick: (evt) => {console.log(evt)}
        },
        {
            name: "cancel_proposal",
            tooltip: "Cancelar Proposta",
            icon: "thumb_down",
            onClick: (evt) => {console.log(evt)}
        },
        {
            name: "see_proposal_contract",
            tooltip: "Ver Contrato Proposta",
            icon: "description",
            onClick: (evt) => {navigate("/engineering/proposals/download/"+evt.id)}
        }
    ];
    
    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Propostas" 
                    tableIcon="work" 
                    fieldNameDeletion="name" 
                    url="/proposals"
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
        </>
    );
}