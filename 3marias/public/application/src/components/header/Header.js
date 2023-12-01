import React from "react";
import Container from 'react-bootstrap/Container';
import Navbar from 'react-bootstrap/Navbar';
import Nav from 'react-bootstrap/Nav';
import NavDropdown from 'react-bootstrap/NavDropdown';
import '../../App.css'
import config from "../../config.json";
export const logo = config.url + "/img/logo.png";

export default function Header() {
    return (
        <>
          <Navbar collapseOnSelect expand="md" className="App-primary-color App-elevation" variant="light">
            <Container>
              <Navbar.Brand href="/home">
                <img
                  alt=""
                  src={logo}
                  width="60"
                  height="60"
                  className="d-inline-block align-top"
                />
              </Navbar.Brand>

              <Navbar.Toggle aria-controls="responsive-navbar-nav" />
              <Navbar.Collapse id="responsive-navbar-nav">
                <Nav className="me-auto">
                    <Nav.Link style={{color: "white"}} href="/home">Início</Nav.Link>
                    <NavDropdown style={{color: "white"}} title="Cadastros" id="basic-nav-dropdown">
                      <NavDropdown.Item href="/users">Usuários</NavDropdown.Item>
                      <NavDropdown.Item href="/groups">Grupos de Usuários</NavDropdown.Item>
                      <NavDropdown.Item href="/roles">Permissões</NavDropdown.Item>
                  </NavDropdown>
                </Nav>
                <Nav className="justify-content-end">
                    <NavDropdown title="Logado como: Mark Otto" id="basic-nav-dropdown">
                      <NavDropdown.Item href="#!">Minha Conta</NavDropdown.Item>
                      <NavDropdown.Item href="#!">Alterar Senha</NavDropdown.Item>
                      <NavDropdown.Divider />
                      <NavDropdown.Item href="/logout">
                        Sair
                      </NavDropdown.Item>
                  </NavDropdown>
                </Nav>
              </Navbar.Collapse>
              
            </Container>
          </Navbar>
        </>
      );
}