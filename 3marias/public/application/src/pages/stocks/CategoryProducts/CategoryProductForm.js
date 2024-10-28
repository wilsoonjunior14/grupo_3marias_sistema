import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const CategoryProductForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

    const fields = [
        {
            name: 'name',
            placeholder: 'Nome da Categoria *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: "category_products_father_id",
            placeholder: "Categoria Associada",
            type: "select2",
            required: false,
            endpoint: "categoryProducts",
            endpoint_field: "name"
        }
    ];

    return (
        <>
        <VHeader />
        {(isDeveloper || isAdmin) &&
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/categoryProducts" nameScreen="Categoria de Produto" fields={fields} />
        </Container>
        }

        {!(isDeveloper || isAdmin) &&
            <Forbidden />
        }
        </>
    )
};

export default CategoryProductForm;
