import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

export default function UserList() {

    const isAdmin = hasPermission("PROPRIETÁRIO");
    const isDeveloper = hasPermission("DESENVOLVEDOR");

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
        fields: ["#", "Nome", "Email", "Grupo"],
        amountOptions: 1,
        bodyFields: ["id", "name", "email", "group.description"]
    };
    
    return (
        <>
            <VHeader />
            {(isAdmin || isDeveloper) &&
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Usuários"
                    tableNamePlaceholder="Usuário" 
                    tableIcon="group" 
                    fieldNameDeletion="name" 
                    url="/users" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
            }

            {!(isAdmin || isDeveloper) &&
                <Forbidden />
            }
        </>
    );
}