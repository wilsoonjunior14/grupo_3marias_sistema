import CustomForm from "../../../components/form/Form";

const EnterpriseForm = ({}) => {

    const fields = [
        {
            name: "name",
            placeholder: "Nome",
            maxlength: 255,
            type: "text",
            required: true
        }
    ]

    return (
        <>
        <CustomForm endpoint="enterprises" nameScreen="Empresa" fields={fields} />
        </>
    )
};

export default EnterpriseForm;
