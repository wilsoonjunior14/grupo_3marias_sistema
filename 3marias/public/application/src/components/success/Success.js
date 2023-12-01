import React, {useState, useEffect} from "react";
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Alert from 'react-bootstrap/Alert';
import '../../App.css';

const Success = ({message}) => {
    const [show, setShow] = useState(true);

    useEffect(() => {
        setTimeout(() => {
            setShow(false);
        }, 5000);
    }, []);

    return (
        <>
        {show &&
        <Row>
            <Col>
                <Alert key='success' variant='success'>
                    <i className="material-icons float-left">
                        done
                    </i>
                     {message}
                </Alert>
            </Col>
        </Row>
        }
        </>
    );
}

export default Success;
