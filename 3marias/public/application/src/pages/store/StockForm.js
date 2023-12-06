import CustomForm from "../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';

const StockForm = ({}) => {

    const fields = [
        {
            name: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: "city_id",
            placeholder: "Cidade",
            type: "select",
            required: true,
            endpoint: "cities",
            endpoint_field: "name"
        }
    ];

    return (
        <>
        <VHeader />
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/stocks" nameScreen="Local de Estoque" fields={fields} />
        </Container>
        </>
    )
};

export default StockForm;
