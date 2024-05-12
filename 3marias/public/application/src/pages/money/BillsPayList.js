import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import { useNavigate } from "react-router-dom";

export default function BillsPayList() {
    const navigate = useNavigate();
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
        fields: ["#", "status", "Descrição", "Valor", "Valor Pago", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["code", "icon", "description", "value", "value_performed", "created_at", "updated_at"]
    };

    const customOptions = [
        {
            name: "see_payment_details",
            icon: "visibility",
            tooltip: "Ver Detalhes da Conta a Pagar",
            onClick: (item) => {navigate("/money/billsPay/details/"+item.id)}
        }
    ];
    
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
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
        </>
    );
}