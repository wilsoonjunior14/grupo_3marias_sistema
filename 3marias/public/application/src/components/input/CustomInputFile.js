import React from "react";
import FloatingLabel from "react-bootstrap/esm/FloatingLabel";
import Form from 'react-bootstrap/Form';

const CustomInputFile = ({placeholder, name, value, maxlength, required, onChange}) => {
    return (
        <>
        <FloatingLabel
            controlId={name + "Input"}
            label={placeholder}
            className="mb-3">
            <Form.Control 
                type="file" 
                placeholder={placeholder}
                name={name} 
                onChange={onChange}
                maxLength={maxlength}
                value={value}
                required={required} />
        </FloatingLabel>
        </>
    );
}

export default CustomInputFile;
