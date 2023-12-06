import React, {useState, useEffect} from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";

export default function ShoppingList() {

    const fields = [
        {
            id: 'nr_ticket',
            placeholder: 'Nr. Recibo',
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
            <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Compras" 
                    tableIcon="shopping_cart" 
                    fieldNameDeletion="name" 
                    url="/users"
                    tableFields={table}
                    searchFields={fields}
                    disableAdd={true}
                    disableEdit={true} />

            </Container>
        </>
    );
}