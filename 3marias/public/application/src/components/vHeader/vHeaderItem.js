import Navbar from 'react-bootstrap/Navbar';
import { Tooltip } from "react-tooltip";

function VHeaderItem ({item, onClick}) {
    return (
        <>
        <Navbar.Toggle
            onClick={onClick} 
            id={item.icon + "-item"} data-tooltip-id={item.icon + "-item-tooltip"} data-tooltip-content={item.name}
            style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-false`}>
            <i style={{color: "white", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">{item.icon}</i>
            </Navbar.Toggle>
        <Tooltip style={{marginTop: "-10px"}} place="right" id={item.icon + "-item-tooltip"} />
        </>
    );
}

export default VHeaderItem;