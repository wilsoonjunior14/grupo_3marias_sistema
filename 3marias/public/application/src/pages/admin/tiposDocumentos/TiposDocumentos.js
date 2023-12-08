import Container from "react-bootstrap/Container";
import CustomTable from "../../../components/table/Table";
import VHeader from "../../../components/vHeader/vHeader";

function TiposDocumentos() {

    const fields = [
        {
            id: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255
        },
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 100
        }
    ];

    const table = {
        fields: ["#", "Nome"],
        amountOptions: 1,
        bodyFields: ["id", "name"]
    };

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

                <CustomTable 
                    tableName="Tipos de Documentos" 
                    tableIcon="content_paste" 
                    fieldNameDeletion="name" 
                    url="/users" 
                    tableFields={table}
                    searchFields={fields} />

            </Container>
        </>
    );
}

export default TiposDocumentos;