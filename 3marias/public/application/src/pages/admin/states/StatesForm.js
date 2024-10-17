import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";

const StatesForm = ({}) => {

    const fields = [
        {
            name: "name",
            placeholder: "Nome",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "acronym",
            placeholder: "Sigla",
            maxlength: 2,
            type: "text",
            required: true
        },
        {
            name: "country_id",
            placeholder: "País",
            type: "select",
            required: true,
            endpoint: "countries",
            endpoint_field: "name"
        }
    ]

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <CustomForm endpoint="/v1/states" nameScreen="Estado" fields={fields} />
            </Container>
        </>
    )
};

export default StatesForm;
