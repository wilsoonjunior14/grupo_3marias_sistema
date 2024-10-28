import React from "react";
import Error from "./Error";
import Container from "react-bootstrap/esm/Container";

const Forbidden = ({message}) => {
    return (
        <>
        <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
            <Error message={"Você não tem permissão para acessar esse conteúdo."} />
        </Container>
        </>
    );
}

export default Forbidden;
