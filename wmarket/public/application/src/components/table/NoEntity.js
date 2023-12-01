import React from "react";

const NoEntity = ({message}) => {
    return (
        <>
        <tr>
            <td colSpan={6}>
                {message}
            </td>
        </tr>
        </>
    );
}

export default NoEntity;