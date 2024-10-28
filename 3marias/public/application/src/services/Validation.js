function returnMessage(message) {
    return {message: message};
}

export function validateRequired(array, field, fieldName) {
    if (!array[field] || array[field] === "") {
        return returnMessage(fieldName + " é obrigatório.");
    }
}

export function validateString(array, field, maxlength, fieldName) {
    if (!array[field]) {
        return;
    }
    if (array[field].length < 3 || array[field].length > maxlength) {
        return returnMessage(fieldName + " deve conter entre 3 e 255 caracteres.");
    }
    const regName = new RegExp(/^[a-z A-Z\u00C0-\u00FF]+$/g);
    if (!regName.test(array[field])) {
        return returnMessage(fieldName + " não é válido. Caracteres especiais ou números não são aceitos para este campo.");
    }
}

export function validateRequiredString(array, field, maxlength, fieldName) {
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    const validatedString = validateString(array, field, maxlength, fieldName);
    if (validatedString) {
        return validatedString;
    }
}

export function validateRequiredStringWithoutPattern(array, field, maxlength, fieldName) {
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    if (array[field].length < 3 || array[field].length > maxlength) {
        return returnMessage(fieldName + " deve conter entre 3 e 255 caracteres.");
    }
}

function validateStringWithoutPattern(array, field, maxlength, fieldName) {
    if (array[field] && (array[field].length < 3 || array[field].length > maxlength)) {
        return returnMessage(fieldName + " deve conter entre 3 e 255 caracteres.");
    }
}

function validateRg(array, field, fieldName, isRequired) {
    if (!isRequired && !array[field]) {
        return;
    }
    if (isRequired) {
        const requiredFieldValidation = validateRequired(array, field, fieldName);
        if (requiredFieldValidation) {
            return requiredFieldValidation;
        }
    }
    const value = array[field].replaceAll("_", "");
    const regRG = new RegExp(/^[\d]+$/g);
    if (!regRG.test(value)) {
        return returnMessage(fieldName + " não é válido. Tente utilizar somente números.");
    }
}

function validateRgOrgan(array, field, fieldName, isRequired) {
    if (!isRequired && !array[field]) {
        return;
    }
    if (isRequired) {
        const requiredFieldValidation = validateRequired(array, field, fieldName);
        if (requiredFieldValidation) {
            return requiredFieldValidation;
        }
    }
    const value = array[field];
    const regRGOrgan = new RegExp(/^[a-zA-Z]{3}\/[a-zA-Z]{2}$/g);
    if (!regRGOrgan.test(value)) {
        return returnMessage(fieldName + " não é válido. Tente utilizar o padrão XXX/XX.");
    }
}

export function validateDate(array, field, fieldName, isRequired) {
    if (!isRequired && !array[field]) {
        return;
    }
    if (isRequired) {
        const requiredFieldValidation = validateRequired(array, field, fieldName);
        if (requiredFieldValidation) {
            return requiredFieldValidation;
        }
    }
    if (!array[field] || array[field].toString().length === 0) {
        return;
    }
    const value = array[field];
    const regDate = new RegExp(/^\d{2}\/\d{2}\/\d{4}$/g);
    if (!regDate.test(value)) {
        return returnMessage(fieldName + " não é válido. Tente utilizar o padrão XX/XX/XXXX.");
    }
}

export function validateCPF(array, field, fieldName) {
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    const value = array[field];
    const regCPF = new RegExp(/^((\d{3}\.){2}\d{3}\-\d{2})$/g);
    if (!regCPF.test(value)) {
        return returnMessage(fieldName + " não é válido. Tente utilizar o padrão XXX.XXX.XXX-XX.");
    }
}

export function validateEmail(array, field, fieldName, isRequired) {
    if (!isRequired && !array[field]) {
        return;
    }
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    // TODO: remove this piece of code
    // const value = array[field];
    // const regCPF = new RegExp(/^(\w)+(\@|\-|\d+)+(\w)+\.(\w)+$/g);
    // if (!regCPF.test(value)) {
    //     return returnMessage(fieldName + " não é válido.");
    // }
}

export function validatePhone(array, field, fieldName, isRequired) {
    if (!isRequired && !array[field]) {
        return;
    }
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    const value = array[field];
    const regCPF = new RegExp(/^\(\d{2}\)\d{5}\-\d{4}$/g);
    if (!regCPF.test(value)) {
        return returnMessage(fieldName + " não é válido.");
    }
}

export function validateMoney(array, field, fieldName, isRequired) {
    if (!isRequired && !array[field]) {
        return;
    }
    if (isRequired) {
        const requiredFieldValidation = validateRequired(array, field, fieldName);
        if (requiredFieldValidation) {
            return requiredFieldValidation;
        }
    }
    if (!array[field] || array[field].toString().length === 0) {
        return;
    }
    const value = array[field];
    const regMoney = new RegExp(/^(\d+\.)+(\d+\,\d+)$|^(\d+)\,(\d+)$/g);
    if (!regMoney.test(value)) {
        return returnMessage(fieldName + " ("+value+") não é válido. Tente utilizar o padrão XXX.XXX,XX");
    }
}

