import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';

const ProductForm = ({}) => {

    const fields = [
        {
            name: 'product',
            placeholder: 'Nome do Produto *',
            type: 'text',
            maxlength: 255,
            required: true
        },
        {
            name: "category_product_name",
            placeholder: "Categoria do Produto *",
            type: "select2",
            required: true,
            endpoint: "categoryProducts",
            endpoint_field: "name"
        }
    ];

    return (
        <>
        <VHeader />
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/products" nameScreen="Produto" fields={fields} />
        </Container>
        </>
    )
};

export default ProductForm;
