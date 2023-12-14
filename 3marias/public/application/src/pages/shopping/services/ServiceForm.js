import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';

const ServiceForm = ({}) => {

    const fields = [
        {
            name: 'service',
            placeholder: 'Serviço *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: "category_service_name",
            placeholder: "Categoria do Serviço *",
            type: "select2",
            required: false,
            endpoint: "categoryServices",
            endpoint_field: "name"
        }
    ];

    return (
        <>
        <VHeader />
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/services" nameScreen="Serviço" fields={fields} />
        </Container>
        </>
    )
};

export default ServiceForm;
