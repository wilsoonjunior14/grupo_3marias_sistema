import { formatDate } from "./Format";

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
            console.log(moneyFields[i].value);
            moneyFields[i].value = "";
            console.log(moneyFields[i].value);
        }
    }
}

export function getMoney(value) {
    const v = Number(value.replace(".", "").replace(",", "."));
    return (v).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
}

export function formatDateField(key, data) {
    if (key.indexOf("date") !== -1) {
        data[key] = formatDate(data[key]);
    } 
}

// TODO: REMEMBER TO REMOVE IT
// export function formatMoneyValue(key, data) {
//     if (key === "value" || key === "value_performed") {
//         setTimeout(() => {
//             console.log(document.getElementById(key+"Input"));
//             var element = document.getElementById(key+"Input");
//             if (element) {
//                 element.value = getMoney(data[key]);
//                 return;
//             }

//             var element = document.getElementById(key);
//             if (element) {
//                 element.value = data[key];
//                 return;
//             }
//         }, 3000);
//     }
// }

export function formatMoney(key, data) {
    if (key === "salary") {
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
