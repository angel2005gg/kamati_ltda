import { abreviaturaUitlidades } from "./abreviaturaUtilidadess.js"; 
import { formatNumber } from "./formatNum.js"; 
export function calculateDiasDominical(alimenatacionUtilidad, transporteUtilidad, alimenatacion, diasKamatiHidden, divContainerActividades ,row, costoExterno, diaKamati, diasKamati,tipoDiaNoMover,abrvLinea,unidad, cant,{costoApl, costoTrs}, factorOAcValue,factorMoValue, polizaAcValue, viaticosValue,costoFinalAlimentacion,costoFinalTransporte,cantPersonas, recargoFestivoDominicalValue){
    if (tipoDiaNoMover === 'Dominical' && unidad === 'Dias' && cant) {
        let costoDia = (parseFloat(costoExterno) * recargoFestivoDominicalValue);
        let costoDiaKamati = (parseFloat(costoDia) + parseFloat(costoTrs) + parseFloat(costoApl)) * cantPersonas;
        let costoDiaKamatiSin = (parseFloat(costoDia)) * cantPersonas;
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
                abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoDia, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant);
            }
        }
    }
}