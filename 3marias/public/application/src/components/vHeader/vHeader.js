import React, { useState } from "react";
import '../../App.css';
import Container from 'react-bootstrap/Container';
import Row from 'react-bootstrap/Row';
import Col from 'react-bootstrap/Col';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import config from "../../config.json";
import { Tooltip } from "react-tooltip";
import VHeaderItem from "./vHeaderItem";
import VHeaderAdmin from "./vHeaderAdmin";
import VHeaderProposals from "./vHeaderProposals";
import VHeaderContracts from "./vHeaderContracts";
import VHeaderMoney from "./vHeaderMoney";
export const logo = config.url + "/img/logo.png";

function VHeader() {
    const dashboardColor = window.location.pathname.indexOf("home") == -1 ? "white" : "red"; 
    const [itemSelected, setItemSelected] = useState({id: 0, item: ""});
    const initialStateItems = [
        {
            id: 1,
            name: "Cadastros",
            icon: "add_circle_outline",
            path: "admin"
        },
        {
            id: 2,
            name: "Propostas",
            icon: "assignment",
            path: "proposals"
        },
        {
            id: 3,
            name: "Contratos",
            icon: "business_center",
            path: "contracts"
        },
        {
            id: 4,
            name: "Movimentações",
            icon: "attach_money",
            path: "money"
        }
    ];
    const [items, setItems] = useState(initialStateItems);

    const setItemShow = (item, value) => {
        items.forEach((i) => {
            if (item === i) {
                i.show = value;
            } else {
                i.show = false;
            }
        });
        setItems(items);
    }

    const onNavToggleClick = function(id, item) {
        setItemSelected({id: id, item: item});
    }

    const onToggleOptions = (item) => {
        onNavToggleClick(item.id, item.name);

        const toggleElement = document.getElementById("toggleOptions");
        const containerElement = document.getElementById("app-container");
        if (toggleElement) {
            setItemShow(item, true);
            const value = toggleElement.style.display;
            if (value === "" || value === "none") {
                toggleElement.style.display = "block";
                if (containerElement) {
                    containerElement.style.pointerEvents = "none";
                    containerElement.style.opacity = "0.5";
                }
            }
        }
    }

    const onToggleClose = () => {
        setItems(initialStateItems);
        const toggleElement = document.getElementById("toggleOptions");
        const containerElement = document.getElementById("app-container");
        if (toggleElement) {
            toggleElement.style.display = "none";
            if (containerElement) {
                containerElement.style.pointerEvents = "";
                containerElement.style.opacity = "1";
            }
        }
    }

    return (
        <>
        <div 
            id="toggleOptions" 
            onMouseLeave={onToggleClose}
            className="toggleOptions App-primary-color App-elevation" 
            style={{width: "300px", height: "100vh", 
            position: "fixed", top: 0, left: 76,
            paddingTop: 10, zIndex: 1000}}>
            <Container fluid>
                <Row style={{margin: 10}} className="toggleOptions-title">
                    <h3>{itemSelected.item}
                    <i onClick={onToggleClose}
                        style={{marginLeft: 10, border: "1px solid white", 
                        padding: 5, borderRadius: 4, cursor: "pointer", position: "absolute", top: 12, right: 12}} 
                        className="material-icons float-right">close</i>
                    </h3>
                    
                </Row>
                <Row>
                    <Col>
                    <Nav className="toggleOptions-nav">
                    {itemSelected.id === 1 &&
                        <VHeaderAdmin />
                    }
                    {itemSelected.id === 2 &&
                        <VHeaderProposals />
                    }
                    {itemSelected.id === 3 &&
                        <VHeaderContracts />
                    }
                    {itemSelected.id === 4 &&
                        <VHeaderMoney />
                    }
                    {/* {itemSelected.id === 2 &&
                        <VHeaderCompras />
                    }
                    {itemSelected.id === 3 &&
                        <VHeaderSuprimentos />
                    }
                    {itemSelected.id === 5 &&
                        <VHeaderEngenharia />
                    } */}
                    </Nav>
                    </Col>
                </Row>
            </Container>
        </div>

            {[false].map((expand) => (
                <Navbar style={{width: 80, height: "100vh", position: "fixed", marginLeft: -5}} 
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

                    <Nav.Link href="/home" style={{marginLeft: "-20px", paddingRight: "10px"}}
                        data-tooltip-id="dashboard-item-tooltip" data-tooltip-content="Dashboard">
                        <i style={{color: dashboardColor, fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">dashboard</i>
                    </Nav.Link>
                    <Tooltip style={{marginTop: "-5px"}} place="right" id="dashboard-item-tooltip" />

                    {items.map((item) => (
                        <VHeaderItem 
                            item={item} 
                            onClick={() => onToggleOptions(item)} />
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

                </Container>
                </Navbar>
            ))}
        </>
      );
}

export default VHeader;
