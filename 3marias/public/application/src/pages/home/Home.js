import React from "react";
import VHeader from "../../components/vHeader/vHeader";
import Container from "react-bootstrap/Container";
import "../../App.css";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import "./Home.css";
import Birthdate from "./Birthdate";

export default function Home() {
    return (
        <>
            <VHeader />
            <Container id='app-container' className="home-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                <Row>
                    <Col xs={4}>
                        <Birthdate />
                    </Col>
                </Row>
            </Container>
        </>
    );
};