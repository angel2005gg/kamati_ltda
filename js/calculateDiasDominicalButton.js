import { formatNumber } from "./formatNum.js";
import { abreviaturaUitlidades } from "./abreviaturaUtilidadess.js";

//Método exportable al main para los calculos de jornada dominical cuando se selecciona por medio del select 
//un cargo y este recoge y compara el cargo y pone el valor para realizar los calculos de los
export function calculateDiasDominicalButtons(
    alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, row, diaKamati, diasKamati,tipoDiaNoMover,
    abrvLinea,unidad, cant,{costoApl, costoTrs}, factorOAcValue,factorMoValue, polizaAcValue
    , viaticosValue,costoFinalAlimentacion,costoFinalTransporte,cantPersonas, recargoFestivoDominicalValue
    ){
    // Obtener el valor del input hidden
    const hiddenInputValue = divContainerActividades.querySelector('.hiddenTableInput-actividades').value;
    console.log(hiddenInputValue);
    // Seleccionar la tabla adecuada
    let tableToUse;
    if (hiddenInputValue === "check") {
        tableToUse = divContainerActividades.querySelector('.hiddenTableInput-actividades .tbody_actividades-hiddenClass'); // Tabla oculta
    } else {
        tableToUse = otherTable; // Tabla pasada como parámetro
    } 
    if (tipoDiaNoMover === 'Dominical' && unidad === 'Dias' && cant) {
        
            const descPersonal = row.querySelector('.select-nombreCotizacionesActividades-Class').value;
            tableToUse.querySelectorAll('tr').forEach(otherRow => {
                const cargo = otherRow.querySelector('td:first-child').textContent.trim();
                const valorDia = otherRow.querySelector('.tarifa_CargoGlobalClass1').value.replace(/\./g, '');
                let valorDiaCalculo = valorDia;
                if (cargo === descPersonal) {
                    let costoDia = (parseFloat(valorDiaCalculo) * recargoFestivoDominicalValue);
                    let costoDiaKamati = (parseFloat(costoDia) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                    let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                    if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                        diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                        diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                        diasKamatiHidden.value = formatNumber(Math.round(((costoDia) * cantPersonas)*parseFloat(cant)));
                        alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                        transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                        alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                        transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                        if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                            abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoDia, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                        }
                    }
                }
            });
       
    }
}