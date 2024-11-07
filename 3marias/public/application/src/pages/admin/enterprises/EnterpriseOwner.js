import CustomForm from "../../../components/form/Form";
import VHeader from "../../../components/vHeader/vHeader";
import Container from "react-bootstrap/Container";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const EnterpriseOwnerForm = ({}) => {

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
            name: 'ocupation',
            placeholder: 'Profissão *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'email',
            placeholder: 'Email *',
            type: 'email',
            maxlength: 100,
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
            name: "state",
            placeholder: "Estado Civil *",
            type: "select",
            required: true,
            data: ["Solteiro", "Casado", "Divorciado", "Viúvo"]
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
            endpoint: "cities",
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
            <CustomForm endpoint="/v1/enterpriseOwners" nameScreen="Representante Legal" fields={fields} />
        </Container>
        }

        {!(isAdmin || isDeveloper) &&
        <Forbidden />
        }
        </>
    )
};

export default EnterpriseOwnerForm;
