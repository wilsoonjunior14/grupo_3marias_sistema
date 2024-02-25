import React, { useState } from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import Success from "../../components/success/Success";
import Error from "../../components/error/Error";
import Button from 'react-bootstrap/esm/Button';
import Loading from '../../components/loading/Loading';
import CustomButton from '../../components/button/Button';
import Modal from 'react-bootstrap/Modal';
import Table from "react-bootstrap/esm/Table";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import { getMoney } from "../../services/Utils";

export default function PurchaseOrdersList() {

    const [showItemsModal, setShowItemsModal] = useState(false);
    const [purchase, setPurchase] = useState(null);

    const fields = [
        {
            id: 'description',
            placeholder: 'Descrição',
            type: 'text',
            maxlength: 255
        }
    ];

    const table = {
        fields: ["Status", "Código", "Descrição", "Valor", "Data"],
        amountOptions: 1,
        bodyFields: ["icon", "id", "description", "value", "date"]
    };

    // TODO: only admin or developer can approve or cancel a purchase order
    const customOptions = [
        {
            name: "approve_purchase_order",
            tooltip: "Efetuar Ordem de Compra",
            icon: "thumb_up",
            onClick: (evt) => {}
        },
        {
            name: "cancel_purchase_order",
            tooltip: "Cancelar Ordem de Compra",
            icon: "thumb_down",
            onClick: (evt) => {}
        },
        {
            name: "see_purchase_order",
            tooltip: "Ver Ordem de Compra",
            icon: "visibility",
            onClick: (evt) => {showPurchaseOrder(evt);}
        }
    ];

    const showPurchaseOrder = (purchase) => {
        setPurchase(purchase);
        setShowItemsModal(true);
    };
    
    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>

            <Modal 
                size={"lg"}
                centered 
                show={showItemsModal} onHide={() => {setShowItemsModal(false)}}>
                <Modal.Header closeButton>
                    <Modal.Title>Ordem de Compra - {purchase.description}</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    <Row>
                        <Col xs={12}>
                            <h5>
                                <i className="material-icons float-left">business_center</i>
                                Itens da Compra ({getMoney(purchase.value.toString().replace(".", ","))})</h5>
                        </Col>
                        <Col>
                            <Table responsive striped>
                                <thead>
                                    <tr>
                                        <th>Produto</th>
                                        <th>Quantidade</th>
                                        <th>Valor Unitário</th>
                                        <th>Valor Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {purchase && purchase.items.map((item) => 
                                    <tr>
                                        <td>{item.product_id}</td>
                                        <td>{item.quantity}</td>
                                        <td>{getMoney(item.value.toString().replace(".", ","))}</td>
                                        <td>{getMoney((item.value * item.quantity).toString().replace(".", ","))}</td>
                                    </tr>
                                    )}
                                </tbody>
                            </Table>
                        </Col>
                    </Row>
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Fechar" color="light" onClick={() => {setShowItemsModal(false)}}></CustomButton>
                </Modal.Footer>
            </Modal>

                <CustomTable 
                    tableName="Compras" 
                    tableIcon="shopping_cart" 
                    fieldNameDeletion="description" 
                    url="/purchaseOrders"
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />

            </Container>
        </>
    );
}