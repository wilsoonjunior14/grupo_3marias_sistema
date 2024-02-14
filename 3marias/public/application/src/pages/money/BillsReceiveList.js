import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function BillsReceiveList() {

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
        fields: ["#", "Status", "Descrição", "Valor", "Valor Pago", "Data Pgt.", "Contrato", "Cliente", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["code", "icon", "description", "value", "value_performed", "desired_date", "contract.code", "contract.proposal.client.name", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Contas a Receber" 
                    tableIcon="monetization_on" 
                    fieldNameDeletion="name" 
                    disableAdd={true}
                    disableDelete={true}
                    url="/billsReceive" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}