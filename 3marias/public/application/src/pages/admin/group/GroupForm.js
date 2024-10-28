import Container from "react-bootstrap/esm/Container";
import CustomForm from "../../../components/form/Form";
import { hasPermission } from "../../../services/Storage";
import VHeader from "../../../components/vHeader/vHeader";
import Forbidden from "../../../components/error/Forbidden";

const GroupForm = ({}) => {
    const isDeveloper = hasPermission("DESENVOLVEDOR");

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
        <VHeader />

        {(isDeveloper) &&
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm id='app-container' endpoint="/v1/groups" nameScreen="Grupo de Usuário" fields={fields} />
        </Container>
        }

        {!(isDeveloper) &&
            <Forbidden />
        }
        </>
    )
};

export default GroupForm;
