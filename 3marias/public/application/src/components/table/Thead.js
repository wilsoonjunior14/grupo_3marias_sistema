import React from "react";

const Thead = ({fields, amountOptions}) => {
    return (
        <>
            <thead>
                <tr>
                    {fields.map((field) => 
                        <th key={field}>{field}</th>
                    )}
                    <th colSpan={amountOptions}>Opções</th>
                </tr>
            </thead>
        </>
    );
};

export default Thead;