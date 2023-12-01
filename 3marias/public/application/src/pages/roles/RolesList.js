import React from "react";
import Container from 'react-bootstrap/Container';
import Header from '../../components/header/Header';
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function RolesList() {

    const fields = [
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'url',
            placeholder: 'URL',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'type',
            placeholder: 'Tipo de Requisição',
            type: 'text',
            maxlength: 10
        }
    ];

    const table = {
        fields: ["#", "Descrição", "URL", "Tipo de Requisição", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "description", "endpoint", "request_type", "created_at", "updated_at"]
    };

    return (
        <>
            <Header />
            <br></br>
            <Container fluid>

                <CustomTable 
                    tableName="Permissões" 
                    tableIcon="lock" 
                    fieldNameDeletion="description" 
                    url="/roles" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}