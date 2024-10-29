import CustomForm from "../../../components/form/Form";
import VHeader from "../../../components/vHeader/vHeader";
import Container from "react-bootstrap/Container";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const EnterpriseForm = ({}) => {

    const isAdmin = hasPermission("PROPRIETÁRIO");
    const isDeveloper = hasPermission("DESENVOLVEDOR");

    const fields = [
        {
            name: "name",
            placeholder: "Nome *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "fantasy_name",
            placeholder: "Nome Fantasia *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "social_reason",
            placeholder: "Razão Social *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "creci",
            placeholder: "CRECI *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "state_registration",
            placeholder: "Inscrição Estadual *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "municipal_registration",
            placeholder: "Inscrição Municipal *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "phone",
            placeholder: "Telefone *",
            mask: '(99)99999-9999',
            type: "mask",
            required: true
        },
        {
            name: "address",
            placeholder: "Endereço *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "city_id",
            placeholder: "Cidade *",
            type: "select",
            endpoint: "citiesuf",
            endpoint_field: "name",
            required: true
        },
        {
            name: "neighborhood",
            placeholder: "Bairro *",
            maxlength: 100,
            type: "text",
            required: true
        },
        {
            name: "number",
            placeholder: "Número *",
            maxlength: 4,
            type: "number",
            required: true
        },
        {
            name: "zipcode",
            placeholder: "CEP *",
            maxlength: 255,
            type: "mask",
            mask: "99999-999",
            required: true
        },
        {
            name: "complement",
            placeholder: "Complemento",
            maxlength: 500,
            type: "text",
            required: false
        }
    ]

    return (
        <>
        <VHeader />

        {(isAdmin || isDeveloper) &&
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/enterprises" nameScreen="Dados da Empresa" fields={fields} />
        </Container>
        }

        {!(isAdmin || isDeveloper) &&
        <Forbidden />
        }
        </>
    )
};

export default EnterpriseForm;
