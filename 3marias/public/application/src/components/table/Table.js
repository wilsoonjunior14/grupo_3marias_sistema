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
import { formatDate, formatDateTime, getValueOfComplexField } from "../../services/Format";
import Search from "../search/Search";
import "../../App.css";
import TableButton from "../button/TableButton";

const CustomTable = ({tableName, tableIcon, url, tableFields, fieldNameDeletion, searchFields, customOptions, refresh,
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
        if (field === "birthdate") {
            return formatDate(item[field]);
        }
        if (field.indexOf(".") !== -1) {
            return getValueOfComplexField(item, field);
        }
        if (field === "image") {
            return (<img width={40} height={40} src={BASE_URL + "/images/" + "category" + "/" + item[field]} />);
        }
        if (field === "status") {
            return getStatusField(item[field]);
        }
        return item[field];
    };

    const getTDField = (item, field) => {
        const value = getValueOfField(item, field);
        if (field === "id") {
            return <td>{value}</td>;
        } else {
            return <td style={{minWidth: 200}}>{value}</td>
        }
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
                    if (item[key].toString().toLowerCase().indexOf(inputData[key].toString().toLowerCase()) != -1) {
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
                Item excluído com sucesso!
            </Modal.Body>
            <Modal.Footer>
                <CustomButton name="Fechar" color="light" onClick={() => {setShowSuccessModal(false)}}></CustomButton>
            </Modal.Footer>
        </Modal>

        {searchFields && searchFields.length > 0 &&
        <Search fields={searchFields} onSearch={onSearch} />
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
                                                <td className="options">
                                                    {customOptions != null &&
                                                        customOptions.map((option) => 
                                                            <TableButton 
                                                                name={option.name} 
                                                                tooltip={option.tooltip} 
                                                                onClick={() => option.onClick(item)}
                                                            icon={option.icon} color="light" />
                                                        )
                                                    }
                                                        
                                                    {!disableEdit &&
                                                        <TableButton name="btnEdit" tooltip="Editar" onClick={() => onEditItem(item)}
                                                                icon="edit" color="light" />
                                                    }

                                                    {!disableDelete &&
                                                        <TableButton name="btnDelete" tooltip="Deletar" onClick={() => onDeleteItem(item)}
                                                                icon="delete" color="light" />
                                                    }
                                                </td>
                                            </tr>
                                            )
                                            }

                                            {itemsPerPage.length === 0 &&
                                                <NoEntity message="Nenhum resultado encontrado." />
                                            }
                                        </tbody>
                                    </Table>
                                </Col>
                                
                                <Col xs="12">
                                    {itemsPerPage.length !== 0 &&
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