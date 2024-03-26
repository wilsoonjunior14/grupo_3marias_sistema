import CurrencyInput from 'react-currency-input-field';
import './Input.css';
import InputErrorTag from "../error/InputErrorTag";

const CustomMoney = ({placeholder, name, value, required, onChange, disabled, defaultValue}) => {

    return (
        <>
        <label className='input-money-label' for={name}>
            {placeholder}
        </label>
        <CurrencyInput
        className='input-money form-control'
        id={name}
        name={name}
        defaultValue={defaultValue}
        required={required}
        disabled={disabled}
        decimalsLimit={2}
        value={value}
        disableAbbreviations={true}
        onChange={onChange}
        onValueChange={(value, name) => {}}
        allowNegativeValue={false}
        prefix=''
        groupSeparator='.'
        decimalSeparator=','
        fixedDecimalLength={2}
        style={{marginTop: "-25px", paddingTop: "15px"}}
        />
        <InputErrorTag 
        required={required} 
        placeholder={placeholder} 
        maxlength={100}
        minLength={3}
        value={value}></InputErrorTag>
        </>
    );
}

export default CustomMoney;