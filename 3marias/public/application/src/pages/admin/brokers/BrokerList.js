import React from "react";
import Container from 'react-bootstrap/Container';
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

export default function BrokerList() {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const fields = [
        {
            id: 'name',
            placeholder: 'Cidade',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Corretor", "Creci"],
        amountOptions: 1,
        bodyFields: ["id", "name", "creci"]
    };

    return (
        <>
            <VHeader />
            {(isDeveloper || isAdmin) &&
            <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Corretores" 
                    tableIcon="person" 
                    fieldNameDeletion="name" 
                    url="/brokers" 
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