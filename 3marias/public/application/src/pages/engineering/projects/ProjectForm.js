import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import "./Projects.css";
import CustomForm from "../../../components/form/Form";

export default function ProjectForm() {

    const fields = [
        {
            name: "name",
            placeholder: "Nome *",
            maxlength: 255,
            type: "text",
            required: true
        },
        {
            name: "description",
            placeholder: "Descrição *",
            maxlength: 1000,
            type: "text",
            required: true
        }
    ]

    return (
        <>
        <VHeader />
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <CustomForm endpoint="/v1/projects" nameScreen="Projeto" fields={fields} />
        </Container>
        </>
    );

}