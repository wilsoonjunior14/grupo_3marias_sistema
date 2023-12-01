import CustomForm from "../../components/form/Form";

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
        <CustomForm endpoint="/users" nameScreen="Usuário" fields={fields} />
        </>
    )
};

export default UserForm;
