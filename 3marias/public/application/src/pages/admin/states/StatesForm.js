import CustomForm from "../../../components/form/Form";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const StatesForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");

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
            <VHeader />
            {(isDeveloper) &&
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <CustomForm endpoint="/v1/states" nameScreen="Estado" fields={fields} />
            </Container>
            }

            {!(isDeveloper) &&
                <Forbidden />
            }
        </>
    )
};

export default StatesForm;
