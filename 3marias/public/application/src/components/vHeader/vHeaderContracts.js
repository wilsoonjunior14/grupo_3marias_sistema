import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';

function VHeaderContracts() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/contracts/proposals/add">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Cadastrar Proposta</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/contracts/proposals">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Ver Propostas</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/contracts/add">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Cadastrar Contrato</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/contracts">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Ver Contratos</Nav.Link>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderContracts;
