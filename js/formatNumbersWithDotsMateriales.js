export function formatNumberWithDots(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}