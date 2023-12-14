import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function ProductList() {

    const fields = [
        {
            id: 'product',
            placeholder: 'Nome do Produto',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome", "Tipo do Produto", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "product", "category_product.name", "created_at", "updated_at"]
    };
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Produtos" 
                    tableIcon="assignment" 
                    fieldNameDeletion="product" 
                    url="/products" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}