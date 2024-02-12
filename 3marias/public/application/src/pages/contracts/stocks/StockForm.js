import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';

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
            name: 'status',
            placeholder: 'Status',
            type: 'select',
            data: ['Ativo', 'Desativado'],
            required: true
        }
    ];

    return (
        <>
        <VHeader />
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/stocks" nameScreen="Centro de Custo" fields={fields} />
        </Container>
        </>
    )
};

export default StockForm;
