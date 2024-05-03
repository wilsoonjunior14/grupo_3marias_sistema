function generateRandomString(chars, length) {
    let string = "";
    for (let i = 0; i < length; i ++) {
        string = string + "" + chars[parseInt(Math.random() * (chars.length - 1))];
    }
    return string;
}

export function generateRandomLetters(length) {
    const chars = "abcdefghijklmnopqrstuvxwyzABCDEFGHIJKLMNOPQRSTUVXWYZ";
    return generateRandomString(chars, length);
}

export function generateRandomNumbers(length) {
    const chars = "123456789";
    return generateRandomString(chars, length);
}
