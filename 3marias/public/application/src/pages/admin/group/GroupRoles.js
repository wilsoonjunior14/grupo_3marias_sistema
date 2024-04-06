import React, {useState, useEffect, useReducer } from "react";
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Card from 'react-bootstrap/Card';
import VHeader from '../../../components/vHeader/vHeader';
import Error from '../../../components/error/Error';
import Success from '../../../components/success/Success';
import '../../../App.css';
import Loading from "../../../components/loading/Loading";
import { performRequest } from "../../../services/Api";
import { useParams } from "react-router-dom";
import CustomButton from "../../../components/button/Button";
import CustomInput from "../../../components/input/CustomInput";
import './GroupRoles.css';
import Table from 'react-bootstrap/Table';
import Form from 'react-bootstrap/Form';
import TableButton from "../../../components/button/TableButton";

function GroupRoles() {

    const [loading, setLoading] = useState(false);
    const [loadingRoles, setLoadingRoles] = useState(false);
    const [httpError, setHttpError] = useState(null);
    const [httpSuccess, setHttpSuccess] = useState(null);
    const [group, setGroup] = useState({roles: []});
    const [roles, setRoles] = useState([]);
    const [initialState, setInitialState] = useState({});
    const parameters = useParams();

    const table = {
        fields: ["#", "Descrição", "Data de Criação", "Data de Alteração"],
        amountOptions: 1,
        bodyFields: ["id", "description", "created_at", "updated_at"]
    };

    const reducer = (state, action) => {
        if (action.type === "reset") {
            return initialState;
        }
        if (action.type === "data") {
            return action.data;
        }
        const result = { ...state };
        result[action.type] = action.value;

        return result;
    };
    const [state, dispatch] = useReducer(reducer, initialState);

    useEffect(() => {
        setLoading(true);
        getGroup(parameters.id);
        getRoles();
    }, []);

    const getRoles = () => {
        performRequest("GET", "/v1/roles")
        .then(successGetRoles)
        .catch(errorGet);
    }

    const successGetRoles = (res) => {
        setRoles(res.data);
    }

    const getGroup = (id) => {
        setLoading(true);
        setHttpError(null);

        performRequest("GET", "/v1/groups/"+id)
        .then(successGet)
        .catch(errorGet);
    }

    const successGet = (response) => {
        setLoading(false);
        const group = response.data;
        setGroup(group);
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

    const onChangeField = (e) => {
        const { name, value } = e.target;
        dispatch({ type: name, value });
    };

    const onSubmit = () => {
        setLoading(true);
        setHttpError(null);
        setHttpSuccess(null);

        if (!state.role_description) {
            setHttpError({message: "Permissão não informada."});
            return;
        }
        const roleSelected = roles.filter((r) => r.description === state.role_description);
        if (!roleSelected || roleSelected.length === 0) {
            setHttpError({message: "Permissão não encontrada."});
            return;
        }
        const payload = {
            group_id: group.id,
            role_id: roleSelected[0].id
        };

        performRequest("POST", "/v1/roles/groups", payload)
        .then(successAddRoleToGroup)
        .catch(errorGet);
    }

    const successAddRoleToGroup = (res) => {
        setHttpSuccess({message: "Permissão adicionada com sucesso!"});
        dispatch({ type: "reset" });
        setLoading(false);
        getGroup(parameters.id);
    }

    const onDeleteItem = (item) => {
        setLoading(true);

        performRequest("DELETE", "/v1/roles/groups/" + item.id)
        .then(successDeleteRoleGroup)
        .catch(errorGet);
    }

    const successDeleteRoleGroup = (res) => {
        setHttpSuccess({message: "Permissão removida com sucesso!"});
        setLoading(false);
        getGroup(parameters.id);
    }

    return (
        <>
            <VHeader />
            <Container id='app-container' style={{marginLeft: 90, width: "calc(100% - 100px)"}} fluid>
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
                                <i className="material-icons float-left">add</i>
                                <p>Adicionar Permissão</p>
                                </Card.Title>

                                {!loading &&
                                <Form id='addRoleForm' onSubmit={onSubmit} noValidate={true} > 
                                    <Row>
                                        <Col xs={4}>
                                            <CustomInput key="role_description" type="select2"
                                                endpoint={"roles"} endpoint_field={"description"}
                                                placeholder="Permissão *" name="role_description" 
                                                required={true}
                                                value={state.role_description}
                                                onChange={onChangeField} />
                                        </Col>
                                        <Col xs={2}>
                                            <CustomButton color="success"
                                                onClick={onSubmit} name={"+ Adicionar"} />
                                        </Col>
                                    </Row>
                                </Form>
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
                                <p>Lista de Permissões</p>
                                </Card.Title>
                                {loading &&
                                    <>
                                    <Col></Col>
                                    <Col style={{textAlign: 'center'}}>
                                        <Loading />
                                    </Col>
                                    <Col></Col>
                                    </>
                                }
                                {!loading &&
                                <Row>
                                    <Col>
                                        <Table responsive striped>
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Descrição</th>
                                                    <th>Opções</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {group.roles.map((role) => 
                                                    <tr>
                                                        <td>{role.id}</td>
                                                        <td>{role.role.description}</td>
                                                        <td>
                                                            <TableButton name="btnDelete" tooltip="Deletar" onClick={() => onDeleteItem(role)}
                                                                icon="delete" color="light" />
                                                        </td>
                                                    </tr>
                                                )}
                                            </tbody>
                                        </Table>
                                    </Col>
                                </Row>
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