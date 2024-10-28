import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const PartnerForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const fields = [
        {
            name: 'fantasy_name',
            placeholder: 'Nome Fantasia *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'cnpj',
            placeholder: 'CNPJ *',
            type: 'mask',
            mask: '99.999.999/9999-99',
            required: true
        },
        {
            name: 'partner_type',
            placeholder: 'Tipo de Pessoa *',
            type: 'select',
            data: ["Física", "Jurídica"],
            required: true
        },
        {
            name: 'social_reason',
            placeholder: 'Razão Social',
            type: 'text',
            maxlength: 255,
            required: false
        },
        {
            name: 'email',
            placeholder: 'Email',
            type: 'email',
            maxlength: 255,
            required: false
        },
        {
            name: 'phone',
            placeholder: 'Telefone',
            type: 'mask',
            mask: '(99)99999-9999',
            required: false
        },
        {
            name: 'website',
            placeholder: 'Link para WebSite/Redes Sociais',
            type: 'text',
            maxlength: 255,
            required: false
        },
        {
            name: 'observation',
            placeholder: 'Observação',
            type: 'text',
            maxlength: 255,
            required: false
        },
    ];

    return (
        <>
        <VHeader />
        {(isDeveloper || isAdmin) &&
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/partners" nameScreen="Parceiro/Fornecedor" fields={fields} />
        </Container>
        }

        {!(isDeveloper || isAdmin) &&
            <Forbidden />
        }
        </>
    )
};

export default PartnerForm;
