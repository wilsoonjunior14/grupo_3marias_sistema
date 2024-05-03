import React from "react";

const InputErrorTag = ({required, value, placeholder, maxlength, minLength}) => {
    return (
        <>
        {required && (!value || value.length === 0) &&
        <div className="invalid-feedback">
            O campo {placeholder.replace("*", "")} é obrigatório.
        </div>
        }
        {value && value.length > 0 && value.length < minLength &&
        <div className="invalid-feedback">
            O campo {placeholder.replace("*", "")} deve conter mais que {minLength} caracteres.
        </div>
        }
        {value && value.length > maxlength &&
        <div className="invalid-feedback">
            O campo {placeholder.replace("*", "")} deve conter menos que {maxlength} caracteres.
        </div>
        }
        </>
    );
}

export default InputErrorTag;
