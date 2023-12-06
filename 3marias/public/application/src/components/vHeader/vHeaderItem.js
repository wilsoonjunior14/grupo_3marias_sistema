import Navbar from 'react-bootstrap/Navbar';
import { Tooltip } from "react-tooltip";

function VHeaderItem ({item, onClick}) {
    const location = window.location.pathname;
    var style = {
        color: "white"
    };
    if (location.indexOf(item.path) != -1) {
        style = {
            color: "red"
        }
    }

    return (
        <>
        <Navbar.Toggle
            key={item.id + Math.random()}
            onClick={onClick} 
            id={item.icon + "-item"} data-tooltip-id={item.icon + "-item-tooltip"} data-tooltip-content={item.name}
            style={{background: "none"}} aria-controls={`offcanvasNavbar-expand-false`}>
            <i style={{color: style.color, fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">{item.icon}</i>
        </Navbar.Toggle>
        <Tooltip key={item.id + Math.random()} style={{marginTop: "-10px"}} place="right" id={item.icon + "-item-tooltip"} />
        </>
    );
}

export default VHeaderItem;