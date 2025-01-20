export function applyInitialFormatting(value, currency) {
    if (currency === 'COP') {
        // Formateo inicial para COP (permite comas y puntos correctamente)
        let [integerPart, decimalPart] = value.split(',');

        // Formatear la parte entera con puntos cada tres dígitos
        integerPart = integerPart
            ? integerPart.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            : '';

        let formattedValue = integerPart;

        // Manejar la parte decimal
        if (decimalPart !== undefined) {
            formattedValue += ',' + decimalPart.replace(/[^\d]/g, ''); // Limitar a dígitos
        } else if (value.endsWith(',')) {
            formattedValue += ','; // Mantener la coma si está al final
        }

        return formattedValue;
    } else {
        // Formateo inicial para USD y EUR
        let [integerPart, decimalPart] = value.split(',');

        // Formatear la parte entera con puntos cada tres dígitos
        integerPart = integerPart
            ? integerPart.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
            : '';

        let formattedValue = integerPart;

        // Manejar la parte decimal
        if (decimalPart !== undefined) {
            formattedValue += ',' + decimalPart.replace(/[^\d]/g, ''); // Limitar a dígitos
        }

        return formattedValue;
    }
}