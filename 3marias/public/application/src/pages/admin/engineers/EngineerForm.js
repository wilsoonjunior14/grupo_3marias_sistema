import Container from "react-bootstrap/Container";
import CustomForm from "../../../components/form/Form";
import VHeader from "../../../components/vHeader/vHeader";
import { hasPermission } from "../../../services/Storage";
import Forbidden from "../../../components/error/Forbidden";

const EngineerForm = ({}) => {
    const isAdmin = hasPermission("PROPRIETÁRIO");
    const isDeveloper = hasPermission("DESENVOLVEDOR");

    const fields = [
        {
            name: "name",
            placeholder: "Nome *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "email",
            placeholder: "Email *",
            type: "email",
            required: true,
            maxlength: 100
        },
        {
            name: "crea",
            placeholder: "CREA *",
            type: "text",
            required: true,
            maxlength: 10
        }
    ]

    return (
        <>
        <VHeader />
        {(isDeveloper || isAdmin) &&
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/engineers" nameScreen="Engenheiro(a)" fields={fields} />
        </Container>
        }

        {!(isDeveloper || isAdmin) &&
            <Forbidden />
        }
        </>
    )
};

export default EngineerForm;
