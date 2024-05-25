import React from "react";
import Container from 'react-bootstrap/Container';
import Header from '../../components/header/Header';
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function CountriesList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'País',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "País", "Sigla"],
        amountOptions: 1,
        bodyFields: ["id", "name", "acronym"]
    };

    return (
        <>
            <Header />
            <br></br>
            <Container fluid>

                <CustomTable 
                    tableName="Países" 
                    tableIcon="language" 
                    fieldNameDeletion="name" 
                    url="/countries" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}