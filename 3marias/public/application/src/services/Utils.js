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
    console.log(form);
    const moneyFields = form.getElementsByClassName("input-money");
    if (moneyFields && moneyFields.length > 0) {
        for (var i=0; i<moneyFields.length; i++) {
            console.log(moneyFields[i].value);
            moneyFields[i].value = "";
            console.log(moneyFields[i].value);
        }
    }
}