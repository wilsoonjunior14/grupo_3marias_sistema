import Container from "react-bootstrap/Container";
import CustomForm from "../../../components/form/Form";
import VHeader from "../../../components/vHeader/vHeader";

const EngineerForm = ({}) => {

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
        <Container style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/engineers" nameScreen="Engenheiro(a)" fields={fields} />
        </Container>
        </>
    )
};

export default EngineerForm;
