import { formatNumber } from "./formatNum.js";


export function calculateViaticos(row, tableViaticos, divContainerActividades) {
    if (row) {
        try {
            const tableBodyDiv = divContainerActividades.querySelector(".tbodyActividades_Clas");
            const inputHidden = divContainerActividades.querySelector('.hiddenTableInput-actividades');
            let viaticoRows;
            if (inputHidden && inputHidden.value === 'check') { // Verifica si el valor es 'check'
                const tableHidden = divContainerActividades.querySelector('.tableViaticosHidden-ClassActividades .class-viaticosHidden');
                viaticoRows = tableHidden.querySelectorAll('tr');
            } else {
                viaticoRows = tableViaticos.querySelectorAll('tr');
            }

            if (viaticoRows.length >= 2) {
                // Función que actualiza los campos de costoAlimentacion y costoTransporte
                const updateCosts = () => {
                    const firstRowValue = viaticoRows[0].querySelector('td:nth-child(2) input').value.replace(/\./g, '');
                    let costoAlimentacionValue = formatNumber(Math.round(firstRowValue));
                    const secondRowValue = viaticoRows[1].querySelector('td:nth-child(2) input').value.replace(/\./g, '');
                    let costoTransporteValue = formatNumber(Math.round(secondRowValue));

                    // Actualiza los inputs
                    const costoAlimentacion = row.querySelector('.costo-alimentacion');
                    const costoTransporte = row.querySelector('.costo-transporte');
                    if (costoAlimentacion && costoTransporte) {
                        costoAlimentacion.value = costoAlimentacionValue;
                        costoTransporte.value = costoTransporteValue;
                    }
                };

                // Llama a la función inicialmente para establecer los valores
                updateCosts();
                

                // Añade los event listeners a los inputs para detectar cambios
                const firstRowInput = viaticoRows[0].querySelector('td:nth-child(2) input');
                const secondRowInput = viaticoRows[1].querySelector('td:nth-child(2) input');

                if (firstRowInput && secondRowInput) {
                    firstRowInput.addEventListener('input', updateCosts);
                    secondRowInput.addEventListener('input', updateCosts);
                }
            } else {
                console.log('No hay suficientes filas en la tabla.');
            }
            
        } catch (error) {
            console.error('Error en calculateViaticos:', error);
        }
    } else {
        console.log('La función calculateViaticos no se ejecutó porque el flag no está activado.');
    }
}