import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";

const UserForm = ({}) => {

    const fields = [
        {
            name: 'name',
            placeholder: 'Nome',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: 'email',
            placeholder: 'Email',
            type: 'email',
            maxlength: 100,
            required: true
        },
        {
            name: 'birthdate',
            placeholder: 'Data de Nascimento',
            type: 'mask',
            maxlength: 10,
            required: true,
            mask: "99/99/9999"
        },
        {
            name: 'phoneNumber',
            placeholder: 'Telefone',
            type: 'mask',
            maxlength: 14,
            required: true,
            mask: "(99)99999-9999"
        },
        {
            name: "group_id",
            placeholder: "Grupo de Usuário",
            type: "select",
            required: true,
            endpoint: "groups",
            endpoint_field: "description"
        },
        {
            name: "password",
            placeholder: "Senha",
            type: "password",
            required: true,
            maxlength: 100
        },
        {
            name: "conf_password",
            placeholder: "Confirmar Senha",
            type: "password",
            required: true,
            maxlength: 100
        }
    ];

    return (
        <>
        <VHeader />
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm id='app-container' endpoint="/users" nameScreen="Usuário" fields={fields} />
        </Container>
        </>
    )
};

export default UserForm;
