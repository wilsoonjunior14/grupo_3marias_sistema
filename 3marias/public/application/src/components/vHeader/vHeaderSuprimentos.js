
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';

function VHeaderSuprimentos() {
    return (
        <>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/store/stocks">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Locais de Estoque</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}>
            <Col><Nav.Link href="/store/products">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Produtos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/store/equipments">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Equipamentos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/store/entries">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Entradas</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/store/consum">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Consumos</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/store/share">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Transferências</Nav.Link>
            </Col>
        </Row>
        <Row style={{width: "100%"}}> 
            <Col><Nav.Link href="/store/balances">
                <i className="material-icons float-left">keyboard_arrow_right</i>
                Balanços</Nav.Link>
            </Col>
        </Row>
        </>
    );
}

export default VHeaderSuprimentos;
