import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

export default function PartnerList() {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const fields = [
        {
            id: 'fantasy_name',
            placeholder: 'Nome Fantasia',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'cnpj',
            placeholder: 'CNPJ',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome Fantasia", "CNPJ"],
        amountOptions: 1,
        bodyFields: ["id", "fantasy_name", "cnpj"]
    };
    
    return (
        <>
            <VHeader />
            {(isDeveloper || isAdmin) &&
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Parceiros/Fornecedores" 
                    tableIcon="group" 
                    fieldNameDeletion="fantasy_name" 
                    url="/partners" 
                    tableFields={table}
                    searchFields={fields} />
            </Container>
            }

            {!(isDeveloper || isAdmin) &&
                <Forbidden />
            }
        </>
    );
}