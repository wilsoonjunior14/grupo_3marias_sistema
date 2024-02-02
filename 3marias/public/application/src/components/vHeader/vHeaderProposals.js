import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';

function VHeaderProposals() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/proposals">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Ver Propostas</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/proposals/add">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Cadastrar Proposta</Nav.Link>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderProposals;
