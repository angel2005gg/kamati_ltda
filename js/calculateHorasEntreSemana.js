import { abreviaturaUitlidadesHoras, abreviaturaUitlidadesHorasq } from "./abreviaturasHoras.js";
import { formatNumber } from "./formatNum.js";
export function calculateHorasDiaSemana(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, row, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, extraDiurnaValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas) {
    if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Horas' && cant) {
        let cantint = parseInt(cant);
        if (cantint === 8) {
            let costoDiaKamati = (parseFloat(costoExterno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
            let costoDiaKamatiSin = (parseFloat(costoExterno)) * cantPersonas;
            let costoDiasKamati = (parseFloat(costoDiaKamati) * (parseFloat(cantint) / 8));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cantint / 8)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidadesHoras(divContainerActividades, row, abrvLinea, costoExterno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        } else if (cantint > 8) {
            let costoHora = parseFloat(costoExterno) / 8;
            let costoExtraCantint = ((cantint - 8) * costoHora) * extraDiurnaValue;
            let costoFinal = (parseFloat(costoExterno) + parseFloat(costoExtraCantint));
            let costoDiaKamati = (parseFloat(costoFinal) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
            let costoDiaKamatiSin = (parseFloat(costoFinal)) * cantPersonas;
            let costoDiasKamati = (parseFloat(costoDiaKamati) * (parseFloat(cantint) / 8));
            if (!isNaN(costoDiaKamati) && !isNaN(costoDiasKamati)) {
                diaKamati.value = formatNumber(Math.round(costoDiaKamati));
                diasKamati.value = formatNumber(Math.round(costoDiasKamati));
                diasKamatiHidden.value = formatNumber(Math.round(costoDiaKamatiSin * parseFloat(cantint / 8)));
                alimenatacion.value = formatNumber(Math.round(parseFloat(costoApl) * cantPersonas));
                transporte.value = formatNumber(Math.round(parseFloat(costoTrs) * cantPersonas));
                alimenatacionUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalAlimentacion) * cantPersonas));
                transporteUtilidad.value = formatNumber(Math.round(parseFloat(costoFinalTransporte) * cantPersonas));
                if (abrvLinea === "8" || abrvLinea === "12" || (abrvLinea !== "8" && abrvLinea !== "12")) {
                    abreviaturaUitlidadesHoras(divContainerActividades, row, abrvLinea, costoFinal, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
                }
            }
        } else if (cantint < 8) {
            let costoHora = parseFloat(costoExterno) / 8;
            let costoHorasFin = parseFloat(costoHora * cantint);
            let costoDiaKamati = ((parseFloat(costoHorasFin) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas);
            let costoDiaKamatiSin = ((parseFloat(costoHora) * (cant)) * cantPersonas);

            console.log(cantPersonas);
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
    }
}