import React from "react";
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import pt from 'date-fns/locale/pt-BR';
import "./Input.css";

const CustomDatePicker = ({ name, required, placeholder, onChange, value }) => {
    const onChangeDatePicker = (date) => {
        const day = date.getDate() < 10 ? "0"+date.getDate() : date.getDate();
        const year = date.getFullYear();
        const month = date.getMonth() + 1 < 10 ? "0"+(date.getMonth() + 1) : (date.getMonth() + 1);
        onChange({
            target: {
                name: name,
                value: day + "/" + month + "/" + year
            }
        });
    }
    return (
        <>
        <DatePicker 
            key={name}
            locale={pt}
            value={value}
            required={required}
            placeholderText={placeholder}
            onChange={onChangeDatePicker} />
        </>
    );
}

export default CustomDatePicker;
