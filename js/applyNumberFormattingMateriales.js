import {applyInitialFormatting} from './applyInitialFormattingMateriales.js';
export function applyNumberFormatting(textarea, currency) {
    textarea.addEventListener('input', function () {
        let value = this.value;
        let cursorPosition = this.selectionStart;
        let formattedValue;

        if (currency === 'COP') {
            // Separar la parte entera y decimal
            let [integerPart, decimalPart] = value.split(',');

            // Formatear la parte entera con puntos cada 3 dígitos
            integerPart = integerPart
                ? integerPart.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                : '';

            // Agregar la coma y la parte decimal si existe
            formattedValue = integerPart;
            if (decimalPart !== undefined) {
                decimalPart = decimalPart.replace(/[^\d]/g, ''); // Solo permitir dígitos en la parte decimal
                formattedValue += ',' + decimalPart;
            } else if (value.endsWith(',')) {
                formattedValue += ','; // Mantener la coma si está al final
            }
        } else {
            // Formateo para USD y EUR (puntos para miles y coma para decimales)
            let [integerPart, decimalPart] = value.split(',');
            integerPart = integerPart
                ? integerPart.replace(/[^\d]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                : '';
            formattedValue = integerPart;

            if (decimalPart !== undefined) {
                formattedValue += ',' + decimalPart.replace(/[^\d]/g, ''); // Solo permitir dígitos en la parte decimal
            } else if (value.endsWith(',')) {
                formattedValue += ','; // Mantener la coma si está al final
            }
        }

        // Actualizar el valor del textarea
        this.value = formattedValue;

        // Restaurar la posición del cursor
        let newCursorPosition = cursorPosition;
        if (value.length > formattedValue.length) {
            newCursorPosition -= (value.length - formattedValue.length);
        } else if (value.length < formattedValue.length) {
            newCursorPosition += (formattedValue.length - value.length);
        }
        this.setSelectionRange(newCursorPosition, newCursorPosition);
    });

    // Aplicar formato inicial al cargar
    textarea.value = applyInitialFormatting(textarea.value, currency);
}