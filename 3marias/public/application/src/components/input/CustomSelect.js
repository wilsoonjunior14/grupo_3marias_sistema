import React, { useEffect, useState } from "react";
import FloatingLabel from "react-bootstrap/esm/FloatingLabel";
import Form from 'react-bootstrap/Form';
import { performRequest } from "../../services/Api";
import { useParams } from "react-router-dom";

const CustomSelect = ({placeholder, name, value, maxlength, required, onChange, endpoint, endpoint_field, data}) => {

    const [loading, setLoading] = useState(false);
    const [items, setItems] = useState([]);
    const parameters = useParams();

    useEffect(() => {
        if (items.length === 0 && !loading) {
            if (data) {
                setItems(data.map((item => {return {id: item, name: item}})));
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
        setItems(response.data);
    };

    const errorGet = (response) => {
        setLoading(false);
    }

    const getOptionField = (item) => {
        if (item.id === value) {
            return (
                <option selected key={item.id} value={item.id} label={item.name}>{item[endpoint_field]}</option>
            );
        } 
        return (
            <option key={item.id} value={item.id} label={item.name}>{item[endpoint_field]}</option>
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
            <div class="invalid-feedback">
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
                disabled="true"
                required={required} />
            }
        </FloatingLabel>
    );
}

export default CustomSelect;
