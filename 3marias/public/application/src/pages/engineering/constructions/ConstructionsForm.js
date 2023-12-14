import React from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../../components/vHeader/vHeader";
import '../../../App.css';
import "./Constructions.css";

export default function ConstructionsForm() {

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            </Container>
        </>
    );
}