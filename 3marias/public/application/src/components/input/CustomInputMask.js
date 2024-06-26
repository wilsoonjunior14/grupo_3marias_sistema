import InputMask from "react-input-mask";
import InputErrorTag from "../error/InputErrorTag";

const CustomInputMask = ({name, placeholder, onChange, maxlength, value, required, mask, pattern}) => {
    return (
        <div className="form-floating">
            <InputMask 
                id={name + "Input"} 
                name={name}
                mask={mask} 
                className="form-control" 
                required={required} 
                maxLength={maxlength}
                pattern={pattern} 
                onChange={onChange}
                value={value} />
            <label htmlFor={name + "Input"}>{placeholder}</label>
            <InputErrorTag placeholder={placeholder} maxlength={maxlength} minLength={3} 
                required={required} value={value} />
        </div>
    );
}

export default CustomInputMask;