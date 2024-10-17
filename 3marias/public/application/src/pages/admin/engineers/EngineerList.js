import React from "react";
import Container from 'react-bootstrap/Container';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";

export default function EngineerList() {

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
            id: 'crea',
            placeholder: 'CREA',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome", "Email", "CREA"],
        amountOptions: 1,
        bodyFields: ["id", "name", "email", "crea"]
    };

    return (
        <>
            <VHeader />
            <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Engenheiros" 
                    tableIcon="person_outline" 
                    fieldNameDeletion="name" 
                    url="/engineers" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}