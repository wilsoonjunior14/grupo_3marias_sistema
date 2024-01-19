import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';
import NavDropdown from 'react-bootstrap/NavDropdown';

function VHeaderEngenharia() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/engineering/proposals">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Propostas</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/engineering/constructions">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Obras</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%", marginLeft: 0}}>
            <Col>
            <Nav className="">
            <NavDropdown
                    title="Projetos"
                    className="App-primary-color">
                <NavDropdown.Item href="/engineering/projects">
                        <i className="material-icons float-left">keyboard_arrow_right</i>
                            Lista de Projetos
                </NavDropdown.Item>
                <NavDropdown.Divider />
                <NavDropdown.Item href="/engineering/projects/add">
                        <i className="material-icons float-left">keyboard_arrow_right</i>
                            Cadastrar Projeto
                </NavDropdown.Item>
                <NavDropdown.Divider />
            </NavDropdown>
            </Nav>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderEngenharia;
