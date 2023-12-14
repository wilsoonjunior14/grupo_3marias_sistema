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
            <Col><Nav.Link href="/engineering/projects">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Projetos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%", marginLeft: 0}}>
            <Col>
            <Nav className="">
            <NavDropdown
                            title="Empreendimentos"
                            className="App-primary-color">
                        <NavDropdown.Item href="/engineering/constructions">
                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                    Lista de Empreendimentos
                        </NavDropdown.Item>
                        <NavDropdown.Divider />
                        <NavDropdown.Item href="/engineering/constructions/add">
                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                    Cadastrar Empreendimento
                        </NavDropdown.Item>
                        <NavDropdown.Divider />
                    <NavDropdown.Divider />
                </NavDropdown>
            </Nav>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderEngenharia;
