import React from "react";
import Container from 'react-bootstrap/Container';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";

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
        fields: ["#", "Cidade"],
        amountOptions: 1,
        bodyFields: ["id", "name"]
    };

    return (
        <>
            <VHeader />
            <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

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