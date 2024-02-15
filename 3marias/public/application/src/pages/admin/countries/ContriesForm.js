import CustomForm from "../../../components/form/Form";

const CountriesForm = ({}) => {

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
            maxlength: 3,
            type: "text",
            required: true
        }
    ]

    return (
        <>
        <CustomForm endpoint="/v1/countries" nameScreen="País" fields={fields} />
        </>
    )
};

export default CountriesForm;
