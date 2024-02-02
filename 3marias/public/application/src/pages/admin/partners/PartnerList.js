import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function PartnerList() {

    const fields = [
        {
            id: 'fantasy_name',
            placeholder: 'Nome Fantasia',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'cnpj',
            placeholder: 'CNPJ',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome Fantasia", "CNPJ", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "fantasy_name", "cnpj", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Parceiros/Fornecedores" 
                    tableIcon="group" 
                    fieldNameDeletion="fantasy_name" 
                    url="/partners" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}