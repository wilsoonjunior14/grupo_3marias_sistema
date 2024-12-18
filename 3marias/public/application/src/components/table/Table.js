import React, { useEffect, useState } from "react";
import CustomButton from "../button/Button";
import Modal from 'react-bootstrap/Modal';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Form from 'react-bootstrap/Form';
import Table from 'react-bootstrap/Table';
import Options from "./Options";
import Loading from "../loading/Loading";
import Error from "../error/Error";
import { useNavigate } from "react-router-dom";
import { performRequest, BASE_URL } from "../../services/Api";
import CustomPagination from "./Pagination";
import Thead from "./Thead";
import NoEntity from "./NoEntity";
import { formatDate, formatDateTime, formatDoubleValue, getValueOfComplexField } from "../../services/Format";
import Search from "../search/Search";
import "../../App.css";
import TableButton from "../button/TableButton";
import ProgressBar from 'react-bootstrap/ProgressBar';

const CustomTable = ({tableName, tableNamePlaceholder, tableIcon, 
    url, tableFields, fieldNameDeletion, searchFields, customOptions, refresh,
    disableEdit, disableDelete, disableAdd }) => {
    
    const [item, setItem] = useState({});
    const [items, setItems] = useState([]);
    const [itemsPerPage, setItemsPerPage] = useState([]);
    const [originalItems, setOriginalItems] = useState([]);
    const [httpError, setHttpError] = useState(null);
    const [loading, setLoading] = useState(false);
    const [showDialogModal, setShowDialogModal] = useState(false);
    const [showSuccessModal, setShowSuccessModal] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        loadItems();
    }, []);

    useEffect(() => {
        setDataPagination(1, 10, items);
    }, [items]);

    useEffect(() => {
        loadItems();
    }, [refresh]);

    const loadItems = () => {
        if(loading) {
            return;
        }

        setItems([]);
        setHttpError(null);
        setLoading(true);

        performRequest("GET", "/v1" + url)
        .then(successGet)
        .catch(errorGet);
    };

    const successGet = (response) => {
        setLoading(false);
        setItems(response.data);
        setOriginalItems(response.data);
    };

    const errorGet = (response) => {
        setLoading(false);
        const res = response.response;
        if (res) {
            if (res.status === 401) {
                setHttpError({message: "Você precisa estar logado na aplicação para efetuar essa operação.", statusCode: res.status});
                return;
            }
            if (res.status === 404) {
                setHttpError({message: "Operação desconhecida não pode ser realizada.", statusCode: res.status});
                return;
            }
            setHttpError({message: res.data.message, statusCode: res.status});
            return;
        }
        setHttpError({message: "Não foi possível conectar-se com o servidor."});
    };

    const setDataPagination = (page, pageSize, items) => {
        const begin = (page - 1) * pageSize;
        const end = page * pageSize;

        var data = [];
        for (var i=begin; i<end; i++) {
            if (items[i]) {
                data.push(items[i]);
            }
        }
        setItemsPerPage(data);
    };

    const onEditItem = (item) => {
        navigate(window.location.pathname + "/edit/"+item.id);
    };

    const onDeleteItem = (item) => {
        setItem(item);
        setShowDialogModal(true);
    };

    const onConfirmDelete = () => {
        setShowDialogModal(false);
        setLoading(true);

        performRequest("DELETE", "/v1" + url + "/" + item.id, null)
        .then(successDelete)
        .catch(errorGet);
    };

    const successDelete = (response) => {
        setLoading(false);
        setShowSuccessModal(true);
        loadItems();
    };

    const getValueOfField = (item, field) => {
        if (field === "created_at" || field === "updated_at") {
            return formatDateTime(item[field]);
        }
        if (field === "birthdate" || field.indexOf("date") !== -1) {
            return formatDate(item[field]);
        }
        if (field.indexOf(".") !== -1) {
            return getValueOfComplexField(item, field);
        }
        if (field === "image") {
            return (<img width={40} height={40} src={BASE_URL + "/images/" + "category" + "/" + item[field]} />);
        }
        if (field === "icon") {
            if (item["icon_color"]) {
                return (<i style={{color: item["icon_color"]}} className="material-icons">{item[field]}</i>);
            }
            return (<i className="material-icons">{item[field]}</i>);
        }
        if (field === "progress") {
            const now = item[field];
            return (<ProgressBar animated now={now} label={`${now}%`} />);
        }
        if (field === "status") {
            return getStatusField(item[field]);
        }
        if (field.indexOf("value") !== -1) {
            const v = Number(item[field].toString().replace(".", "").replace(",", "."));
            return (v).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
        }
        return item[field];
    };

    const getTDField = (item, field) => {
        const value = getValueOfField(item, field);
        if (field === "id" || field === "icon") {
            return <td key={item[field] + "field" + field}>{value}</td>
        }
        if (field.indexOf("name") !== -1 || field.indexOf("description") !== -1) {
            return <td key={item[field] + "field" + field} style={{minWidth: 300}}>{value}</td>;
        }
        return <td key={item[field] + "field" + field} style={{minWidth: 200}}>{value}</td>;
    }

    const getStatusField = (value) => {
        if (value === "active") {
            return (<i className="text-success material-icons">check_circle</i>);
        }
        if (value === "waiting") {
            return (<i className="text-warning material-icons">report</i>);
        }
        if (value === "inactive") {
            return (<i className="text-danger material-icons">remove_circle</i>);
        }
        return value;
    }

    const onReset = () => {
        setItems(originalItems);
    }

    const onSearch = (inputData) => {
        if (!inputData) {
            setItems(originalItems);
        }

        if (Object.keys(inputData).length === 0) {
            setItems(originalItems);
        }

        const allEntriesEmpty = Object.keys(inputData).every((key) => {
            return inputData[key].toString().length === 0;
        });
        if (allEntriesEmpty) {
            setItems(originalItems);
        }

        const itemsFiltered = originalItems.filter((item) => {
            var matches = 0;
            var amountMatches = 0;

            Object.keys(inputData).forEach((key) => {
                if (inputData[key] !== "") {
                    amountMatches = amountMatches + 1;
                    var fieldValue = getValueOfComplexField(item, key);
                    if (fieldValue && 
                        fieldValue.toString().toLowerCase().indexOf(inputData[key].toString().toLowerCase()) != -1) {
                        matches = matches + 1;
                    }
                }    
            });

            return matches === amountMatches;
        });

        setItems(itemsFiltered);
    };

    return (
        <>
        <Modal show={showDialogModal} onHide={() => {setShowDialogModal(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>Atenção</Modal.Title>
            </Modal.Header>
                    
            <Modal.Body>
                Você deseja realmente excluir <b>{item[fieldNameDeletion]}</b>?
            </Modal.Body>

            <Modal.Footer>
                <CustomButton name="Cancelar" color="light" onClick={() => {setShowDialogModal(false)}}></CustomButton>
                <CustomButton name="Deletar" color="danger" onClick={onConfirmDelete}></CustomButton>
            </Modal.Footer>
        </Modal>

        <Modal show={showSuccessModal} onHide={() => {setShowSuccessModal(false)}}>
            <Modal.Header closeButton>
                <Modal.Title>Atenção</Modal.Title>
                </Modal.Header>
            <Modal.Body>
                {tableNamePlaceholder ? tableNamePlaceholder : "Item"} excluído com sucesso!
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowSuccessModal(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        {searchFields && searchFields.length > 0 &&
        <Search 
            fields={searchFields} 
            onSearch={onSearch}
            onReset={onReset} />
        }

        <Row>
            <Col>
                <Card>
                    <Card.Body>
                        <Card.Title>
                        <i className="material-icons float-left">{tableIcon}</i>
                        {tableName} ({items.length})
                        </Card.Title>

                        <Form>
                            <Options disableAdd={disableAdd} onAdd={() => {navigate(window.location.pathname + "/add")}} onLoad={loadItems} />

                            {loading &&
                            <>
                            <Col></Col>
                            <Col style={{textAlign: 'center'}}>
                                <Loading />
                            </Col>
                            <Col></Col>
                            </>
                            }

                            {!loading && httpError &&
                            <Error message={httpError.message} />
                            }

                            {!loading && !httpError &&      
                            <>
                            <Row>
                                <Col xs="12">
                                    <Table responsive striped>
                                        <Thead fields={tableFields.fields} amountOptions={tableFields.amountOptions} />
                                        <tbody>
                                            {itemsPerPage.map((item) => 
                                                <tr key={item.id}>
                                                {tableFields.bodyFields.map((field) => 
                                                    getTDField(item, field)
                                                )}
                                                <td key={item.id + "options"} className="options">
                                                    {customOptions != null &&
                                                        customOptions.map((option) => 
                                                            <TableButton
                                                                key={"table-button-custom-" + option.name.toString() }
                                                                name={option.name} 
                                                                tooltip={option.tooltip} 
                                                                onClick={() => option.onClick(item)}
                                                            icon={option.icon} color="light" />
                                                        )
                                                    }
                                                        
                                                    {!disableEdit &&
                                                        <TableButton key={"table-button-edit-" + item.id} name="btnEdit" tooltip="Editar" onClick={() => onEditItem(item)}
                                                                icon="edit" color="light" />
                                                    }

                                                    {!disableDelete &&
                                                        <TableButton key={"table-button-delete-" + item.id} name="btnDelete" tooltip="Deletar" onClick={() => onDeleteItem(item)}
                                                                icon="delete" color="light" />
                                                    }
                                                </td>
                                            </tr>
                                            )
                                            }

                                            {itemsPerPage.length === 0 &&
                                                <NoEntity count={tableFields.bodyFields.length + 2} message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                                
                                <Col xs="12">
                                    {itemsPerPage.length !== 0 && items.length > 10 &&
                                    <CustomPagination data={items} setDataCallback={setDataPagination} />
                                    }
                                </Col>
                            </Row>
                            </>
                            }
                        </Form>
                    </Card.Body>
                </Card>
            </Col>
        </Row>
        </>
    );
}

export default CustomTable;