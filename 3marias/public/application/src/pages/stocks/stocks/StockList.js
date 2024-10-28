import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import CustomTable from "../../../components/table/Table";
import { useNavigate } from "react-router-dom";
import config from "../../../config.json";
import Forbidden from "../../../components/error/Forbidden";
import { hasPermission } from "../../../services/Storage";

export default function StockList() {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");
    
    const navigate = useNavigate();
    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["#", "Nome", "Contrato", "Cliente", "Tipo de Obra", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "contract.code", "contract.proposal.client.name", "contract.building_type", "created_at", "updated_at"]
    };

    const customOptions = [
        {
            name: "file_download",
            tooltip: "Download do Alvará",
            icon: "file_download",
            onClick: (evt) => {if (evt.id ===1) {return;} window.open(config.url + "/alvara/" + evt.contract.id)}
        },
        {
            name: "see_items_stock",
            icon: "visibility",
            tooltip: "Ver Itens do Centro de Custo",
            onClick: (item) => {navigate("/stocks/items/"+item.id)}
        }
    ];
    
    return (
        <>
            <VHeader />
            {(isDeveloper || isAdmin) &&
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Centros de Custo" 
                    tableIcon="attach_money" 
                    fieldNameDeletion="name" 
                    disableAdd={true}
                    disableDelete={true}
                    customOptions={customOptions}
                    url="/stocks" 
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