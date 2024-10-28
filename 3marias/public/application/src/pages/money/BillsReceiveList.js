import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import { useNavigate } from "react-router-dom";
import { hasPermission } from "../../services/Storage";
import Forbidden from "../../components/error/Forbidden";

export default function BillsReceiveList() {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const navigate = useNavigate();
    const fields = [
        {
            id: 'contract.proposal.client.name',
            placeholder: 'Cliente',
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
        fields: ["Status", "Contrato", "Cliente", "Descrição", "Valor"],
        amountOptions: 1,
        bodyFields: ["icon", "contract.code", "contract.proposal.client.name", "description", "value"]
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
            {(isDeveloper || isAdmin) &&
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
            }

            {!(isDeveloper || isAdmin) &&
                <Forbidden />
            }
        </>
    );
}