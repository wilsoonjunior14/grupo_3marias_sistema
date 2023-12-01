import CustomForm from "../../components/form/Form";

const RolesForm = ({}) => {

    const fields = [
        {
            name: "description",
            placeholder: "Descrição",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "endpoint",
            placeholder: "URL",
            maxlength: 100,
            type: "text",
            required: true
        },
        {
            name: "request_type",
            placeholder: "Tipo de Requisição",
            type: "select",
            required: true,
            data: [
                "get", "post", "put", "patch", "delete"
            ]
        }
    ]

    return (
        <>
        <CustomForm endpoint="/v1/roles" nameScreen="Permissão" fields={fields} />
        </>
    )
};

export default RolesForm;
