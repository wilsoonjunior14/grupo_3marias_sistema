import React from "react";
import VHeader from "../../components/vHeader/vHeader";
import Container from "react-bootstrap/Container";
import "../../App.css";

export default function Home() {

    return (
        <>
            <VHeader />
            <Container id='app-container' className="home-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            </Container>
        </>
    );
};