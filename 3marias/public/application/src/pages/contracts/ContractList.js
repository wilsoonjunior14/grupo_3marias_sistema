import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import config from "../../config.json";

export default function ContractList() {

    const fields = [
        {
            id: 'code',
            placeholder: "Código",
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["Código", "Tipo de Obra", "Descrição", "Valor"],
        amountOptions: 1,
        bodyFields: ["code", "building_type", "description", "value"]
    };

    const customOptions = [
        {
            name: "file_download",
            tooltip: "Download do Contrato",
            icon: "file_download",
            onClick: (evt) => {window.open(config.url + "/contract/" + evt.id)}
        }
    ];

    return (
        <>
            <VHeader />
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
        </>
    );
}