import Container from "react-bootstrap/Container";
import CustomForm from "../../../components/form/Form";
import VHeader from "../../../components/vHeader/vHeader";

const CitiesForm = ({}) => {

    const fields = [
        {
            name: "name",
            placeholder: "Nome",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "state_id",
            placeholder: "Estado",
            type: "select",
            required: true,
            endpoint: "states",
            endpoint_field: "name"
        }
    ]

    return (
        <>
        <VHeader />
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/cities" nameScreen="Cidade" fields={fields} />
        </Container>
        </>
    )
};

export default CitiesForm;
