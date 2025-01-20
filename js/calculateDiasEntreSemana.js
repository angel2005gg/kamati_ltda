import { abreviaturaUitlidades } from "./abreviaturaUtilidadess.js";
import { formatNumber } from "./formatNum.js";
export function calculateDiaEntresemanas(alimenatacionUtilidad, transporteUtilidad, alimenatacion, transporte, diasKamatiHidden, divContainerActividades, row, costoExterno, diaKamati, diasKamati, tipoDiaNoMover, abrvLinea, unidad, cant, { costoApl, costoTrs }, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas) {
    if (tipoDiaNoMover === 'Dia_semana' && unidad === 'Dias' && cant) {
        let costoDiaKamati = (parseFloat(costoExterno) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
        let costoDiaKamatiSin = (parseFloat(costoExterno)) * cantPersonas;
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
                abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoExterno, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
            }
        }
    }
}