import { formatNumber } from "./formatNum.js";

// Helper para detectar cambios en los valores
function valueChanged(oldValue, newValue) {
    return oldValue !== newValue;
}

// Helper para agregar múltiples event listeners de manera consolidada
function addListeners(row, elements, callback) {
    elements.forEach(element => {
        element.addEventListener('input', callback);
    });
}

export function abreviaturaUitlidades(divContainerActividades, row, abrvLinea, costoHorasExtraRecargo, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant) {

    // Almacenar los selectores una sola vez al inicio
    const inputs = {
        nuevoFactor: row.querySelector('.inputHidden-new-factor-Actividades-class'),
        nuevoFactorValue: row.querySelector('.input-new-factor-Actividades-class'),
        inputHidden: divContainerActividades.querySelector('.hiddenTableInput-actividades'),
        factorMoAcHidden: divContainerActividades.querySelector('.factorMoHiddenClass'),
        factorOAcHidden: divContainerActividades.querySelector('.factorOHiddenClass'),
        viaticosAcHidden: divContainerActividades.querySelector('.viaticosHiddenClass'),
        polizaAcHidden: divContainerActividades.querySelector('.polizaHiddenClaas'),
        diaUtilidad: row.querySelector('.valor-dia-utilidadClass'),
        diasUtilidad: row.querySelector('.valorDiasClienteUtilidadClass'),
        diasUtilidadClon: row.querySelector('.valor_diasCliente_classHiddenValorAc_unique_for_clon')

    };

    // Almacenar los valores antiguos para detectar cambios
    let lastValues = {
        factorOAc: '',
        factorMo: '',
        viaticos: '',
        polizaAc: ''
    };

    function shouldRecalculate(newValues) {
        return Object.keys(newValues).some(key => valueChanged(lastValues[key], newValues[key]));
    }

    function updateLastValues(newValues) {
        lastValues = { ...newValues };
    }

    // Obtener el nuevo factor y su valor
    const newFactor = inputs.nuevoFactor.value === 'true'; // El nuevo valor si se utiliza
    const newFactorValue = inputs.nuevoFactorValue.value;

    // Verificar si el campo oculto está marcado
    const inputHiddenChecked = inputs.inputHidden.value === 'check';

    // Obtener los valores de los campos ocultos
    const factorMoAcHidden = inputs.factorMoAcHidden.value;
    const factorOAcHidden = inputs.factorOAcHidden.value;
    const viaticosAcHidden = inputs.viaticosAcHidden.value;
    const polizaAcHidden = inputs.polizaAcHidden.value;

    // Decidir qué valores usar dependiendo de las combinaciones de nuevoFactor y inputHidden
    const factorOAc = (!newFactor && inputHiddenChecked) ? factorOAcHidden : (newFactor ? newFactorValue : factorOAcValue);
    const factorMo = (!newFactor && inputHiddenChecked) ? factorMoAcHidden : (newFactor ? newFactorValue : factorMoValue);
    const viaticos = (!newFactor && inputHiddenChecked) ? viaticosAcHidden : (newFactor ? newFactorValue : viaticosValue);
    const polizaAc = (!newFactor && inputHiddenChecked) ? polizaAcHidden : polizaAcValue;

    // Obtener los elementos de utilidad
    const diaUtilidad = inputs.diaUtilidad;
    const diasUtilidad = inputs.diasUtilidad;
    const diasUtilidadClon = inputs.diasUtilidadClon;

    // Realizar cálculos si los valores realmente cambiaron
    const newValues = { factorOAc, factorMo, viaticos, polizaAc };

    if (shouldRecalculate(newValues)) {
        updateLastValues(newValues);

        // Realizar cálculos según el valor de abrvLinea
        let diasX;
        if (abrvLinea === '8') {
            diasX = ((parseFloat(costoHorasExtraRecargo) * factorOAc * polizaAc) + costoFinalAlimentacion + costoFinalTransporte) * cantPersonas;
            diasUtilidadClon.value = formatNumber(Math.round(((parseFloat(costoHorasExtraRecargo) * factorOAc * polizaAc) * cant) * cantPersonas));
        } else if (abrvLinea === '12') {
            diasX = ((parseFloat(costoHorasExtraRecargo) * viaticos * polizaAc) + costoFinalAlimentacion + costoFinalTransporte) * cantPersonas;
            diasUtilidadClon.value = formatNumber(Math.round(((parseFloat(costoHorasExtraRecargo) * viaticos * polizaAc) * cant) * cantPersonas));
        } else {
            diasX = ((parseFloat(costoHorasExtraRecargo) * factorMo * polizaAc) + costoFinalAlimentacion + costoFinalTransporte) * cantPersonas;
            diasUtilidadClon.value = formatNumber(Math.round(((parseFloat(costoHorasExtraRecargo) * factorMo * polizaAc) * cant) * cantPersonas));
        }

        // Actualizar los campos de utilidad
        diaUtilidad.value = formatNumber(Math.round(diasX));
        diasUtilidad.value = formatNumber(Math.round(diasX * cant));
    }

    // Agregar event listeners para recalcular cuando los valores cambien (sin debounce)
    addListeners(row, [
        inputs.nuevoFactor,
        inputs.nuevoFactorValue,
        inputs.factorMoAcHidden,
        inputs.factorOAcHidden,
        inputs.viaticosAcHidden,
        inputs.polizaAcHidden
    ], () => abreviaturaUitlidades(divContainerActividades,row, abrvLinea, costoHorasExtraRecargo, factorOAcValue, polizaAcValue, costoFinalAlimentacion, costoFinalTransporte, cantPersonas, viaticosValue, factorMoValue, cant));
}