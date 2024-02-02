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
    return Number(value.replace(".", "").replace(",", "."));
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
    return getValueOfComplexField(item[array[0]], array[1]);
}