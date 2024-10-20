import React from "react";
import Container from 'react-bootstrap/Container';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";

export default function StatesList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Estado',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Estado"],
        amountOptions: 1,
        bodyFields: ["id", "name"]
    };

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <CustomTable 
                    tableName="Estados" 
                    tableIcon="language" 
                    fieldNameDeletion="name" 
                    url="/states" 
                    tableFields={table}
                    searchFields={fields} />
            </Container>
        </>
    );
}