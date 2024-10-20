import React, { useEffect, useState } from "react";
import "../../App.css";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from "react-bootstrap/Card";
import Loading from "../../components/loading/Loading";
import { performRequest } from '../../services/Api';
import { formatDate } from "../../services/Format";
import "./Home.css";

export default function Birthdate() {

    const [birthdates, setBirthdates] = useState([]);
    const [loadingBirthdates, setLoadingBirthdates] = useState(false);
    const congratsMessage = "A Construtora 3 Marias gostaria de lhe desejar feliz aniversário!! E muitos anos de vida.";

    const onGetBirthdates = () => {
        setLoadingBirthdates(true);

        performRequest("POST", "/v1/clients/birthdates")
        .then(successPost)
        .catch(errorResponse);
    }

    const successPost = (res) => {
        setBirthdates(res.data);
        setLoadingBirthdates(false);
    }

    const errorResponse = (err) => {
        setLoadingBirthdates(false);
    }

    const onSendCongrats = (client) => {
        if (!client.phone) {
            return;
        }
        const phoneWithoutChars = client.phone.replace("(", "").replace(")", "").replace("-", "");
        window.open("https://api.whatsapp.com/send/?phone=+55"+phoneWithoutChars+"&text="+congratsMessage);
    }

    useEffect(() => {
        onGetBirthdates();
    }, []);

    return (
        <Card className="card-birthdate" style={{color: "white"}}>
            <Card.Body>
                <Card.Title>
                    Aniversariantes do Mês
                    <i className="material-icons float-left">cake</i>
                </Card.Title>
                {loadingBirthdates &&
                <>
                <br></br>
                <Row>
                    <Col></Col>
                    <Col style={{position: "absolute", top: "50%", left: "45%"}}><Loading /></Col>
                    <Col></Col>
                </Row>
                </>
                }
                {!loadingBirthdates &&
                <>
                    {birthdates.length > 0 && birthdates.map((client) => 
                    <Row>
                        <Col style={{fontSize: 40, marginTop: 25, color: "white"}}>
                            <Card onClick={() => onSendCongrats(client)} className="card-birthdate-item">
                                <Card.Body>
                                    <Row>
                                        <Col xs={8} style={{fontSize: 12}}>
                                            <i className="material-icons float-left">cake</i>
                                            {client.name.toString().toUpperCase()}
                                        </Col>
                                        <Col xs={4} style={{fontSize: 12}}>
                                            {formatDate(client.birthdate)}
                                        </Col>
                                    </Row>
                                </Card.Body>
                            </Card>
                        </Col>
                    </Row>
                    )}
                    {birthdates.length === 0 && 
                        <Row>
                        <Col style={{fontSize: 40, marginTop: 25, color: "white"}}>
                            <h5>Nenhum aniversariante este mês.</h5>
                        </Col>
                    </Row>
                    }
                </>
                }
            </Card.Body>
        </Card>
    );
};