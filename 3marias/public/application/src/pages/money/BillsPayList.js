import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function BillsPayList() {

    const fields = [
        {
            id: 'id',
            placeholder: 'Código',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Descrição", "Valor", "Valor Pago", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["code", "description", "value", "value_performed", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Contas a Pagar" 
                    tableIcon="monetization_on" 
                    fieldNameDeletion="description" 
                    disableAdd={true}
                    disableDelete={true}
                    disableEdit={true}
                    url="/billsPay" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}