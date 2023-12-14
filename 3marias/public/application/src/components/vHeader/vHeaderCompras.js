import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';

function VHeaderCompras() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/shopping">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Compras</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/shopping/orders">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Ordens de Compra</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/shopping/orders_services">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Ordens de Serviço</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/shopping/partners">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Parceiros</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/shopping/services">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Serviços</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/shopping/categoryServices">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Categorias de Serviços</Nav.Link>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderCompras;