export function validateMoneyWithoutAllPatterns(array, field, field2, fieldName) {
    const regMoney = new RegExp(/^(\d+\.)+(\d+)|(\d+)$/g);
    if (!regMoney.test(array[field])) {
        return returnMessage(fieldName + "("+array[field]+") não é válido.");
    }
    const regMoney2 = new RegExp(/^(\d+\.)+(\d+)$|^(\d+)$|^(\d+\,)+(\d+)$/g);
    if (!regMoney2.test(array[field2])) {
        return returnMessage(fieldName + "("+array[field2]+") não é válido...");
    }
}

function validateZipCode(array, field, fieldName) {
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    const regZipCode = new RegExp(/^(\d{5}\-\d{3})$/g);
    if (!regZipCode.test(array[field])) {
        return returnMessage(fieldName + " não é válido. Tente utilizar o padrão XXXXX-XXX");
    }
}

export function validateNumber(array, field, fieldName) {
    const regNumber = new RegExp(/^\d+$/g);
    if (!array[field]) {
        return;
    }
    if (!regNumber.test(array[field])) {
        return returnMessage(fieldName + " não é válido.");
    }
    if (array[field].toString().length > 4) {
        return returnMessage(fieldName + " não pode ser tão longo.");
    }
}

export function validateAddress(array) {
    const addressAddress = validateRequiredStringWithoutPattern(array, "address", 255, "Endereço");
    if (addressAddress) {
        return addressAddress;
    }
    const addressZipCode = validateZipCode(array, "zipcode", "CEP");
    if (addressZipCode) {
        return addressZipCode;
    }
    const addressCity = validateRequired(array, "city_id", "Cidade");
    if (addressCity) {
        return addressCity;
    }
    const addressNeighborhood = validateRequiredStringWithoutPattern(array, "neighborhood", 255, "Bairro");
    if (addressNeighborhood) {
        return addressNeighborhood;
    }
    const addressNumber = validateNumber(array, "number", "Número");
    if (addressNumber) {
        return addressNumber;
    }
    const addressComplement = validateStringWithoutPattern(array, "complement", 255, "Complemento");
    if (addressComplement) {
        return addressComplement;
    }
}

export function validateClient(array) {
    const clientNameValidation = validateRequiredString(array, "name", 255, "Nome do Cliente");
    if (clientNameValidation) {
        return clientNameValidation;
    }
    const clientCPFValidation = validateCPF(array, "cpf", "CPF do Cliente");
    if (clientCPFValidation) {
        return clientCPFValidation;
    }
    const clientRGValidation = validateRg(array, "rg", "RG do Cliente", false);
    if (clientRGValidation) {
        return clientRGValidation;
    }
    const clientRGOrganValidation = validateRgOrgan(array, "rg_organ", "Òrgão do RG do Cliente", false);
    if (clientRGOrganValidation) {
        return clientRGOrganValidation;
    }
    const clientRGDateValidation = validateDate(array, "rg_date", "Data de Emissão do RG do Cliente", false);
    if (clientRGDateValidation) {
        return clientRGDateValidation;
    }
    const clientOcupationValidation = validateString(array, "ocupation", 255, "Profissão do Cliente");
    if (clientOcupationValidation) {
        return clientOcupationValidation;
    }
    const clientEmailValidation = validateEmail(array, "email", "Email do Cliente");
    if (clientEmailValidation) {
        return clientEmailValidation;
    }
    const clientPhoneValidation = validatePhone(array, "phone", "Telefone do Cliente");
    if (clientPhoneValidation) {
        return clientPhoneValidation;
    }
    const clientSalaryValidation = validateMoney(array, "salary", "Renda Bruta do Cliente");
    if (clientSalaryValidation) {
        return clientSalaryValidation;
    }
    if (array["naturality"] && array["naturality"].length <= 0) {
        return returnMessage("Campo naturalidade está inválido.");
    }
    const clientNationalityValidation = validateString(array, "nationality", 100, "Nacionalidade do Cliente");
    if (clientNationalityValidation) {
        return clientNationalityValidation;
    }
    const clientBirthdateValidation = validateDate(array, "birthdate", "Data de Nascimento do Cliente", false);
    if (clientBirthdateValidation) {
        return clientBirthdateValidation;
    }

    // if there is some field related to address, we need validate the all address fields.
    if (!((array["address"] && array["address"].length > 0) || 
        (array["neighborhood"] && array["neighborhood"].length > 0) || 
        (array["zipcode"] && array["zipcode"].length > 0) || 
        (array["city_id"] && array["city_id"].length > 0) || 
        (array["complement"] && array["complement"].length > 0) || 
        (array["number"] && array["number"].length > 0))) {
            return;
    } 

    const addressValidation = validateAddress(array);
    if (addressValidation) {
        return addressValidation;
    }
}