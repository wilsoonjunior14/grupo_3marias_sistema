import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function UserList() {

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
        },
        {
            id: 'phoneNumber',
            placeholder: 'Telefone',
            type: 'text',
            maxlength: 20
        }
    ];

    const table = {
        fields: ["#", "Nome", "Email", "Data de Nascimento", "Telefone", "Grupo", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "email", "birthdate", "phoneNumber", "group.description", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Usuários" 
                    tableIcon="group" 
                    fieldNameDeletion="name" 
                    url="/users" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}