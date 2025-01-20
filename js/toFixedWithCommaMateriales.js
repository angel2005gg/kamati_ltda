export function toFixedWithComma(num, digits, symbol) {
    num = parseFloat(num).toFixed(digits);
    num = num.replace('.', ',');
    return symbol + num;
}