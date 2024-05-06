import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";

const RolesForm = ({}) => {

    const fields = [
        {
            name: "description",
            placeholder: "Descrição",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "endpoint",
            placeholder: "URL",
            maxlength: 100,
            type: "text",
            required: true
        },
        {
            name: "request_type",
            placeholder: "Tipo de Requisição",
            type: "select",
            required: true,
            data: [
                "get", "post", "put", "patch", "delete"
            ]
        }
    ]

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <CustomForm endpoint="/v1/roles" nameScreen="Permissão" fields={fields} />
            </Container>
        </>
    )
};

export default RolesForm;
