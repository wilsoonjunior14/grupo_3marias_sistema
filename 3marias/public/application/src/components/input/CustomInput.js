import React from "react";
import FloatingLabel from "react-bootstrap/esm/FloatingLabel";
import Form from 'react-bootstrap/Form';
import CustomInputFile from "./CustomInputFile";
import CustomSelect from "./CustomSelect";
import CustomInputMask from "./CustomInputMask";
import CustomSelect2 from "./CustomSelect2";
import CustomDatePicker from "./CustomDatePicker";
import CustomMoney from "./CustomMoney";
import InputErrorTag from "../error/InputErrorTag";

const CustomInput = ({placeholder, name, type, value, maxlength, required, onChange, endpoint, 
    endpoint_field, data, mask, maskPlaceholder, pattern, disabled, dateId}) => {
    if (type === "select") {
        return (
            <CustomSelect
                name={name}
                placeholder={placeholder}
                maxlength={maxlength}
                required={required} 
                onChange={onChange} 
                endpoint={endpoint}
                endpoint_field={endpoint_field}
                data={data}
                value={value}/>
        )
    }

    if (type === "select2") {
        return (
            <CustomSelect2
            name={name}
            placeholder={placeholder}
            maxlength={maxlength}
            required={required} 
            onChange={onChange} 
            endpoint={endpoint}
            endpoint_field={endpoint_field}
            data={data}
            value={value} />
        )
    }

    if (type === "mask") {
        return (
            <CustomInputMask 
                placeholder={placeholder}
                mask={mask}
                value={value}
                onChange={onChange}
                required={required}
                name={name}
                pattern={pattern}
                maskPlaceholder={maskPlaceholder} />
        );
    }

    if (type === "file") {
        return (
        <CustomInputFile 
            name={name}
            placeholder={placeholder}
            onChange={onChange}
            maxlength={maxlength}
            value={value}
            required={required} />
        );
    }

    if (type === "textarea") {
        return (<FloatingLabel
            controlId={name + "Input"}
            label={placeholder}
            className="mb-3">
            <Form.Control  
                placeholder={placeholder}
                name={name} 
                onChange={onChange}
                maxLength={maxlength}
                value={value}
                rows={5}
                required={required} 
                style={{height: 200}}
                as={type}/>
        </FloatingLabel>);
    }

    if (type === "date") {
        return (
            <CustomDatePicker 
                name={name} 
                placeholder={placeholder}
                required={required} 
                onChange={onChange} 
                value={value}
                id={dateId} />
        );
    }

    if (type === "money") {
        return (
            <CustomMoney 
                name={name} 
                onChange={onChange}
                placeholder={placeholder}
                required={required} 
                disabled={disabled}
                defaultValue={value} />
        );
    }

    return (<FloatingLabel
        controlId={name + "Input"}
        label={placeholder}
        className="mb-3">
        <Form.Control 
            type={type} 
            placeholder={placeholder}
            name={name} 
            pattern={pattern}
            onChange={onChange}
            minLength={3}
            maxLength={maxlength}
            value={value}
            required={required}
            readOnly={disabled} />
        <InputErrorTag 
            required={required} 
            placeholder={placeholder} 
            maxlength={maxlength}
            minLength={3}
            value={value}></InputErrorTag>
    </FloatingLabel>);
}

export default CustomInput;
