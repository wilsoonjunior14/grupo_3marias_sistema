import Container from "react-bootstrap/Container";
import VHeader from "../../../components/vHeader/vHeader";
import CustomForm from "../../../components/form/Form";

function DocumentTypeForm() {

    const fields = [
        {
            name: "name",
            placeholder: "Nome do Documento *",
            type: "text",
            required: true,
            maxlength: 255
        },
        {
            name: "description",
            placeholder: "Descrição do Documento *",
            type: "text",
            required: true,
            maxlength: 255
        }
    ];

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <CustomForm id='app-container' endpoint="/v1/documentTypes" nameScreen="Tipo de Documento" fields={fields} />
            </Container>
        </>
    );
}

export default DocumentTypeForm;