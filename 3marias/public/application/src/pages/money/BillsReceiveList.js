import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import { useNavigate } from "react-router-dom";

export default function BillsReceiveList() {

    const navigate = useNavigate();
    const fields = [
        {
            id: 'code',
            placeholder: 'Código',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'contract.code',
            placeholder: 'Contrato',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Status", "Cliente", "Descrição", "Valor", "Contrato", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["code", "icon", "contract.proposal.client.name", "description", "value", "contract.code", "created_at", "updated_at"]
    };

    const customOptions = [
        {
            name: "see_payment_details",
            icon: "visibility",
            tooltip: "Ver Detalhes da Conta a Receber",
            onClick: (item) => {navigate("/money/billsReceive/details/"+item.id)}
        }
    ];
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Contas a Receber" 
                    tableIcon="monetization_on" 
                    fieldNameDeletion="name" 
                    disableAdd={true}
                    disableEdit={true}
                    disableDelete={true}
                    url="/billsReceive" 
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
        </>
    );
}