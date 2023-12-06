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
    const dashboardColor = window.location.pathname.indexOf("home") == -1 ? "white" : "red"; 
    const [itemSelected, setItemSelected] = useState({id: 0, item: ""});
    const items = [
        {
            id: 1,
            name: "Administração",
            icon: "business_center",
            path: "admin"
        },
        {
            id: 2,
            name: "Compras",
            icon: "shopping_cart",
            path: "shopping"
        },
        {
            id: 3,
            name: "Suprimentos",
            icon: "store",
            path: "store"
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
                        <i style={{color: dashboardColor, fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">dashboard</i>
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
                            <b>{itemSelected.item}</b>
                            </Offcanvas.Title>
                        </Offcanvas.Header>

                        <Offcanvas.Body>
                            {itemSelected.id === 1 &&
                                <Nav className="justify-content-end flex-grow-1 pe-3">
                                    <Nav.Link href="/admin/clients">Clientes</Nav.Link>
                                    <Nav.Link href="/admin/users">Usuários</Nav.Link>
                                    <Nav.Link href="/admin/enterprises">Empresa</Nav.Link>
                                    <Nav.Link href="/admin/contractsModel">Modelos de Contrato</Nav.Link>
                                    <Nav className="justify-content-end flex-grow-1 pe-3">
                                        
                                        <NavDropdown
                                            title="Cadastros"
                                            className="App-primary-color"
                                            id={`offcanvasNavbarDropdown-expand-${expand}`}>
                                            <NavDropdown.Item href="/admin/documents">
                                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Tipos de Documentos
                                            </NavDropdown.Item>
                                            <NavDropdown.Divider />
                                            <NavDropdown.Item href="/admin/groups">
                                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Grupos de Usuários
                                            </NavDropdown.Item>
                                            <NavDropdown.Divider />
                                            <NavDropdown.Item href="/admin/roles">
                                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Permissões
                                            </NavDropdown.Item>
                                            <NavDropdown.Divider />
                                            <NavDropdown.Item href="/admin/states">
                                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Estados</NavDropdown.Item>
                                            <NavDropdown.Divider />
                                            <NavDropdown.Item href="/admin/cities">
                                                <i className="material-icons float-left">keyboard_arrow_right</i>
                                                Cidades
                                            </NavDropdown.Item>
                                            <NavDropdown.Divider />
                                        </NavDropdown>
                                    </Nav>
                                </Nav>
                            }
                            {itemSelected.id === 2 &&
                                <Nav className="justify-content-end flex-grow-1 pe-3">
                                    <Nav.Link href="/shopping">Compras</Nav.Link>
                                    <Nav.Link href="/shopping/orders">Ordens de Compra</Nav.Link>
                                    <Nav.Link href="/shopping/orders_services">Ordens de Serviço</Nav.Link>
                                    <Nav.Link href="/shopping/partners">Parceiros</Nav.Link>
                                    <Nav.Link href="/shopping/services">Serviços</Nav.Link>
                                    <Nav.Link href="/shopping/categories_services">Categorias de Serviços</Nav.Link>
                                </Nav>
                            }
                            {itemSelected.id === 3 &&
                                <Nav className="justify-content-end flex-grow-1 pe-3">
                                    <Nav.Link href="/store/stocks">Locais de Estoque</Nav.Link>
                                    <Nav.Link href="/store/products">Produtos</Nav.Link>
                                    <Nav.Link href="/store/equipments">Equipamentos</Nav.Link>
                                    <Nav.Link href="/store/entries">Entradas</Nav.Link>
                                    <Nav.Link href="/store/consum">Consumos</Nav.Link>
                                    <Nav.Link href="/store/shares">Transferências</Nav.Link>
                                    <Nav.Link href="/store/shares">Balanços</Nav.Link>
                                </Nav>
                            }
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
