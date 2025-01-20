import { abreviaturaUitlidadesHoras, abreviaturaUitlidadesHorasq } from "./abreviaturasHoras.js";
import { formatNumber } from "./formatNum.js";
export function calculateHorasDomiinicalButtons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable,row, diaKamati, diasKamati,tipoDiaNoMover,abrvLinea,unidad, cant,{costoApl, costoTrs}, factorOAcValue,factorMoValue, polizaAcValue, viaticosValue,costoFinalAlimentacion,costoFinalTransporte,cantPersonas, recargoFestivoDominicalValue, recargoExtraDominicalDiurnaValue){
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
    if (tipoDiaNoMover === 'Dominical' && unidad === 'Horas' && cant) {
            const descPersonal = row.querySelector('.select-nombreCotizacionesActividades-Class').value;
            tableToUse.querySelectorAll('tr').forEach(otherRow => {
                const cargo = otherRow.querySelector('td:first-child').textContent.trim();
                const valorDia = otherRow.querySelector('.tarifa_CargoGlobalClass1').value.replace(/\./g, '');
                let valorDiaCalculo = valorDia;
                let cantint = parseInt(cant);
                if (cantint === 8 && cargo === descPersonal) {
                    let costoDominical = parseFloat(valorDiaCalculo * recargoFestivoDominicalValue);
                    let costoDiaKamati = (parseFloat(costoDominical) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                    let costoDiaKamatiSin = (parseFloat(costoDominical)) * cantPersonas;
                    let costoDiasKamati = (parseFloat(costoDiaKamati) * (parseFloat(cantint) / 8));
                    if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                        diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                        diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                        diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant / 8)));
                        alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * cantPersonas));
                        transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * cantPersonas));
                        alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * cantPersonas));
                        transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * cantPersonas));
                        if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                            abreviaturaUitlidadesHoras(divContainerActividades, row, abrvLinea, costoDominical, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                        }
                    }
                } else if (cantint > 8 && cargo === descPersonal) {
                    let costoHora = parseFloat(valorDiaCalculo) / 8;
                    let costoDominical = parseFloat(valorDiaCalculo * recargoFestivoDominicalValue);
                    let costoExtraCantint = ((cantint - 8) * costoHora) * recargoExtraDominicalDiurnaValue;
                    let costoFinal = (parseFloat(costoExtraCantint) + parseFloat(costoDominical));
                    let costoDiaKamati = (parseFloat(costoFinal) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                    let costoDiaKamatiSin = (parseFloat(costoFinal)) * cantPersonas;
                    let costoDiasKamati = (parseFloat(costoDiaKamati) * (parseFloat(cantint) / 8));
                    if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                        diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                        diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                        diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant / 8)));
                        alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * cantPersonas));
                        transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * cantPersonas));
                        alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * cantPersonas));
                        transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * cantPersonas));
                        if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                            abreviaturaUitlidadesHoras(divContainerActividades, row, abrvLinea, costoFinal, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                        }
                    }
                } else if (cantint < 8 && cargo === descPersonal) {
                    let costoHora = parseFloat(valorDiaCalculo) / 8;
                    let costoHorasFin = parseFloat((costoHora * cantint) * recargoFestivoDominicalValue);
                    let costoDiaKamati = (parseFloat(costoHorasFin) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                    let costoDiaKamatiSin = (costoHorasFin * cantPersonas);
                    let costoDiasKamati = (parseFloat(costoDiaKamati));
                    if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                        diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                        diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                        diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin));
                        alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * cantPersonas));
                        transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * cantPersonas));
                        alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * cantPersonas));
                        transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * cantPersonas));
                        if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                            abreviaturaUitlidadesHorasq(divContainerActividades, row, abrvLinea, costoHorasFin, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                        }
                    }
                }
            });
        
    }
}