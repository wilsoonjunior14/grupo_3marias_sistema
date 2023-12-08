import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function StockList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Locais de Estoque" 
                    tableIcon="group" 
                    fieldNameDeletion="name" 
                    url="/stocks" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}