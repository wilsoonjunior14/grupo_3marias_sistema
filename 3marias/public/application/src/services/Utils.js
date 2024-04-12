import { formatDate, formatDateToServer, formatDoubleValue } from "./Format";
import MD5 from "crypto-js/md5";

export function validateForm (id) {
    // Form Validation
    const form = document.getElementById(id);
    if (!form.checkValidity()) {
        form.classList.add("was-validated");
        return false;
    } else {
        form.classList.remove("was-validated");
        return true;
    }
}

export function clearForm(formId) {
    const form = document.getElementById(formId);
    form.reset();
    const moneyFields = form.getElementsByClassName("input-money");
    if (moneyFields && moneyFields.length > 0) {
        for (var i=0; i<moneyFields.length; i++) {
            moneyFields[i].value = "";
        }
    }
}

export function getMoney(value) {
    if (!value) {return "";}
    const v = Number(value.replace(".", "").replace(",", "."));
    return (v).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
}

export function formatDateField(key, data) {
    if (key.indexOf("date") !== -1) {
        data[key] = formatDate(data[key]);
    } 
}

export function formatMoney(key, data) {
    if (key === "salary" || key === "value") {
        data[key] = Number(data[key]);
    }
}

export function formatDataFrontend(data) {
    Object.keys(data).forEach((key) => {
        formatDateField(key, data);
        formatMoney(key, data);
    });
    return data;
}

export function processDataBefore(data) {
    const keys = Object.keys(data);
    keys.forEach((key) => {
        if (key === "birthdate" || key.indexOf("date") !== -1) {
            data[key] = formatDateToServer(data[key]);
        }
        if (key.indexOf("password") !== -1) {
            data[key] = MD5(data[key]).toString();
        }
        if (key.indexOf("value") !== -1 || key.indexOf("value_performed") !== -1) {
            data[key] = formatDoubleValue(data[key]);
        }
        if (!data[key] || data[key] === null || data[key] === undefined) {
            delete data[key];
        }
    });

    return data;
}
