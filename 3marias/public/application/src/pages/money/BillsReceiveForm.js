import CustomForm from "../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';

const BillsReceiveForm = ({}) => {

    const fields = [
        {
            name: 'code',
            placeholder: 'Código *',
            type: 'text',
            maxlength: 255,
            required: true,
            disabled: true
        },
        {
            name: 'value',
            placeholder: 'Valor *',
            type: 'money',
            maxlength: 255,
            required: true,
            disabled: true
        },
        {
            name: 'value_performed',
            placeholder: 'Valor Já Pago *',
            type: 'money',
            maxlength: 255,
            required: true,
        },
        {
            name: 'description',
            placeholder: 'Pix *',
            type: 'text',
            maxlength: 255,
            required: true,
        },
        {
            name: 'desired_date',
            placeholder: 'Data Prevista Pagamento *',
            type: 'mask',
            mask: '99/99/9999',
            required: true,
        },
    ];

    return (
        <>
        <VHeader />
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/billsReceive" nameScreen="Pagamento a Receber" fields={fields} />
        </Container>
        </>
    )
};

export default BillsReceiveForm;
