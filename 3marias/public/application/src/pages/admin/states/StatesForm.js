import CustomForm from "../../../components/form/Form";

const StatesForm = ({}) => {

    const fields = [
        {
            name: "name",
            placeholder: "Nome",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "acronym",
            placeholder: "Sigla",
            maxlength: 2,
            type: "text",
            required: true
        },
        {
            name: "country_id",
            placeholder: "País",
            type: "select",
            required: true,
            endpoint: "countries",
            endpoint_field: "name"
        }
    ]

    return (
        <>
        <CustomForm endpoint="/v1/states" nameScreen="Estado" fields={fields} />
        </>
    )
};

export default StatesForm;
