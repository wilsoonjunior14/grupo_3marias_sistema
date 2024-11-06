import Container from "react-bootstrap/Container";
import CustomForm from "../../../components/form/Form";
import VHeader from "../../../components/vHeader/vHeader";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const BrokerForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const fields = [
        {
            name: "name",
            placeholder: "Nome Completo *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "creci",
            placeholder: "Creci *",
            maxlength: 255,
            type: "text",
            required: true,
        },
        {
            name: "cpf",
            placeholder: "CPF *",
            type: "mask",
            required: true,
            maxlength: 14,
            mask: "999.999.999-99"
        },
        {
            name: 'email',
            placeholder: 'Email',
            type: 'email',
            maxlength: 100,
            required: false
        },
        {
            name: 'phone',
            placeholder: 'Telefone',
            type: 'mask',
            maxlength: 14,
            required: false,
            mask: "(99)99999-9999"
        },
        {
            name: 'zipcode',
            placeholder: 'CEP *',
            type: 'mask',
            required: true,
            mask: '99999-999'
        },  
        {
            name: 'city_id',
            placeholder: 'Cidade *',
            type: 'select',
            required: true,
            endpoint: "cities",
            endpoint_field: "name"
        },      
        {
            name: 'address',
            placeholder: 'Endereço *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'neighborhood',
            placeholder: 'Bairro *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'number',
            placeholder: 'Número',
            type: 'text',
            maxlength: 4,
            required: false
        },
        {
            name: 'complement',
            placeholder: 'Complemento',
            type: 'text',
            maxlength: 255,
            required: false
        }
    ]

    return (
        <>
        <VHeader />
        {(isDeveloper) &&
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/brokers" nameScreen="Corretor" fields={fields} />
        </Container>
        }

        {!(isDeveloper) &&
            <Forbidden />
        }
        </>
    )
};

export default BrokerForm;
