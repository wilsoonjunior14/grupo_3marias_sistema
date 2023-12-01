import { useParams } from "react-router-dom";
import CustomForm from "../../components/form/Form";

const CategoriesForm = ({}) => {

    const parameters = useParams();

    const fields = [
        {
            name: "image",
            placeholder: "Imagem",
            type: "file",
            required: true
        },
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
        <CustomForm endpoint="/v1/categories" nameScreen="Categoria" fields={fields} />
        </>
    )
};

export default CategoriesForm;
