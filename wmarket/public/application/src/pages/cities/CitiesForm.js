import CustomForm from "../../components/form/Form";

const CitiesForm = ({}) => {

    const fields = [
        {
            name: "name",
            placeholder: "Nome",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "state_id",
            placeholder: "Estado",
            type: "select",
            required: true,
            endpoint: "states",
            endpoint_field: "name"
        }
    ]

    return (
        <>
        <CustomForm endpoint="/v1/cities" nameScreen="Cidade" fields={fields} />
        </>
    )
};

export default CitiesForm;
