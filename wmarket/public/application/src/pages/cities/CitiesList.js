import React from "react";
import Container from 'react-bootstrap/Container';
import Header from '../../components/header/Header';
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function CitiesList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Cidade',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Cidade", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "created_at", "updated_at"]
    };

    return (
        <>
            <Header />
            <br></br>
            <Container fluid>

                <CustomTable 
                    tableName="Cidades" 
                    tableIcon="language" 
                    fieldNameDeletion="name" 
                    url="/cities" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}