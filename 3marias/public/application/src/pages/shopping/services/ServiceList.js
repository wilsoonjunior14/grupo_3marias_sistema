import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function ServiceList() {

    const fields = [
        {
            id: 'service',
            placeholder: 'Serviço',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Serviço", "Categoria do Serviço", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "service", "category_service.name", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Serviços" 
                    tableIcon="assignment" 
                    fieldNameDeletion="service" 
                    url="/services" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}