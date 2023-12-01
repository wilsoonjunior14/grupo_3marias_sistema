import React, {useState, useEffect, createRef, useReducer} from "react";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Table from 'react-bootstrap/Table';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import Header from '../../components/header/Header';
import Error from '../../components/error/Error';
import Success from '../../components/success/Success';
import '../../App.css';
import Loading from "../../components/loading/Loading";
import { performCustomRequest, performRequest } from "../../services/Api";
import { useParams } from "react-router-dom";
import CustomPagination from "../../components/table/Pagination";
import Select from 'react-select';
import CustomButton from "../../components/button/Button";
import './GroupRoles.css';
import CustomTable from "../../components/table/Table";

function GroupRoles() {

    const [loading, setLoading] = useState(false);
    const [loadingRoles, setLoadingRoles] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [group, setGroup] = useState({});
    const [roles, setRoles] = useState([]);
    const [itemsPerPage, setItemsPerPage] = useState([]);
    const parameters = useParams();

    const table = {
        fields: ["#", "Descrição", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "description", "created_at", "updated_at"]
    };

    useEffect(() => {
        setLoading(true);
        getGroup(parameters.id);
        getRoles();
    }, []);

    useEffect(() => {
        setDataPagination(1, 10, roles);
    }, [roles]);

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

    const getRoles = () => {
        setLoadingRoles(true);
        performRequest("GET", "/v1/roles")
        .then(successGetRoles)
        .catch(errorGet);
    };

    const successGetRoles = (response) => {
        setLoadingRoles(false);
        postProcessing(group, response.data);
    }
    

    const getGroup = (id) => {
        performRequest("GET", "/v1/groups/"+id)
        .then(successGet)
        .catch(errorGet);
    }

    const successGet = (response) => {
        setLoading(false);
        const group = response.data;
        setGroup(group);
        postProcessing(group, roles);
    }

    const errorGet = (response) => {
        setLoading(false);
        setLoadingRoles(false);
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
    }

    const postProcessing = (group, roles) => {
        if (loading || loadingRoles || !roles || roles.length === 0) {
            return;
        }
        
        roles.forEach((role) => {
            const hasMatch = group.roles.some((item) => {
                return item.role_id === role.id;
            });
            role.enabled = hasMatch;
        });

        setRoles(roles);
    }

    const addRole = (item) => {
        setLoading(true);

        const payload = {
            group_id: group.id,
            role_id: item.id
        };

        performRequest("POST", "/v1/roles/groups", payload)
        .then(successAddRoleToGroup)
        .catch(errorGet);
    }

    const successAddRoleToGroup = (response) => {
        getGroup(parameters.id);
    }

    const removeRole = (item) => {
        setLoading(true);
        const roleGroup = group.roles.find((role) => {
            return role.role_id === item.id
        });

        if (roleGroup) {
            performRequest("DELETE", "/v1/roles/groups/"+roleGroup.id)
            .then(successDeleteRole)
            .catch(errorGet);
        }
    }

    const successDeleteRole = (response) => {
        getGroup(parameters.id);
    }

    return (
        <>
            <Header />
            <br></br>
            <Container fluid>
                {!loading && httpError &&
                    <Error message={httpError.message} />
                }

                {!loading && httpSuccess &&
                    <Success message={httpSuccess.message} />
                }

                <Row>
                    <Col>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                <i className="material-icons float-left">group</i>
                                <p>Grupo de Usuário: {group.description}</p>
                                </Card.Title>

                                {!loading && 
                                <Row>
                                    <Col>
                                        Nesta página você pode adicionar ou remover permissões de um grupo de usuários.
                                    </Col>
                                </Row>
                                }

                                {loading && 
                                    <>
                                    <Col></Col>
                                    <Col style={{textAlign: 'center'}}>
                                        <Loading />
                                    </Col>
                                    <Col></Col>
                                    </>
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>

                <br></br>

                <Row>
                    <Col>
                        <Card>
                            <Card.Body>
                                <Card.Title>
                                <i className="material-icons float-left">lock</i>
                                <p>Gerenciar Permissões</p>
                                </Card.Title>

                                {!(!loadingRoles && !loading) && 
                                    <>
                                    <Col></Col>
                                    <Col style={{textAlign: 'center'}}>
                                        <Loading />
                                    </Col>
                                    <Col></Col>
                                    </>
                                }

                                {!loadingRoles && !loading &&
                                <>
                                <Row>
                                    <Col>
                                        <Table responsive striped>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Permissão</th>
                                                    <th>Status</th>
                                                    <th>Opções</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {itemsPerPage.length > 0 && itemsPerPage.map((item) => 
                                                <tr>
                                                    <td>{item.id}</td>
                                                    <td>{item.description}</td>
                                                    <td>
                                                        {item.enabled &&
                                                        <i className="material-icons text-success">check_circle</i>
                                                        }
                                                        {!item.enabled &&
                                                        <i className="material-icons text-danger">remove_circle</i>
                                                        }
                                                    </td>
                                                    <td>
                                                        <Row>
                                                            <Col xs={6} sm={1} md={6} lg={2}>
                                                                <CustomButton onClick={() => addRole(item)} disabled={item.enabled} name={"add"} tooltip={"Adicionar Permissão"} icon={"add"} color={"light"} />
                                                            </Col>
                                                            <Col xs={6} sm={1} md={6} lg={2}>
                                                                <CustomButton onClick={() => removeRole(item)} disabled={!item.enabled} name={"remove"} tooltip={"Remover Permissão"} icon={"remove"} color={"light"} />
                                                            </Col>
                                                        </Row>
                                                    </td>
                                                </tr>
                                                )}
                                                {itemsPerPage.length === 0 &&
                                                <tr>
                                                    <td colSpan="4">Nenhuma permissão encontrada.</td>
                                                </tr>
                                                }
                                            </tbody>
                                        </Table>
                                    </Col>
                                    <Col xs="12">
                                        {itemsPerPage.length !== 0 &&
                                        <CustomPagination data={roles} setDataCallback={setDataPagination} />
                                        }
                                    </Col>
                                </Row>
                                </>
                                }
                            </Card.Body>
                        </Card>
                    </Col>
                </Row>

            </Container>
        </>
    );
}

export default GroupRoles;