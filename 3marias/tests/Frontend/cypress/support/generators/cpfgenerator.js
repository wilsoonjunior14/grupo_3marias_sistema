export function gerarCpf() {
    const num1 = aleatorio();
    const num2 = aleatorio();
    const num3 = aleatorio();
    const dig1 = dig(num1, num2, num3);
    const dig2 = dig(num1, num2, num3, dig1);
    return `${num1}.${num2}.${num3}-${dig1}${dig2}`;
    }

    function dig(n1, n2, n3, n4) { 
    const nums = n1.split("").concat(n2.split(""), n3.split(""));
    if (n4 !== undefined){ 
        nums[9] = n4;
    }

    let x = 0;
    for (let i = (n4 !== undefined ? 11:10), j = 0; i >= 2; i--, j++) {
        x += parseInt(nums[j]) * i;
    }

    const y = x % 11;
    return y < 2 ? 0 : 11 - y; 
}

function aleatorio() {
    const aleat = Math.floor(Math.random() * 999);
    return ("" + aleat).padStart(3, '0'); 
}
