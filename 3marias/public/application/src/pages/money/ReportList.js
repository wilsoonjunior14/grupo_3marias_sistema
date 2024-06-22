import React, { useState } from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import Card from 'react-bootstrap/Card';
import Table from 'react-bootstrap/Table';
import Search from "../../components/search/Search";
import Error from "../../components/error/Error";
import { validateDate } from "../../services/Validation";
import Loading from "../../components/loading/Loading";
import NoEntity from "../../components/table/NoEntity";
import { performRequest } from "../../services/Api";
import { processDataBefore } from "../../services/Utils";
import { formatDate, formatMoney } from "../../services/Format";
import "./Report.css";

export default function ReportList() {
    const [errorMessage, setErrorMessage] = useState(null);
    const [tickets, setTickets] = useState([]);
    const [total, setTotal] = useState("");
    const [loadingTickets, setLoadingTickets] = useState(false);
    const fields = [
        {
            id: 'begin_date',
            placeholder: 'Data Inicial *',
            type: 'mask',
            mask: '99/99/9999',
            maskPlaceholder: 'Data Inicial *',
            required: true
        },
        {
            id: 'end_date',
            placeholder: 'Data Final *',
            type: 'mask',
            mask: '99/99/9999',
            maskPlaceholder: 'Data Final *',
            required: true
        }
    ];

    const onSearch = (input) => {
        setErrorMessage(null);
        const beginDateValidation = validateDate(input, "begin_date", "Data Inicial", true);
        if (beginDateValidation) {
            setErrorMessage(beginDateValidation.message);
            return;
        }
        const endDateValidation = validateDate(input, "end_date", "Data Final", true);
        if (endDateValidation) {
            setErrorMessage(endDateValidation.message);
            return;
        }
        setLoadingTickets(true);
        setTickets([]);
        let payload = processDataBefore(Object.assign({}, input));

        performRequest("POST", "/v1/billsTicket/search", payload)
        .then(onSuccessSearch)
        .catch(onError);
    };

    const onSuccessSearch = (res) => {
        setLoadingTickets(false);
        setTickets(res.data);
        
        let value = 0;
        res.data.forEach((ticket) => {
            if (ticket.bill_receive_id) {
                value += Number(ticket.value);
            }
            if (ticket.bill_pay_id) {
                value -= Number(ticket.value);
            }
        });
        setTotal(formatMoney(value.toString()));
    };

    const onError = (err) => {
        setLoadingTickets(false);
        setErrorMessage("Não foi possível conectar-se com o servidor.");
    }

    const onReset = () => {
        setErrorMessage(null);
        setTickets([]);
    }
    
    return (
        <>
            <VHeader />
            <Container id="app-container" style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
                {errorMessage !== null &&
                <Error message={errorMessage} />
                }
                <Search key={"searchPanel"} onSearch={onSearch} onReset={onReset} fields={fields} />
                <Row>
                    <Col>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                <i className="material-icons float-left">attach_money</i>
                                    Pagamentos ({tickets.length})
                                </Card.Title>
                                {loadingTickets &&
                                <Row>
                                    <Col lg={6}></Col>
                                    <Col lg={4}>
                                        <Loading />
                                    </Col>
                                    <Col lg={2}></Col>
                                </Row>
                                }
                                {!loadingTickets &&
                                <Table responsive striped>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Data</th>
                                            <th>Descrição</th>
                                            <th>Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {tickets.map((t) =>
                                        <tr>
                                            <td>{t.id}</td>
                                            <td>{formatDate(t.date)}</td>
                                            <td>{t.description}</td>
                                            {t.bill_receive_id &&
                                               <td style={{color: "green"}}>+ {formatMoney(t.value)}</td>
                                            }
                                            {t.bill_pay_id &&
                                               <td style={{color: "red"}}>- {formatMoney(t.value)}</td>
                                            }
                                        </tr>
                                        )}
                                        {tickets.length > 0 &&
                                            <tr>
                                                <td colSpan={3}></td>
                                                <td>
                                                    Total: {total}
                                                </td>
                                            </tr>
                                        }
                                        {tickets.length === 0 &&
                                        <NoEntity count={4} message={"Nenhum pagamento encontrado."} />
                                        }
                                    </tbody>
                                </Table>
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>
            </Container>
        </>
    );
}