import CurrencyInput from 'react-currency-input-field';
import './Input.css';

const CustomMoney = ({placeholder, name, value, required, onChange, disabled}) => {

    return (
        <>
        <label className='input-money-label' for={name}>
            {placeholder}
        </label>
        <CurrencyInput
        className='input-money'
        id={name}
        name={name}
        defaultValue={"0"}
        required={required}
        disabled={disabled}
        decimalsLimit={2}
        value={value}
        disableAbbreviations={true}
        onChange={onChange}
        onValueChange={(value, name) => console.log(value, name)}
        allowNegativeValue={false}
        prefix=''
        groupSeparator='.'
        decimalSeparator=','
        fixedDecimalLength={2}
        />
        </>
    );
}

export default CustomMoney;