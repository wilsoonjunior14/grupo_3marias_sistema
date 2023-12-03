import React, { useState } from "react";
import '../../App.css';
import Button from 'react-bootstrap/Button';
import Container from 'react-bootstrap/Container';
import Form from 'react-bootstrap/Form';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';
import Offcanvas from 'react-bootstrap/Offcanvas';
import config from "../../config.json";
import { Tooltip } from "react-tooltip";
import VHeaderItem from "./vHeaderItem";
export const logo = config.url + "/img/logo.png";

function VHeader() {

    const [itemSelected, setItemSelected] = useState({id: 0, item: ""});
    const items = [
        {
            id: 1,
            name: "Administração",
            icon: "business_center"
        },
        {
            id: 2,
            name: "Compras",
            icon: "shopping_cart"
        },
        {
            id: 3,
            name: "Suprimentos",
            icon: "store"
        },
        {
            id: 4,
            name: "Vendas",
            icon: "payment"
        },
        {
            id: 5,
            name: "Engenharia",
            icon: "memory"
        },
        {
            id: 6,
            name: "Financeiro",
            icon: "attach_money"
        },        {
            id: 7,
            name: "RH",
            icon: "people"
        }
    ];

    const onNavToggleClick = function(id, item) {
        setItemSelected({id: id, item: item});
    }

    return (
        <>
            {[false].map((expand) => (
                <Navbar style={{width: 80, height: "100vh", position: "fixed"}} 
                        key={expand} 
                        expand={expand} 
                        className="App-primary-color App-secondary-color App-elevation Over-zIndex">
                <Container>

                    <Navbar.Brand href="/home">
                        <img
                            alt=""
                            src={logo}
                            width="60"
                            height="60"
                            style={{marginBottom: "20px", position: "absolute", top: 20, left: 10}}
                            className="d-inline-block align-top"
                        />
                    </Navbar.Brand>

                    <Nav.Link href="/home" style={{marginRight: "10px"}}
                        data-tooltip-id="dashboard-item-tooltip" data-tooltip-content="Dashboard">
                        <i style={{color: "red", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">dashboard</i>
                    </Nav.Link>
                    <Tooltip style={{marginTop: "-5px"}} place="right" id="dashboard-item-tooltip" />

                    {items.map((item) => (
                        <VHeaderItem item={item} onClick={() => onNavToggleClick(item.id, item.name)} />
                    ))}

                    <Nav style={{position: "absolute", bottom: 15, left: 25}}>
                        <Nav.Link href="/home" data-tooltip-id="account_circle-item-tooltip" data-tooltip-content="Minha Conta">
                            <i style={{color: "white", fontSize: "30px", marginBottom: "10px"}} className="material-icons float-left">account_circle</i>
                        </Nav.Link>
                        <Tooltip style={{marginTop: "-5px"}} place="right" id="account_circle-item-tooltip" />

                        <Nav.Link href="/home" data-tooltip-id="exit_to_app-item-tooltip" data-tooltip-content="Sair">
                            <i style={{color: "white", fontSize: "30px", marginBottom: "10px"}} className="material-icons float-left">exit_to_app</i>
                        </Nav.Link>
                        <Tooltip style={{marginTop: "-5px"}} place="right" id="exit_to_app-item-tooltip" />
                    </Nav>

                    <Navbar.Offcanvas
                        id={`offcanvasNavbar-expand-${expand}`}
                        aria-labelledby={`offcanvasNavbarLabel-expand-${expand}`}
                        style={{marginLeft: 80, background: "#0C3472", color: "white"}}
                        placement="start">

                        <Offcanvas.Header closeButton>
                            <Offcanvas.Title id={`offcanvasNavbarLabel-expand-${expand}`}>
                            {itemSelected.item}
                            </Offcanvas.Title>
                        </Offcanvas.Header>

                        <Offcanvas.Body>
                            {itemSelected.id === 1 &&
                                <Nav className="justify-content-end flex-grow-1 pe-3">
                                    <Nav.Link href="/admin/users">Usuários</Nav.Link>
                                    <Nav.Link href="/admin/enterprises">Empresa</Nav.Link>
                                    <Nav.Link href="/admin/documents">Tipos de Documentos</Nav.Link>
                                    <Nav.Link href="/admin/contractsModel">Modelos de Contrato</Nav.Link>
                                    <Nav.Link href="/admin/groups">Grupos de Usuários</Nav.Link>
                                    <Nav.Link href="/admin/roles">Permissões</Nav.Link>
                                </Nav>
                            }
                            {itemSelected.id === 2 &&
                                <Nav className="justify-content-end flex-grow-1 pe-3">
                                    <Nav.Link href="/home">Compras</Nav.Link>
                                    <Nav.Link href="/home">Ordens de Compra</Nav.Link>
                                    <Nav.Link href="/home">Ordens de Serviço</Nav.Link>
                                    <Nav.Link href="/home">Parceiros</Nav.Link>
                                    <Nav.Link href="/home">Serviços</Nav.Link>
                                    <Nav.Link href="/home">Categorias de Serviços</Nav.Link>
                                </Nav>
                            }
                            <Nav className="justify-content-end flex-grow-1 pe-3">
                            <Nav.Link href="#action1">Home</Nav.Link>
                            <Nav.Link href="#action2">Link</Nav.Link>
                            <NavDropdown
                                title="Dropdown"
                                id={`offcanvasNavbarDropdown-expand-${expand}`}>
                                <NavDropdown.Item href="#action3">Action</NavDropdown.Item>
                                <NavDropdown.Item href="#action4">
                                Another action
                                </NavDropdown.Item>
                                <NavDropdown.Divider />
                                <NavDropdown.Item href="#action5">
                                Something else here
                                </NavDropdown.Item>
                            </NavDropdown>
                            </Nav>
                            <Form className="d-flex">
                            <Form.Control
                                type="search"
                                placeholder="Search"
                                className="me-2"
                                aria-label="Search"
                            />
                            <Button variant="outline-success">Search</Button>
                            </Form>
                        </Offcanvas.Body>
                    
                    </Navbar.Offcanvas>

                </Container>
                </Navbar>
            ))}
        </>
      );
}

export default VHeader;
