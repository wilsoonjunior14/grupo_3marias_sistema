export function formatDate(date) {
    if (!date) {
        return "";
    }
    if (date.length < 10) {
        return "";
    }
    const array = date.substring(0, 10).split("-");
    return array[2] + "/" + array[1] + "/" + array[0];
}

export function formatDateToServer(date) {
    if (!date) {
        return "";
    }
    const array = date.split("/");
    return array[2] + "-" + array[1] + "-" + array[0];
}

export function formatDoubleValue(value) {
    if (!value){
        return "";
    }
    if (value.toString().indexOf(",") === -1) {
        return value;
    }
    return Number(value.replace(".", "").replace(",", "."));
}

export function formatMoney(value) {
    if (!value) {
        return "";
    }
    const v = Number(value.replace(".", "").replace(",", "."));
    return (v).toLocaleString("pt-BR", {style: "currency", currency: "BRL", minimumFractionDigits: 2});
}

export function formatHour(hour) {
    if (!hour) {
        return "";
    }
    const time = hour.substring(11, 19)
    return time;
}

export function formatDateTime(datetime) {
    return formatDate(datetime) + " " + formatHour(datetime);
}

// TODO: it must be located on utils
export function getValueOfComplexField(item, complexField) {
    if (!item) {
        return "";
    }
    if (complexField.indexOf(".") === -1) {
        return item[complexField];
    }

    var array = complexField.split(".");
    // TODO: it must merge the another options instead of provide only array[1]
    const first = array[0];
    const last = array.slice(1).join(".")
    return getValueOfComplexField(item[first], last);
}