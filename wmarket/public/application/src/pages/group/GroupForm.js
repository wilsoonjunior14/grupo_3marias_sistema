import { useParams } from "react-router-dom";
import CustomForm from "../../components/form/Form";

const GroupForm = ({}) => {
    const fields = [
        {
            name: "description",
            placeholder: "Nome do Grupo",
            maxlength: 255,
            type: "text",
            required: true
        }
    ]

    return (
        <>
        <CustomForm endpoint="/v1/groups" nameScreen="Grupo de Usuário" fields={fields} />
        </>
    )
};

export default GroupForm;
