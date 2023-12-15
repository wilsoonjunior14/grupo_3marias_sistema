import React, { useEffect, useState } from "react";
import FloatingLabel from "react-bootstrap/esm/FloatingLabel";
import Form from 'react-bootstrap/Form';
import { performRequest } from "../../services/Api";
import { useParams } from "react-router-dom";
import "@blueprintjs/core/lib/css/blueprint.css"; 
import { Button, MenuItem } from "@blueprintjs/core"; 
import { Select2 } from "@blueprintjs/select"; 
import "@blueprintjs/select/lib/css/blueprint-select.css"; 
import CustomInput from "./CustomInput";

const CustomSelect2 = ({placeholder, name, value, maxlength, required, onChange, endpoint, endpoint_field, data}) => {

    const [loading, setLoading] = useState(false);
    const [items, setItems] = useState([]);
    const [itemsToBePresented, setItemsToBePresented] = useState([]);
    const [item, setItem] = useState(value);
    const [placeholderName, setPlaceholderName] = useState(null);
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
        setPlaceholderName(null);
        setItems(response.data);
        filterItems(response.data);
    };

    const filterItems = (items) => {
        var newItems = [];
        items.forEach((i) => {
            const contains = newItems.some((s) => s === i[endpoint_field]);
            if (!contains) {
                newItems.push(i[endpoint_field]);
            }
        });
        setItemsToBePresented(newItems);
    }

    const errorGet = (response) => {
        setLoading(false);
    }

    const onSearch = (value) => {
        if (value.length === 0) {
            filterItems(items);
            return;
        }

        const itemsFound = items.filter((i) => {
            return i[endpoint_field].toString().toLowerCase().indexOf(value.toString().toLowerCase()) !== -1;
        });
        filterItems(itemsFound);
    }

    return (
        <FloatingLabel controlId={name + "Input"}
        className="mb-3">
            {!loading &&
            <Select2 
                items={itemsToBePresented} 
                itemRenderer={(val, itemProps) => { 
                    return ( 
                        <MenuItem 
                            key={val} 
                            text={val} 
                            onClick={(elm) => { 
                                setItem(elm.target.textContent);
                                const event = {target: {
                                    name: name,
                                    value: elm.target.textContent
                                }};
                                onChange(event);
                            }} 
                        /> 
                    ); 
                }}
                onQueryChange={(e) => onSearch(e)}
                onItemSelect={onChange} > 
                <CustomInput type="text" value={value} placeholder={placeholder} required={required} disabled={true} />
            </Select2>
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

export default CustomSelect2;
