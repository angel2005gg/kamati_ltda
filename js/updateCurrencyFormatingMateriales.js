import { applyNumberFormatting } from './applyNumberFormattingMateriales.js';
export async function updateCurrencyFormatting() {
        document.querySelectorAll('.select_trm_nu').forEach(select => {
            const row = select.closest('tr');
            const currencySymbol = row.querySelector('#span_trm');
            const numberInput = row.querySelector('.numberInput1');

            // Obtener la moneda seleccionada inicialmente
            const selectedCurrency = select.value;

            // Actualizar el símbolo de la moneda
            switch (selectedCurrency) {
                case 'USD':
                    currencySymbol.textContent = 'US$';
                    break;
                case 'EUR':applyNumberFormatting
                    currencySymbol.textContent = '€';
                    break;
                default:
                    currencySymbol.textContent = '$';
                    break;
            }
            // Aplicar formateo adecuado al cargar la página
            applyNumberFormatting(numberInput, selectedCurrency);
        });
    }