import React, { useState } from "react";
import Container from 'react-bootstrap/Container';
import VHeader from "../../components/vHeader/vHeader";
import '../../App.css';
import CustomTable from "../../components/table/Table";
import CustomButton from '../../components/button/Button';
import Modal from 'react-bootstrap/Modal';
import Table from "react-bootstrap/esm/Table";
import Row from "react-bootstrap/esm/Row";
import Col from "react-bootstrap/esm/Col";
import { getMoney } from "../../services/Utils";
import Button from 'react-bootstrap/esm/Button';
import Loading from '../../components/loading/Loading';
import Success from "../../components/success/Success";
import Error from "../../components/error/Error";
import { performRequest } from "../../services/Api";

export default function PurchaseOrdersList() {

    const [showItemsModal, setShowItemsModal] = useState(false);
    const [showRejectModal, setShowRejectModal] = useState(false);
    const [showApproveModal, setShowApproveModal] = useState(false);
    const [isRejecting, setIsRejecting] = useState(false);
    const [isApproving, setIsApproving] = useState(false);
    const [errorMessage, setErrorMessage] = useState(null);
    const [successMessage, setSuccessMessage] = useState(null);
    const [purchase, setPurchase] = useState({description: "", value: "", items: []});

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
            onClick: (evt) => {setPurchase(evt); setShowApproveModal(true);}
        },
        {
            name: "cancel_purchase_order",
            tooltip: "Cancelar Ordem de Compra",
            icon: "thumb_down",
            onClick: (evt) => {setPurchase(evt); setShowRejectModal(true);}
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

    const onApprove = () => {
        setIsApproving(true);
        setSuccessMessage(null);
        setErrorMessage(null)
        
        performRequest("POST", "/v1/purchaseOrders/approve/"+purchase.id)
        .then(onSuccessResponse)
        .catch(onErrorResponse);
    }

    const onReject = () => {
        setIsRejecting(true);
        setSuccessMessage(null);
        setErrorMessage(null)
        
        performRequest("POST", "/v1/purchaseOrders/reject/"+purchase.id)
        .then(onSuccessResponse)
        .catch(onErrorResponse);
    }

    const onSuccessResponse = (res) => {
        setIsRejecting(false);
        setShowRejectModal(false);
        setIsApproving(false);
        setShowApproveModal(false);
        setSuccessMessage("Operação realizada com sucesso!");
    }

    // todo: it can be standardized
    const onErrorResponse = (res) => {
        setIsRejecting(false);
        setShowRejectModal(false);
        setIsApproving(false);
        setShowApproveModal(false);
        setErrorMessage("Não foi possível concluir a operação.");
    }
    
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

            <Modal 
                size={"lg"}
                centered 
                show={showRejectModal} onHide={() => {setShowRejectModal(false)}}>
                <Modal.Header closeButton>
                    <Modal.Title>Atenção</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    Você deseja realmente rejeitar a ordem de compra <b>{purchase.description}</b>?
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Cancelar" color="light" onClick={() => {setShowRejectModal(false)}}></CustomButton>
                    {!isRejecting &&
                        <CustomButton name="Rejeitar" 
                            color="danger" onClick={onReject}></CustomButton>
                    }
                    {isRejecting &&
                        <Button variant="danger"
                        size="lg"
                        disabled={isRejecting}>
                        {isRejecting ? <Loading />
                                    : 
                                    'Rejeitar'}
                        </Button>
                    }
                </Modal.Footer>
            </Modal>

            <Modal 
                size={"lg"}
                centered 
                show={showApproveModal} onHide={() => {setShowApproveModal(false)}}>
                <Modal.Header closeButton>
                    <Modal.Title>Atenção</Modal.Title>
                </Modal.Header>
                        
                <Modal.Body>
                    Você deseja realmente efetuar a ordem de compra <b>{purchase.description}</b>?
                </Modal.Body>

                <Modal.Footer>
                    <CustomButton name="Cancelar" color="light" onClick={() => {setShowApproveModal(false)}}></CustomButton>
                    {!isApproving &&
                        <CustomButton name="Aprovar" 
                            color="success" onClick={onApprove}></CustomButton>
                    }
                    {isApproving &&
                        <Button variant="success"
                        size="lg"
                        disabled={isApproving}>
                        {isApproving ? <Loading />
                                    : 
                                    'Aprovar'}
                        </Button>
                    }
                </Modal.Footer>
            </Modal>

            {successMessage &&
                <Success message={successMessage} />
            }

            {errorMessage &&
                <Error message={errorMessage} />
            } 

            {!isRejecting && !isApproving &&
                <CustomTable 
                    tableName="Compras" 
                    tableIcon="shopping_cart" 
                    fieldNameDeletion="description" 
                    url="/purchaseOrders"
                    tableFields={table}
                    searchFields={fields}
                    customOptions={customOptions} />
            }

            </Container>
        </>
    );
}