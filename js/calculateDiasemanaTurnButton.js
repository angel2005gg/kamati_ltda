import { formatNumber } from "./formatNum.js";
import { abreviaturaUitlidades } from "./abreviaturaUtilidadess.js";

export function calculateDiassemanaTurnButton(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, otherTable, row, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, {costoApl, costoTrs}, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, recargoNocturnoValue, extraDiurnaValue, recargoNocturnoExtraValue, costoFinalAlimentacion, costoFinalTransporte, totalTiempo, jornada, horasDiurnas, horasNocturnas, cantPersonas) {

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

    if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Turn' && cant) {
        const descPersonal = row.querySelector('.select-nombreCotizacionesActividades-Class').value;

        tableToUse.querySelectorAll('tr').forEach(otherRow => {
            const cargo = otherRow.querySelector('td:first-child').textContent.trim();
            const valorDia = otherRow.querySelector('.tarifa_CargoGlobalClass1').value.replace(/\./g, '');

            let valorDiaCalculo = valorDia;
            let horasCalculadas = 0;

            // Caso jornada diurna de 8 horas
            if (totalTiempo === 8 && jornada === 'JornadaDiurna' && cargo === descPersonal) {
                let costoDiaKamati = (parseFloat(valorDiaCalculo) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));

                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((valorDiaCalculo) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, valorDiaCalculo, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }

            } else if ((totalTiempo > 8 && totalTiempo <= 15) && jornada === 'JornadaDiurna' && cargo === descPersonal) {
                // Caso jornada diurna con horas extra
                horasCalculadas = totalTiempo - 8;
                let costoHorasExtra = parseFloat(valorDiaCalculo) / 8;
                let costoHorasExtraRecargo = (costoHorasExtra * horasCalculadas * extraDiurnaValue);
                let costoFinal = parseFloat(valorDiaCalculo) + costoHorasExtraRecargo;

                let costoDiaKamati = ((parseFloat(costoFinal) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));

                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoFinal) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoFinal, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
            }
            
        } else if (totalTiempo < 8 && jornada === 'JornadaDiurna' && cargo === descPersonal) {
            // Caso jornada diurna con menos de 8 horas
                let costoHoras = parseFloat(valorDiaCalculo) / 8;
                let costoHorasExtraRecargo = (costoHoras * totalTiempo);
                let costoDiaKamati = ((parseFloat(costoHorasExtraRecargo) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoHorasExtraRecargo) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasExtraRecargo, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que la jornada es nocturna y son 8 horas laborales
            else if ((totalTiempo === 8 && totalTiempo < 9) && jornada === 'JornadaNocturna' && cargo === descPersonal) {
                let costoRecargoNocturno = (parseFloat(valorDiaCalculo) * recargoNocturnoValue);
                let costoDiaKamati = ((parseFloat(costoRecargoNocturno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoRecargoNocturno) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que la jornada es nocturna y supera las 8 horas con la limitación de nueve horas por el tiempo que dura la jornada nocturna 9pm - 6am
            else if (totalTiempo === 9 && jornada === 'JornadaNocturna' && cargo === descPersonal) {
                let costoRecargoNocturno = (parseFloat(valorDiaCalculo) * recargoNocturnoValue) + ((parseFloat(valorDiaCalculo) / 8) * recargoNocturnoExtraValue);
                let costoDiaKamati = ((parseFloat(costoRecargoNocturno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoRecargoNocturno) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que las horas norcturnas sean menos de 8
            else if (totalTiempo < 8 && jornada === 'JornadaNocturna' && cargo === descPersonal) {
                //divide el costo externo por las 8 horas laborales
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoHorasRecargoNocturno = (costoHoras * totalTiempo) * recargoNocturnoValue;
                let costoDiaKamati = ((parseFloat(costoHorasRecargoNocturno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoHorasRecargoNocturno) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que la jornada es mixta y son 8 horas laborales
            else if (totalTiempo === 8 && jornada === 'JornadaMixta' && cargo === descPersonal) {
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoDiurnasMixtas = horasDiurnas * costoHoras;
                let costoNocturnasMixtas = (horasNocturnas * costoHoras) * recargoNocturnoValue;
                let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas));
                let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoHorasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que son mas de 8 horas laborales es jonada mixta, las horas diurnas son mas de 8 y y las nocturnas son mas de 1 y menos de 9 
            else if ((totalTiempo > 8 && horasDiurnas > 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadaMixta' && cargo === descPersonal) {
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoDiurnasMixtas = ((horasDiurnas - 8) * costoHoras) * extraDiurnaValue;
                let costoNocturnasMixtas = (horasNocturnas * costoHoras) * recargoNocturnoExtraValue;
                let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas) + parseFloat(valorDiaCalculo));
                let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoHorasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que son mas de 8 horas laborales pero las horas diurnas son menos de 8 y las nocturnas son mas de 1 y menos de 9 
            else if ((totalTiempo > 8 && horasDiurnas <= 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadaMixta' && cargo === descPersonal) {
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoDiurnasMixtas = (horasDiurnas * costoHoras);
                let entreDiurnas = (8 - horasDiurnas);
                let costoNocturnas = (entreDiurnas * costoHoras) * recargoNocturnoValue;
                let extrasNocturnas = horasNocturnas - entreDiurnas;
                let costoNocturnasMixtas = ((costoHoras * extrasNocturnas) * recargoNocturnoExtraValue);
                let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas) + parseFloat(costoNocturnas));
                let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoHorasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
            //Caso en el que la jornada sea nocturna
            else if ((totalTiempo > 8 && horasDiurnas <= 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadanNocturna' && cargo === descPersonal) {
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoDiurnasMixtas = (horasDiurnas * costoHoras);
                let entreDiurnas = (8 - horasDiurnas);
                let costoNocturnas = (entreDiurnas * costoHoras) * recargoNocturnoValue;
                let extrasNocturnas = horasNocturnas - entreDiurnas;
                let costoNocturnasMixtas = ((costoHoras * extrasNocturnas) * recargoNocturnoExtraValue);
                let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas) + parseFloat(costoNocturnas));
                let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoHorasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        });
    }
}