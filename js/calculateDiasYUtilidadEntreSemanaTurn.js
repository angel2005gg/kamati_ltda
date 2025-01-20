import { formatNumber } from './formatNum.js';
import { abreviaturaUitlidades } from './abreviaturaUtilidadess.js';
export function entreSemanaTurn(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden,divContainerActividades, row, costoExterno, diaKamati, diasKamati,tipoDiaNoMover, abrvLinea,unidad, cant,{costoApl, costoTrs}, factorOAcValue,factorMoValue, polizaAcValue, viaticosValue,recargoNocturnoValue,extraDiurnaValue,recargoNocturnoExtraValue,costoFinalAlimentacion,costoFinalTransporte,totalTiempo, jornada,horasDiurnas,horasNocturnas,cantPersonas){
    if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Turn' && cant) {
        console.log(totalTiempo);
        let horasCalculadas = 0;
        //En caso de que sean 8 horas de jornada diurna 
        if (totalTiempo === 8 && jornada === 'JornadaDiurna') {
            let costoNew = costoExterno;
            let costoDiaKamati = (parseFloat(costoNew) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
            let costoDiaKamatiSin = (parseFloat(costoNew)) * cantPersonas;
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNew, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }//Caso en el que hay horas extras diurnas es decir más de 8 horas laborales entre las 6:am - 9:pm
        else if ((totalTiempo > 8 && totalTiempo <= 15) && jornada === 'JornadaDiurna') {
            horasCalculadas = totalTiempo - 8;
            //divide el costo externo por las 8 horas laborales
            let costoHorasExtra = costoExterno / 8;
            let costoHorasExtraRecargo = ((costoHorasExtra * horasCalculadas ) * extraDiurnaValue);
            let costoNew = costoExterno;
            let costoFinal = parseFloat(costoNew) + parseFloat(costoHorasExtraRecargo);
            let costoDiaKamati = ((parseFloat(costoNew) + parseFloat(costoTrs) + parseFloat(costoApl) + parseFloat(costoHorasExtraRecargo)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoNew) + parseFloat(costoHorasExtraRecargo)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoFinal, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que se toman menos de 8 horas para un turno
        else if (totalTiempo < 8 && jornada === 'JornadaDiurna') {
            //divide el costo externo por las 8 horas laborales
            let costoHoras = costoExterno / 8;
            let costoHorasExtraRecargo = (costoHoras * totalTiempo);
            let costoDiaKamati = ((parseFloat(costoHorasExtraRecargo) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHorasExtraRecargo)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasExtraRecargo, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                    
                }
            }
        }
        //Caso en el que la jornada es nocturna y son 8 horas laborales
        else if ((totalTiempo === 8 && totalTiempo < 9) && jornada === 'JornadaNocturna') {
            let costoRecargoNocturno = (costoExterno * recargoNocturnoValue);
            let costoDiaKamati = ((parseFloat(costoRecargoNocturno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoRecargoNocturno)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que la jornada es nocturna y supera las 8 horas
        else if (totalTiempo === 9 && jornada === 'JornadaNocturna') {
            let costoRecargoNocturno = (costoExterno * recargoNocturnoValue) + ((costoExterno / 8) * recargoNocturnoExtraValue);
            let costoDiaKamati = ((parseFloat(costoRecargoNocturno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoRecargoNocturno)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que las horas norcturnas sean menos de 8
        else if (totalTiempo < 8 && jornada === 'JornadaNocturna') {
            //divide el costo externo por las 8 horas laborales
            let costoHoras = costoExterno / 8;
            let costoHorasRecargoNocturno = (costoHoras * totalTiempo) * recargoNocturnoValue;
            let costoDiaKamati = ((parseFloat(costoHorasRecargoNocturno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHorasRecargoNocturno)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasRecargoNocturno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que la jornada es mixta y son 8 horas laborales
        else if (totalTiempo === 8 && jornada === 'JornadaMixta') {
            let costoHoras = costoExterno / 8;
            let costoDiurnasMixtas = horasDiurnas * costoHoras;
            let costoNocturnasMixtas = (horasNocturnas * costoHoras) * recargoNocturnoValue;
            let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas));
            let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHorasMixtas)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que son mas de 8 horas laborales es jonada mixta, las horas diurnas son mas de 8 y y las nocturnas son mas de 1 y menos de 9 
        else if ((totalTiempo > 8 && horasDiurnas > 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadaMixta') {
            let costoHoras = costoExterno / 8;
            let costoDiurnasMixtas = ((horasDiurnas - 8) * costoHoras) * extraDiurnaValue;
            let costoNocturnasMixtas = (horasNocturnas * costoHoras) * recargoNocturnoExtraValue;
            let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas) + parseFloat(costoExterno));
            let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHorasMixtas)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que son mas de 8 horas laborales pero las horas diurnas son menos de 8 y las nocturnas son mas de 1 y menos de 9 
        else if ((totalTiempo > 8 && horasDiurnas <= 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadaMixta') {
            let costoHoras = costoExterno / 8;
            let costoDiurnasMixtas = (horasDiurnas * costoHoras);
            let entreDiurnas = (8 - horasDiurnas);
            let costoNocturnas = (entreDiurnas * costoHoras) * recargoNocturnoValue;
            let extrasNocturnas = horasNocturnas - entreDiurnas;
            let costoNocturnasMixtas = ((costoHoras * extrasNocturnas) * recargoNocturnoExtraValue);
            let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas) + parseFloat(costoNocturnas));
            let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHorasMixtas)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
        //Caso en el que la jornada sea nocturna
        else if ((totalTiempo > 8 && horasDiurnas <= 8 && (horasNocturnas >= 1 && horasNocturnas <= 9)) && jornada === 'JornadanNocturna') {
            let costoHoras = costoExterno / 8;
            let costoDiurnasMixtas = (horasDiurnas * costoHoras);
            let entreDiurnas = (8 - horasDiurnas);
            let costoNocturnas = (entreDiurnas * costoHoras) * recargoNocturnoValue;
            let extrasNocturnas = horasNocturnas - entreDiurnas;
            let costoNocturnasMixtas = ((costoHoras * extrasNocturnas) * recargoNocturnoExtraValue);
            let costoHorasMixtas = (parseFloat(costoDiurnasMixtas) + parseFloat(costoNocturnasMixtas) + parseFloat(costoNocturnas));
            let costoDiaKamati = ((parseFloat(costoHorasMixtas) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHorasMixtas)) * cantPersonas);
            let costoDiasKamati = (parseFloat(costoDiaKamati) * parseFloat(cant));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cant)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * parseFloat(cant) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * parseFloat(cant) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * parseFloat(cant) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * parseFloat(cant) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoNocturnasMixtas, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        }
    }
}