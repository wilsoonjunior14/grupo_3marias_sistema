import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';
// import NavDropdown from 'react-bootstrap/NavDropdown';

function VHeaderAdmin() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/admin/clients">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Clientes</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/admin/users">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Usuários</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/enterprises/1">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Empresa</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/projects">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Projetos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/partners">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Parceiros/Fornecedores</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/services">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Serviços</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/categoryServices">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Categorias de Serviços</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/cities">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Cidades</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/states">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Estados</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/groups">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Grupos de Usuários</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/admin/roles">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Permissões</Nav.Link>
            </Col>
        </Row>

{/*                         
        <Row style={{width: "100%", marginLeft: 0}}>
            <Col>
            <Nav className="">
            <NavDropdown
                            title="Cadastros"
                            className="App-primary-color">
                        
                       
                        <NavDropdown.Item href="/admin/states">
                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Estados</NavDropdown.Item>
                        <NavDropdown.Divider />
                        <NavDropdown.Item href="/admin/cities">
                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Cidades
                        </NavDropdown.Item>
                    <NavDropdown.Divider />
                </NavDropdown>
            </Nav>
            </Col>
        </Row> */}
        </>
    );
}

export default VHeaderAdmin;
