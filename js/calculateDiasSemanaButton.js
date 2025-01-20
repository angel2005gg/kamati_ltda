import { formatNumber } from "./formatNum.js";
import { abreviaturaUitlidades } from "./abreviaturaUtilidadess.js";
export function calculateDiasSemanaButtons(
    alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, row, diaKamati, diasKamati, tipoDiaNoMover,
    abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue,
    polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas
) {
    const hiddenInputValue = divContainerActividades.querySelector('.hiddenTableInput-actividades').value;
    console.log(hiddenInputValue);
    // Seleccionar la tabla adecuada
    let tableToUse;
    if (hiddenInputValue === "check") {
        tableToUse = divContainerActividades.querySelector('.hiddenTableInput-actividades .tbody_actividades-hiddenClass'); // Tabla oculta
    } else {
        tableToUse = otherTable; // Tabla pasada como parámetro
    }



    if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Dias' && cant) {

        // Iterar sobre las filas de la tabla (excluyendo las que no se deben mover)

        const descPersonal = row.querySelector('.select-nombreCotizacionesActividades-Class').value.trim(); // Obtener descripción del personal
        if (!descPersonal) return; // Saltar si descPersonal está vacío

        // Iterar sobre la tabla de referencia para encontrar coincidencias con la descripción del personal
        tableToUse.querySelectorAll('tr').forEach(otherRow => {
            const cargo = otherRow.querySelector('td:first-child').textContent.trim();

            // Verificar si el cargo coincide con la descripción del personal seleccionada
            if (cargo === descPersonal) {
                const valorDiaInput = otherRow.querySelector('.tarifa_CargoGlobalClass1');
                if (!valorDiaInput) return; // Saltar si el input no se encuentra

                // Reemplazar puntos y convertir valor a número
                const valorDia = valorDiaInput.value.replace(/\./g, '');
                const valorDiaCalculo = parseFloat(valorDia);

                // Validar que el valorDiaCalculo sea un número válido
                if (!isNaN(valorDiaCalculo)) {
                    // Realizar cálculos de costos
                    const costoDiaKamati = (valorDiaCalculo + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                    const costoDiasKamati = costoDiaKamati * parseFloat(cant);

                    // Validar que los costos calculados sean números válidos
                    if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                        diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                        diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                        diasKamatiHidden.value = formatNumber(Math.round(((valorDiaCalculo) * cantPersonas) * parseFloat(cant)));
                        alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                        transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                        alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                        transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                        // Condicionales para manejar diferentes valores de abrvLinea
                        if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                            abreviaturaUitlidades(
                                divContainerActividades, row, abrvLinea, valorDiaCalculo, factorOAcValue, polizaAcValue,
                                costoFinalAlimentacion, costoFinalTransporte, cantPersonas,
                                viaticosValue, factorMoValue, cant
                            );
                        }
                    }
                }
            }
        });

    }
}