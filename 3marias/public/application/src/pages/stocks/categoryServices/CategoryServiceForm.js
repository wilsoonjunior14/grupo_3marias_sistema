import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const CategoryServiceForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isAdmin = hasPermission("PROPRIETÁRIO");

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
        {(isDeveloper || isAdmin) &&
        <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/categoryServices" nameScreen="Categoria de Serviço" fields={fields} />
        </Container>
        }

        {!(isDeveloper || isAdmin) &&
            <Forbidden />
        }
        </>
    )
};

export default CategoryServiceForm;
