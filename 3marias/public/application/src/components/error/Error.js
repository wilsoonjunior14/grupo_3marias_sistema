import React, {useState, useEffect} from "react";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Alert from 'react-bootstrap/Alert';
import '../../App.css';

const Error = ({message}) => {
    return (
        <>
        <Row>
            <Col>
                <Alert key='danger' variant='danger'>
                    <i className="material-icons float-left">
                        error
                    </i>
                     {message}
                </Alert>
            </Col>
        </Row>
        </>
    );
}

export default Error;
