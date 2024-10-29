import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';
import { hasPermission } from '../../services/Storage';

function VHeaderAdmin() {
    const isAdmin = hasPermission("PROPRIETÁRIO");
    const isDeveloper = hasPermission("DESENVOLVEDOR");
    const isUser = hasPermission("USUÁRIO");

    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/admin/clients">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Clientes</Nav.Link>
            </Col>
        </Row>
        {(isAdmin || isDeveloper) &&
        <>
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
            <Col><Nav.Link href="/admin/engineers">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Engenheiros</Nav.Link>
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
        </>
        }
        {isDeveloper &&
        <>
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
        </>
        }
        </>
    );
}

export default VHeaderAdmin;
