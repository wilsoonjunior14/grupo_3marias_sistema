import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';

function VHeaderMoney() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/money/billsReceive">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Contas a Receber</Nav.Link>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderMoney;
