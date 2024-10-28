import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import config from "../../config.json";
import { hasPermission } from "../../services/Storage";
import Forbidden from "../../components/error/Forbidden";

export default function ContractList() {
    const isAdmin = hasPermission("PROPRIETÁRIO");
    const isDeveloper = hasPermission("DESENVOLVEDOR");

    const fields = [
        {
            id: 'code',
            placeholder: "Código",
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["Código", "Cliente", "Valor", "Progresso"],
        amountOptions: 1,
        bodyFields: ["code", "client.name", "value", "progress"]
    };

    const customOptions = [
        {
            name: "file_download",
            tooltip: "Download do Contrato",
            icon: "file_download",
            onClick: (evt) => {window.open(config.url + "/contract/" + evt.id)}
        },
        // {
        //     name: "attach_money",
        //     tooltip: "Pagamentos do Contrato",
        //     icon: "attach_money",
        //     onClick: (evt) => {}
        // }
    ];

    return (
        <>
            <VHeader />
            {(isDeveloper || isAdmin) &&
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <CustomTable 
                    tableName="Contratos" 
                    tableIcon="assignment" 
                    fieldNameDeletion="name" 
                    url="/contracts"
                    customOptions={customOptions}
                    tableFields={table}
                    searchFields={fields} />
            </Container>
            }

            {!(isDeveloper || isAdmin) &&
                <Forbidden />
            }
        </>
    );
}