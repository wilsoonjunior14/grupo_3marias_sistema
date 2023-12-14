import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function CategoryProductList() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome da Categoria',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome", "Categoria Associada", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "category_product.name", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Categoria de Produtos" 
                    tableIcon="assignment" 
                    fieldNameDeletion="name" 
                    url="/categoryProducts" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}