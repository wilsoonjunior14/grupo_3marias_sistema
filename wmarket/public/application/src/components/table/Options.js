import React from "react";
import CustomButton from "../button/Button";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';

const Options = ({onLoad, onAdd, disableAdd}) => {
    return (
        <>
        <Row>
            <Col xs={12} sm={12} md={12} lg={10}></Col>
            {!disableAdd &&
            <Col xs={6} sm={1} md={6} lg={1}>
                <CustomButton name="btnAdd" tooltip="Adicionar" icon="add" color="success" onClick={onAdd} />
            </Col>
            }
            <Col xs={6} sm={1} md={6} lg={1}>
                <CustomButton name="btnRefresh" tooltip="Atualizar" icon="refresh" color="light" onClick={onLoad} />
            </Col>
        </Row>
        <br></br>
        </>
    );
}

export default Options;
