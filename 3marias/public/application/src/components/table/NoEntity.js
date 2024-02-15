import React from "react";

const NoEntity = ({message, count}) => {
    return (
        <>
        <tr>
            <td colSpan={count}>
                {message}
            </td>
        </tr>
        </>
    );
}

export default NoEntity;