import { formatNumberWithDots } from "./formatNumbersWithDotsMateriales.js";
import { obtenerValorSinFormato}from "./obtenerValorSinFormato.js";
import { toFixedWithComma } from "./toFixedWithCommaMateriales.js";
export async function calculateTotalsMaquinaria(divContainer, tableBody) {
    
    setTimeout(() => {
        let totalKamati = 0;
        let totalCliente = 0;
        // const tableBody = divContainer.querySelector('tbody');
    
        tableBody.querySelectorAll('tr').forEach(row => {
            const precioListaInput = row.querySelector('.precio-lista-input');
            const precioListaSinFormato = obtenerValorSinFormato(precioListaInput);
            
            const costKamatiTotal = row.querySelector('.cost-kamati-total');
            const costKamatiTotalSinFormato = obtenerValorSinFormato(costKamatiTotal);
            const valueTotalInput = row.querySelector('.value-total-input');
            const valueTotalInputSinFormato = obtenerValorSinFormato(valueTotalInput);
    
            // Verifica que el campo de precio lista tenga un valor antes de acumular
            if (precioListaSinFormato && parseFloat(precioListaSinFormato)) {
                if (costKamatiTotalSinFormato) {
                    totalKamati += parseFloat(costKamatiTotalSinFormato) || 0;
                }
    
                if (valueTotalInputSinFormato) {
                    totalCliente += parseFloat(valueTotalInputSinFormato) || 0;
                }
            }
        });
        divContainer.querySelector('.txtTotalKamatiMaquinaria').value = `$ ${formatNumberWithDots(toFixedWithComma(totalKamati, 2, ''))}`;
        divContainer.querySelector('.txtTotalClienteMaquinaria').value = `$ ${formatNumberWithDots(toFixedWithComma(totalCliente, 2, ''))}`;
        
    }, 200);
}