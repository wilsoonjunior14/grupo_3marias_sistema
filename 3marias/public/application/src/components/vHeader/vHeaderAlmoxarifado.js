import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';

function VHeaderAlmoxarifado() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/stocks">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Centros de Custo</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/stocks/services">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Serviços</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/stocks/products">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Produtos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/stocks/categoryProducts">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Categorias de Produtos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/stocks/categoryServices">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Categorias de Serviços</Nav.Link>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderAlmoxarifado;
