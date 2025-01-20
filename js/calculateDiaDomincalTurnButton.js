import { formatNumber } from "./formatNum.js";
import { abreviaturaUitlidades } from "./abreviaturaUtilidadess.js"; 
import { calcularHorasNocturnas } from "./calculateNocturnHrs.js";
export function calculateDiaDomicalTurnButons(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte,diasKamatiHidden,divContainerActividades, tableBody,otherTable, row, costoExterno, diaKamati, diasKamati,tipoDiaNoMover,abrvLinea,unidad, cant,{costoApl, costoTrs}, factorOAcValue,factorMoValue, polizaAcValue, viaticosValue,recargoNocturnoValue,recargoNocturnoExtraValue,costoFinalAlimentacion,costoFinalTransporte,totalTiempo, jornada,horasDiurnas,horasNocturnas,cantPersonas, recargoFestivoDominicalValue, recargoExtraDominicalDiurnaValue, recargoNocturnoDominicalValue, recargoNocturnoExtraDominicalValue){
    
    const hiddenInputValue = divContainerActividades.querySelector('.hiddenTableInput-actividades').value;
    console.log(hiddenInputValue);
    // Seleccionar la tabla adecuada
    let tableToUse;
    if (hiddenInputValue === "check") {
        tableToUse = divContainerActividades.querySelector('.hiddenTableInput-actividades .tbody_actividades-hiddenClass'); // Tabla oculta
    } else {
        tableToUse = otherTable; // Tabla pasada como parámetro
    }
 
    
    if (tipoDiaNoMover === 'Dominical' && unidad === 'Turn' && cant) {
        const descPersonal = row.querySelector('.select-nombreCotizacionesActividades-Class').value;
        tableToUse.querySelectorAll('tr').forEach(otherRow => {
            const cargo = otherRow.querySelector('td:first-child').textContent.trim();
            const valorDia = otherRow.querySelector('.tarifa_CargoGlobalClass1').value.replace(/\./g, '');
            let valorDiaCalculo = valorDia;
            let horasCalculadas = 0;
            //En caso de que sean 8 horas de jornada diurna 
            if (totalTiempo === 8 && jornada === 'JornadaDiurna' && cargo === descPersonal) {
                let costoNew = parseFloat(valorDiaCalculo) * recargoFestivoDominicalValue;
                let costoDiaKamati = (parseFloat(costoNew) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoNew) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNew, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que hay horas extras diurnas es decir más de 8 horas laborales entre las 6:am - 9:pm
            else if ((totalTiempo > 8 && totalTiempo <= 15) && jornada === 'JornadaDiurna' && cargo === descPersonal) {
                horasCalculadas = totalTiempo - 8;
                //divide el costo externo por las 8 horas laborales
                let costoHorasExtra = parseFloat(valorDiaCalculo) / 8;
                let costoHorasExtraRecargo = (costoHorasExtra * horasCalculadas * recargoExtraDominicalDiurnaValue);
                let costoNew = parseFloat(valorDiaCalculo) * recargoFestivoDominicalValue;
                let costoFinal = costoNew + costoHorasExtraRecargo;
                let costoDiaKamati = ((parseFloat(costoNew) + parseFloat(costoTrs) + parseFloat(costoApl) + parseFloat(costoHorasExtraRecargo)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoNew) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoFinal, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que hay menos de 8 horas laborales en jornada diurna
            else if (totalTiempo < 8 && jornada === 'JornadaDiurna' && cargo === descPersonal) {
                //divide el costo externo por las 8 horas laborales
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoHorasExtraRecargo = (costoHoras * totalTiempo * recargoFestivoDominicalValue);
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
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasExtraRecargo, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que hay 8 horas laborales nocturnas
            else if (totalTiempo === 8 && jornada === 'JornadaNocturna' && cargo === descPersonal) {
                const { horasAntesDeMedianoche, horasDespuesDeMedianoche } = calcularHorasNocturnas(tableBody);
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoAntesMediaNoche = (horasAntesDeMedianoche * costoHoras * recargoNocturnoDominicalValue);
                let costoDespuesMediaNoche = (horasDespuesDeMedianoche * costoHoras * recargoNocturnoValue);
                let costoRecargoNocturno = (costoAntesMediaNoche + costoDespuesMediaNoche);
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
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que hay 9 horas laborales en la jornada nocturna
            else if (totalTiempo === 9 && jornada === 'JornadaNocturna' && cargo === descPersonal) {
                const { horasAntesDeMedianoche, horasDespuesDeMedianoche } = calcularHorasNocturnas(tableBody);
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoHorasAntesDeMedianoche = (horasAntesDeMedianoche * costoHoras * recargoNocturnoDominicalValue);
                let antesDe = horasDespuesDeMedianoche - 1;
                let costoHorasDespuesDeMedianoche = (antesDe * costoHoras * recargoNocturnoExtraValue);
                let costoHoraNocturnaExtraDespues = (1 * costoHoras * recargoNocturnoExtraValue);
                let costoRecargoNocturno = (costoHorasAntesDeMedianoche + costoHorasDespuesDeMedianoche + costoHoraNocturnaExtraDespues);
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
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que son menos de 8 horas en jornada nocturna
            else if (totalTiempo < 8 && jornada === 'JornadaNocturna' && cargo === descPersonal) {
                //divide el costo externo por las 8 horas laborales
                const { horasAntesDeMedianoche, horasDespuesDeMedianoche } = calcularHorasNocturnas(tableBody);
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                let costoAntesMediaNoche = (horasAntesDeMedianoche * costoHoras * recargoNocturnoDominicalValue);
                let costoDespuesMediaNoche = (horasDespuesDeMedianoche * costoHoras * recargoNocturnoValue);
                let costoHorasRecargoNocturno = (costoAntesMediaNoche + costoDespuesMediaNoche);
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
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que son 8 horas y abarca las jornadas diurna y nocturna
            else if (totalTiempo === 8 && jornada === 'JornadaMixta' && cargo === descPersonal) {
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                const { horasAntesDeMedianoche, horasDespuesDeMedianoche } = calcularHorasNocturnas(tableBody);
                let entreDe = horasAntesDeMedianoche - horasDiurnas;
                let costoNocturnasNormales = entreDe * costoHoras * recargoNocturnoDominicalValue;
                let diurnasNormales = horasDiurnas * costoHoras * recargoFestivoDominicalValue;
                let costoDespuesDeMediaNoche = horasDespuesDeMedianoche * costoHoras * recargoNocturnoValue
                let costoNocturnasMixtas = (costoNocturnasNormales + diurnasNormales + costoDespuesDeMediaNoche);
                let costoDiaKamati = ((parseFloat(costoNocturnasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoNocturnasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que son mas de 8 horas laborales, mas de 8 horas diurnas y mas de 1 y menos de 9 horas nocturnas
            else if ((totalTiempo > 8 && horasDiurnas > 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadaMixta' && cargo === descPersonal) {
                let costoHoras = (parseFloat(valorDiaCalculo) / 8);
                const { horasAntesDeMedianoche, horasDespuesDeMedianoche } = calcularHorasNocturnas(tableBody);
                let entreDe = horasAntesDeMedianoche - horasDiurnas;
                let costoNocturnasNormales = entreDe * costoHoras * recargoNocturnoExtraDominicalValue;
                let entreAn = horasDiurnas - 8;
                let diurnasNormales = 8 * costoHoras * recargoFestivoDominicalValue;
                let extraDiurnass = costoHoras * entreAn * recargoExtraDominicalDiurnaValue;
                let costoDespuesDeMediaNoche = horasDespuesDeMedianoche * costoHoras * recargoNocturnoExtraValue
                let costoNocturnasMixtas = (costoNocturnasNormales + diurnasNormales + costoDespuesDeMediaNoche + extraDiurnass);
                let costoDiaKamati = ((parseFloat(costoNocturnasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoNocturnasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
            //Caso en el que son mas de 8 horas laborales menos de 8 horas diurnas y mas de 1 y menos de 9 nocturnas
            else if ((totalTiempo > 8 && horasDiurnas <= 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadaMixta' && cargo === descPersonal) {
                let costoHoras = costoExterno / 8;
                const { horasAntesDeMedianoche, horasDespuesDeMedianoche } = calcularHorasNocturnas(tableBody);
                let entreDe = horasAntesDeMedianoche - horasDiurnas;
                let costoNocturnasNormales = entreDe * costoHoras * recargoNocturnoDominicalValue;
                let diurnasNormales = horasDiurnas * costoHoras * recargoFestivoDominicalValue;
                let entreOchoDespues = 8 - horasDespuesDeMedianoche;
                let faltantesDespues = horasDespuesDeMedianoche - entreOchoDespues;
                let costoDespuesDeMediaNoche = entreOchoDespues * costoHoras * recargoNocturnoValue;
                let costoFaltantes = faltantesDespues * costoHoras * recargoNocturnoExtraValue;
                let costoNocturnasMixtas = (costoNocturnasNormales + diurnasNormales + costoDespuesDeMediaNoche + costoFaltantes);
                let costoDiaKamati = ((parseFloat(costoNocturnasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
                let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
                if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                    diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                    diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                    diasKamatiHidden.value = formatNumber(Math.round(((costoNocturnasMixtas) * cantPersonas)*parseFloat(cant)));
                    alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                    if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                        abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    }
                }
            }
        });
  
}}