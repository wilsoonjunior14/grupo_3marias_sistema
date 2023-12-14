import { Tooltip } from "react-tooltip";
import Nav from 'react-bootstrap/Nav';

function VHeaderItem ({item, onClick, onMouseOut}) {
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
        <Nav.Link key={item.id}
                onMouseOver={onClick}
                onMouseOut={onMouseOut}
                onClick={onClick} style={{marginLeft: "15px"}}
                data-tooltip-id={item.icon + "-item-tooltip"} data-tooltip-content={item.name}>
            {!item.show &&
                <i style={{color: style.color, fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">{item.icon}</i>
            }
            {item.show &&
                <>
                    <i style={{color: "orange", fontSize: "30px", marginBottom: "20px"}} className="material-icons float-left">{item.icon}</i>
                    <i className="material-icons float-right" style={{color: "orange", fontSize: 10}}>keyboard_arrow_right</i>
                </>
            }

        </Nav.Link>
        <Tooltip key={item.id + 1000} style={{marginTop: "-5px"}} place="right" id={item.icon + "-item-tooltip"} />
        </>
    );
}

export default VHeaderItem;