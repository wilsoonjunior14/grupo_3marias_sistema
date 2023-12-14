import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';

const CategoryServiceForm = ({}) => {

    const fields = [
        {
            name: 'name',
            placeholder: 'Nome da Categoria *',
            type: 'text',
            maxlength: 255,
            required: true
        }
    ];

    return (
        <>
        <VHeader />
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/categoryServices" nameScreen="Categoria de Serviço" fields={fields} />
        </Container>
        </>
    )
};

export default CategoryServiceForm;
