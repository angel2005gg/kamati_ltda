import { calculateButtonNa } from "./calculateButtonNas.js";

// Función para actualizar los valores de los campos ocultos
function updateHiddenValues() {
    return {
        factorMoAcHidden: parseFloat(document.querySelector('#factorMoAcHidden').value) || 0,
        factorOAcHidden: parseFloat(document.querySelector('#factorOAcHidden').value) || 0,
        viaticosAcHidden: parseFloat(document.querySelector('#viaticosAcHidden').value) || 0,
        polizaAcHidden: parseFloat(document.querySelector('#polizaAcHidden').value) || 0,
    };
}

// Función para actualizar los listeners de los campos ocultos
function addHiddenFieldListeners() {
    const hiddenFields = ['#factorMoAcHidden', '#factorOAcHidden', '#viaticosAcHidden', '#polizaAcHidden'];

    hiddenFields.forEach(selector => {
        const field = document.querySelector(selector);
        if (field) {
            field.addEventListener('input', () => {
                // Recalcular cuando los valores ocultos cambien
                updateHiddenValues();
            });
        }
    });
}

// Llama a la función para añadir los listeners cuando se cargue la página
document.addEventListener('DOMContentLoaded', addHiddenFieldListeners);

// Función para calcular la utilidad
export function calculateAndSetUtilidad(row, costoExternoUnitario, abrvLinea, cant, cantPersonas, factorOAcValue, factorMoValue, polizaAcValue, viaticosValue) {
    // Obtener los valores de los elementos
    const nuevoFactor = row.querySelector('#input-new-factor-hiddenId');
    const nuevoFactorValue = row.querySelector('#input-new-factor-id');
    const inputHidden = document.querySelector('#inputHiddenDivHiddenid');
    
    // Obtener los valores ocultos actualizados
    const { factorMoAcHidden, factorOAcHidden, viaticosAcHidden, polizaAcHidden } = updateHiddenValues();

    // Verificar si 'nuevoFactor' es verdadero o falso
    const newFactor = nuevoFactor?.value === 'true'; // Usamos el operador de encadenamiento opcional por si 'nuevoFactor' es null
    const newFactorValueParsed = parseFloat(nuevoFactorValue?.value) || 0;

    // Verificar si el campo oculto está marcado
    const inputHiddenChecked = inputHidden?.value === 'check';

    // Determinar los valores a usar
    const factorOAc = (!newFactor && inputHiddenChecked) ? factorOAcHidden : (newFactor ? newFactorValueParsed : factorOAcValue);
    const factorMo = (!newFactor && inputHiddenChecked) ? factorMoAcHidden : (newFactor ? newFactorValueParsed : factorMoValue);
    const viaticos = (!newFactor && inputHiddenChecked) ? viaticosAcHidden : (newFactor ? newFactorValueParsed : viaticosValue);
    const polizaAc = (!newFactor && inputHiddenChecked) ? polizaAcHidden : polizaAcValue;

    // Obtener los valores calculados desde 'calculateButtonNa'
    const { costoApl, costoTrs } = calculateButtonNa(row);

    let diaUtilidadCliente;
    let diasUtilidadCliente;

    // Realizar cálculos según el valor de abrvLinea
    if (abrvLinea === "8") {
        diaUtilidadCliente = (
            ((parseFloat(costoExternoUnitario.replace(/\./g, '')) || 0) * factorOAc * polizaAc) +
            (costoApl * viaticos * polizaAc) +
            (costoTrs * viaticos * polizaAc)
        ) * cantPersonas;
    } else if (abrvLinea === "12") {
        diaUtilidadCliente = (
            ((parseFloat(costoExternoUnitario.replace(/\./g, '')) || 0) * viaticos * polizaAc) +
            (costoApl * viaticos * polizaAc) +
            (costoTrs * viaticos * polizaAc)
        ) * cantPersonas;
    } else {
        diaUtilidadCliente = (
            ((parseFloat(costoExternoUnitario.replace(/\./g, '')) || 0) * factorMo * polizaAc) +
            (costoApl * viaticos * polizaAc) +
            (costoTrs * viaticos * polizaAc)
        ) * cantPersonas;
    }

    // Multiplicar por la cantidad
    diasUtilidadCliente = diaUtilidadCliente * cant;

    // Redondear los resultados
    diaUtilidadCliente = Math.round(diaUtilidadCliente);
    diasUtilidadCliente = Math.round(diasUtilidadCliente);

    return {
        dia: diaUtilidadCliente,
        dias: diasUtilidadCliente
    };
}