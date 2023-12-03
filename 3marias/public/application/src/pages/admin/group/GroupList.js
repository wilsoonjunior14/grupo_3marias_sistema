import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from '../../../components/vHeader/vHeader';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function GroupList() {

    const fields = [
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Descrição", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "description", "created_at", "updated_at"]
    };

    const customOptions = [
        {
            name: "btnGroupRoles",
            icon: "lock",
            redirectTo: "/groups/roles",
            tooltip: "Editar Permissões"
        }
    ];

    return (
        <>
            <VHeader />
            <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Grupos de Usuários" 
                    tableIcon="group" 
                    fieldNameDeletion="description" 
                    url="/groups" 
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
        </>
    );
}