import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";

export default function PurchaseOrdersList() {

    const fields = [
        {
            id: 'code',
            placeholder: 'Código',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nr. Recibo", "description"],
        amountOptions: 1,
        bodyFields: ["id", "nr_ticket", "description"]
    };
    
    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Compras" 
                    tableIcon="shopping_cart" 
                    fieldNameDeletion="name" 
                    url="/users"
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}