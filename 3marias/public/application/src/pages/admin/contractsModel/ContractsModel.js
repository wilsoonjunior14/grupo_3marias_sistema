import Container from "react-bootstrap/Container";
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";

function ContractsModel() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'type',
            placeholder: 'Tipo de Contrato',
            type: 'text',
            maxlength: 100
        }
    ];

    const table = {
        fields: ["#", "Nome", "Tipo de Contrato", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "name", "type", "created_at", "updated_at"]
    };

    return (
        <>
        <VHeader />
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomTable
                    tableName="Modelos de Contratos" 
                    tableIcon="content_paste" 
                    fieldNameDeletion="name" 
                    url="/contractsModels" 
                    tableFields={table}
                    searchFields={fields} />
        </Container>
        </>
    );
}

export default ContractsModel;