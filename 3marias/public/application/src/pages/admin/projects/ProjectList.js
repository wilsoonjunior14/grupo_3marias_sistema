import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function ProjectList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome", "Descrição"],
        amountOptions: 1,
        bodyFields: ["id", "name", "description"]
    };
    
    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Projetos" 
                    tableIcon="business_center" 
                    fieldNameDeletion="name" 
                    url="/projects" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}