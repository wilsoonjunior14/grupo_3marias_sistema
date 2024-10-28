import React from "react";
import Container from 'react-bootstrap/Container';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

export default function CitiesList() {
    const isDeveloper = hasPermission("DESENVOLVEDOR");

    const fields = [
        {
            id: 'name',
            placeholder: 'Cidade',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Cidade"],
        amountOptions: 1,
        bodyFields: ["id", "name"]
    };

    return (
        <>
            <VHeader />
            {(isDeveloper) &&
            <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Cidades" 
                    tableIcon="language" 
                    fieldNameDeletion="name" 
                    url="/cities" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
            }

            {!(isDeveloper) && 
                <Forbidden />
            }
        </>
    );
}