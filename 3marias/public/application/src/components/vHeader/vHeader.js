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
export const logo = config.url + "/img/logo.png";

function VHeader() {

    const [itemSelected, setItemSelected] = useState("");

    const onNavToggleClick = function(item) {
        console.log("it clicked.");
        setItemSelected(item);
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

                    <Navbar.Toggle 
                        id="dashboard-item" data-tooltip-id="dashboard-item-tooltip" data-tooltip-content="Dashboard"
                        onClick={() => onNavToggleClick("dashboard")} 
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "red", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">dashboard</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px", zIndex: 1000, display: "block", position: "absolute"}} place="right" id="dashboard-item-tooltip" />

                    <Navbar.Toggle 
                        id="business_center-item" data-tooltip-id="business_center-item-tooltip" data-tooltip-content="Administração"
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">business_center</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px"}} place="right" id="business_center-item-tooltip" />

                    <Navbar.Toggle 
                        id="shopping_cart-item" data-tooltip-id="shopping_cart-item-tooltip" data-tooltip-content="Compras"
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">shopping_cart</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px"}} place="right" id="shopping_cart-item-tooltip" />

                    <Navbar.Toggle 
                        id="store-item" data-tooltip-id="store-item-tooltip" data-tooltip-content="Suprimentos"
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">store</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px"}} place="right" id="store-item-tooltip" />

                    <Navbar.Toggle 
                        id="payment-item" data-tooltip-id="payment-item-tooltip" data-tooltip-content="Vendas"
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">payment</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px"}} place="right" id="payment-item-tooltip" />

                    <Navbar.Toggle 
                        id="attach_money-item" data-tooltip-id="attach_money-item-tooltip" data-tooltip-content="Financeiro"
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">attach_money</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px"}} place="right" id="attach_money-item-tooltip" />

                    <Navbar.Toggle 
                        id="people-item" data-tooltip-id="people-item-tooltip" data-tooltip-content="RH"
                        style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-${expand}`}>
                        <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">people</i>
                    </Navbar.Toggle>
                    <Tooltip style={{marginTop: "-10px"}} place="right" id="people-item-tooltip" />

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
                        style={{marginLeft: 80, background: "#0C3472"}}
                        placement="start">

                        <Offcanvas.Header closeButton>
                            <Offcanvas.Title id={`offcanvasNavbarLabel-expand-${expand}`}>
                            {itemSelected}
                            </Offcanvas.Title>
                        </Offcanvas.Header>

                        <Offcanvas.Body>
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
