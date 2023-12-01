import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import Header from '../../components/header/Header';
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function EnterpriseList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'email',
            placeholder: 'Email',
            type: 'email',
            maxlength: 100
        }
    ];

    const table = {
        fields: ["#", "Nome", "Email", "Telefone", "Categoria", "Status", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "email", "phone", "category.name", "status", "created_at", "updated_at"]
    };
    
    return (
        <>
            <Header />
            <br></br>
            <Container fluid>

                <CustomTable 
                    tableName="Empresas" 
                    tableIcon="business" 
                    fieldNameDeletion="name" 
                    url="/enterprises" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}