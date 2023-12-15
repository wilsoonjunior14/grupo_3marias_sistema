import React from "react";
import Button from 'react-bootstrap/Button';
import { Tooltip } from 'react-tooltip';

const TableButton = ({name, icon, color, tooltip, onClick, disabled}) => {
    return (
        <>
        {tooltip &&
            <>
            <Button disabled={disabled} style={{maxWidth: '80px', marginRight: 12}} key={name} onClick={onClick} variant={color} data-tooltip-id={name} data-tooltip-content={tooltip}>
                <i className="material-icons">{icon}</i>
            </Button>
            <Tooltip id={name} />
            </>
        }

        {!tooltip &&
            <>
            <Button disabled={disabled} size="lg" key={name} onClick={onClick} variant={color}>
            <i className="material-icons float-left">{icon}</i>
            {name}
            </Button>
            </>
        }
        </>
    );
}

export default TableButton;
