import React from "react";
import Container from 'react-bootstrap/Container';
import Header from '../../components/header/Header';
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function CategoriesList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Imagem", "Nome", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "image", "name", "created_at", "updated_at"]
    };

    return (
        <>
            <Header />
            <br></br>
            <Container fluid>

                <CustomTable 
                    tableName="Categorias" 
                    tableIcon="assignment" 
                    fieldNameDeletion="name" 
                    url="/categories" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}