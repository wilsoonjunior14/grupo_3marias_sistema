function returnMessage(message) {
    return {message: message};
}

function validateRequired(array, field, fieldName) {
    if (!array[field] || array[field] === "") {
        return returnMessage(fieldName + " é obrigatório.");
    }
}

function validateRequiredString(array, field, maxlength, fieldName) {
    const requiredFieldValidation = validateRequired(array, field, fieldName);
    if (requiredFieldValidation) {
        return requiredFieldValidation;
    }
    if (array[field].length < 3 || array[field].length > maxlength) {
        return returnMessage(fieldName + " deve conter entre 3 e 255 caracteres.");
    }
    const regName = new RegExp(/^[a-z A-Z\u00C0-\u00FF]+$/g);
    if (!regName.test(array[field])) {
        return returnMessage(fieldName + " não é válido. Caracteres especiais ou números não são aceitos para este campo.");
    }
}

function validateRequiredStringWithoutPattern(array, field, maxlength, fieldName) {
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

export function validateEmail(array, field, fieldName) {
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

export function validatePhone(array, field, fieldName) {
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
    console.log(value);
    const regMoney = new RegExp(/^(\d+\.)+(\d+\,\d+)$/g);
    if (!regMoney.test(value)) {
        return returnMessage(fieldName + " não é válido. Tente utilizar o padrão XXX.XXX,XX");
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

function validateNumber(array, field, fieldName) {
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
    const clientRGValidation = validateRg(array, "rg", "RG do Cliente", true);
    if (clientRGValidation) {
        return clientRGValidation;
    }
    const clientRGOrganValidation = validateRgOrgan(array, "rg_organ", "Òrgão do RG do Cliente", true);
    if (clientRGOrganValidation) {
        return clientRGOrganValidation;
    }
    const clientRGDateValidation = validateDate(array, "rg_date", "Data de Emissão do RG do Cliente", true);
    if (clientRGDateValidation) {
        return clientRGDateValidation;
    }
    const clientSexValidation = validateRequired(array, "sex", "Sexo do Cliente");
    if (clientSexValidation) {
        return clientSexValidation;
    }
    const clientCPFValidation = validateCPF(array, "cpf", "CPF do Cliente");
    if (clientCPFValidation) {
        return clientCPFValidation;
    }
    const clientOcupationValidation = validateRequiredString(array, "ocupation", 255, "Profissão do Cliente");
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
    const clientNaturalityValidation = validateRequiredString(array, "naturality", 100, "Naturalidade do Cliente");
    if (clientNaturalityValidation) {
        return clientNaturalityValidation;
    }
    const clientNationalityValidation = validateRequiredString(array, "nationality", 100, "Nacionalidade do Cliente");
    if (clientNationalityValidation) {
        return clientNationalityValidation;
    }
    const clientStateValidation = validateRequired(array, "state", "Estado Civil do Cliente");
    if (clientStateValidation) {
        return clientStateValidation;
    }
    const clientBirthdateValidation = validateDate(array, "birthdate", "Data de Nascimento do Cliente", false);
    if (clientBirthdateValidation) {
        return clientBirthdateValidation;
    }
    const addressValidation = validateAddress(array);
    if (addressValidation) {
        return addressValidation;
    }
}