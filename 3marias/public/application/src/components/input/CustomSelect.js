import React, { useEffect, useState } from "react";
import FloatingLabel from "react-bootstrap/esm/FloatingLabel";
import Form from 'react-bootstrap/Form';
import { performRequest } from "../../services/Api";

const CustomSelect = ({placeholder, name, value, maxlength, required, onChange, endpoint, endpoint_field, data}) => {

    const [loading, setLoading] = useState(false);
    const [items, setItems] = useState([]);

    useEffect(() => {
        if (items.length === 0 && !loading) {
            if (data) {
                setItems(data.map((item => {return {id: item.toString(), name: item.toString().toUpperCase()}})));
            } else {
                loadItems();
            }
        }
    }, []);

    const loadItems = () => {
        setLoading(true);

        performRequest("GET", "/v1/"+endpoint)
        .then(successGet)
        .catch(errorGet);
    };

    const successGet = (response) => {
        setLoading(false);
        setItems(response.data.map((item => {return {id: item.id.toString(), name: item[endpoint_field].toString().toUpperCase()}})));
    };

    const errorGet = (response) => {
        setLoading(false);
    }

    const getOptionField = (item) => {
        if (value && item.id.toString() === value.toString()) {
            return (
                <option selected key={item.id} value={item.id.toString()} label={item.name.toString().toUpperCase()}>{item[endpoint_field]}</option>
            );
        } 
        return (
            <option key={item.id} value={item.id.toString()} label={item.name.toString().toUpperCase()}>{item[endpoint_field]}</option>
        );
    } 

    return (
        <FloatingLabel controlId={name + "Input"}
        label={placeholder}
        className="mb-3">
            {!loading &&
            <>
            <Form.Select
                name={name}
                required={required}
                onChange={onChange}
                aria-label="Default select example">
                <option value="">Selecione uma opção...</option>
                {items.map((item) => 
                    getOptionField(item)
                )}
            </Form.Select>
            <div className="invalid-feedback">
                Por favor, Selecione uma opção válida para o campo {placeholder.replace("*", "")}.
            </div>
            </>
            }
            {loading &&
                <Form.Control 
                type="text" 
                placeholder="Carregando..."
                name={name}
                maxLength={maxlength}
                value="Carregando..."
                disabled={true}
                required={required} />
            }
        </FloatingLabel>
    );
}

export default CustomSelect;
