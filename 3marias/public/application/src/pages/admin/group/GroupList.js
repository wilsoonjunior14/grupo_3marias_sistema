import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from '../../../components/vHeader/vHeader';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import { useNavigate } from "react-router-dom";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

export default function GroupList() {

    const isDeveloper = hasPermission("DESENVOLVEDOR");

    const navigate = useNavigate();
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
            tooltip: "Editar Permissões",
            onClick: (item) => {navigate("/admin/groups/roles/"+item.id)}
        }
    ];

    return (
        <>
            <VHeader />
            {(isDeveloper) &&
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Grupos de Usuários" 
                    tableIcon="group" 
                    fieldNameDeletion="description" 
                    url="/groups" 
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
            }

            {!(isDeveloper) &&
                <Forbidden />
            }
        </>
    );
}