import { formatNumberWithDots } from "./formatNumbersWithDotsMateriales.js";
import { obtenerValorSinFormato } from "./obtenerValorSinFormato.js";
import { toFixedWithComma } from "./toFixedWithCommaMateriales.js";
import { calculateTotals } from "./calculateTotalsMateriales.js";

export async function updateCostoKamatiUnitario(row = null, divContainer, tableBody) {
    const container = row || document;
    // Si se proporciona una fila, usa los valores de esa fila

    // Añadir el event listener al input de tipo date
    container.querySelectorAll('#idfecha_tiempo_entrega').forEach(input => {
        input.addEventListener('change', function () {
            const fechaActual = new Date();
            const fechaSeleccionada = new Date(this.value);

            const diferenciaTiempo = fechaSeleccionada - fechaActual;
            const diferenciaDias = Math.floor(diferenciaTiempo / (1000 * 60 * 60 * 24)) + 1;

            const textarea = this.closest('tr').querySelector('#valor_tiempo_entrega');

            if (diferenciaDias > 6) {
                const semanas = Math.floor(diferenciaDias / 6);
                textarea.value = `${semanas} semana(s)`;
            } else {
                textarea.value = `${diferenciaDias} día(s)`;
            }
        });
    });

    const listaAbreviatura = container.querySelector('.abreviatura-lista');
    const precioListaInput = container.querySelector('.precio-lista-input');
    const selectTrm = container.querySelector('.select_trm_nu');
    const costKamatiInput = container.querySelector('.cost-kamati-input');

    const costKamatiTotalElement = container.querySelector('.cost-kamati-total');
    const valorUtilidad = container.querySelector('.valor-utilidad');
    const valorUtilidadTotal = container.querySelector('.value-total-input');
    const descuentoInput = container.querySelector('.descuento-input');
    const descuentoAdicionalInput = container.querySelector('.descuento-adicional-input');
    const cantidadMaterial = container.querySelector('.cantidad-material');
    const cantidadMaterialSinFormato = obtenerValorSinFormato(cantidadMaterial);
    const poliza = document.querySelector('#polizaGlobal');
    const viaticos = document.querySelector('#viaticosGlobal');
    const factorO = document.querySelector('#factorOGlobal');
    const factorMO = document.querySelector('#factorMoGlobal');
    const siemens = document.querySelector('#siemensGlobal');
    const rittal = document.querySelector('#rittalGlobal');
    const pc = document.querySelector('#phoenixGlobal');
    const pilz = document.querySelector('#pilzGlobal');

    const identificacionUsd = document.querySelector('#txt_identificacion_usd');
    const identificacionUsdSinFormato = obtenerValorSinFormato(identificacionUsd);

    const identificacionEur = document.querySelector('#txt_identificacion_eur');
    const identificacionEurSinFormato = obtenerValorSinFormato(identificacionEur);

    if (precioListaInput && descuentoInput && descuentoAdicionalInput && costKamatiInput && cantidadMaterialSinFormato) {
        let cantidad = parseFloat(cantidadMaterialSinFormato);
        let precioLista = parseFloat(obtenerValorSinFormato(precioListaInput)) || 0;
        let descuento = parseFloat(descuentoInput.value) || 0;
        let descuentoAdicional = parseFloat(descuentoAdicionalInput.value) || 0;
        let moneda = selectTrm ? selectTrm.value : 'COP';

        if (moneda === 'USD' && identificacionUsdSinFormato) {
            let usdValue = parseFloat(identificacionUsdSinFormato) || 1;
            precioLista *= usdValue;
        } else if (moneda === 'EUR' && identificacionEurSinFormato) {
            let eurValue = parseFloat(identificacionEurSinFormato) || 1;
            precioLista *= eurValue;
        }
        let precioConDescuento = precioLista - (precioLista * (descuento / 100));

        if (descuentoAdicional > 0) {
            precioConDescuento -= (precioConDescuento * (descuentoAdicional / 100));
        }

        // Aplicar el formato al valor antes de asignarlo al input
        costKamatiInput.value = `$ ${formatNumberWithDots(toFixedWithComma(precioConDescuento, 2, ''))}`;

        let costoKamati = parseFloat(obtenerValorSinFormato(costKamatiInput)) || 0;

        if (cantidad > 0) {
            let valorFinal = parseFloat(costoKamati) * parseFloat(cantidad);

            if (costKamatiTotalElement) {
                costKamatiTotalElement.value = `$ ${formatNumberWithDots(toFixedWithComma(valorFinal, 2, ''))}`;
            }
        }

        // Obtener el valor de los inputs
        let inputHiddenNewFactor = container.querySelector('.inputHiddenNewFactorClas'); // Usar clase en lugar de ID
        let newFactorValue = container.querySelector('.inputNewFactorClas');
        let hiddenInputFactoresIndependientes = divContainer.querySelector('.hiddenInputFactoresIndependientesClas'); // Input para verificar factores independientes

        // Obtener los factores independientes
        let factorMOHidden = divContainer.querySelector('.factor_MoClassMateriales');
        let factorOHidden = divContainer.querySelector('.factor_OClassMateriales');
        let factorPolizaHidden = divContainer.querySelector('.factor_polizaClassMateriales');
        let factorViaticosHidden = divContainer.querySelector('.factor_VClassMateriales');
        let siemensHidden = divContainer.querySelector('.factor_siemensClassMateriales');
        let pilzHidden = divContainer.querySelector('.factor_pilzClassMateriales');
        let rittalHidden = divContainer.querySelector('.factor_rittalClassMateriales');
        let pcHidden = divContainer.querySelector('.factor_phoenixcontactClassMateriales');

        // Variables iniciales de los factores globales
        let valorUtilidadFinal = 0;
        let factorPolizaValue = poliza ? parseFloat(poliza.value) || 0 : 0;
        let factorViaticoValue = viaticos ? parseFloat(viaticos.value) || 0 : 0;
        let factorOValue = factorO ? parseFloat(factorO.value) || 0 : 0;
        let factorMOValue = factorMO ? parseFloat(factorMO.value) || 0 : 0;
        let siemensValue = siemens ? parseFloat(siemens.value) || 0 : 0;
        let rittalValue = rittal ? parseFloat(rittal.value) || 0 : 0;
        let pilzValue = pilz ? parseFloat(pilz.value) || 0 : 0;
        let pcValue = pc ? parseFloat(pc.value) || 0 : 0;
        let abreviatura = listaAbreviatura ? parseInt(listaAbreviatura.value, 10) : 0;

        // Verificar si inputHiddenNewFactor es verdadero y si hiddenInputFactoresIndependientes está en 'check'
        let inputHiddenChecked = (hiddenInputFactoresIndependientes && hiddenInputFactoresIndependientes.value === 'check');
        let newFactor = (inputHiddenNewFactor && inputHiddenNewFactor.value === 'true');

        // Asignar los factores usando la lógica de ternarios
        const factorOAc = (!newFactor && inputHiddenChecked) ? parseFloat(factorOHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : factorOValue);
        const factorVAc = (!newFactor && inputHiddenChecked) ? parseFloat(factorViaticosHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : factorViaticoValue);
        const factorMo = (!newFactor && inputHiddenChecked) ? parseFloat(factorMOHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : factorMOValue);
        const siemensAc = (!newFactor && inputHiddenChecked) ? parseFloat(siemensHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : siemensValue);
        const rittalAc = (!newFactor && inputHiddenChecked) ? parseFloat(rittalHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : rittalValue);
        const pilzAc = (!newFactor && inputHiddenChecked) ? parseFloat(pilzHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : pilzValue);
        const pcAc = (!newFactor && inputHiddenChecked) ? parseFloat(pcHidden.value) || 0 : (newFactor ? parseFloat(newFactorValue.value) || 0 : pcValue);
        const polizaAc = (!newFactor && inputHiddenChecked) ? parseFloat(factorPolizaHidden.value) || 0 : factorPolizaValue;

        // Calcular el valor de utilidad final según el valor de abreviatura
        if (abreviatura === 8) { // O otros
            valorUtilidadFinal = costoKamati * factorOAc * polizaAc;
        } else if (abreviatura === 13) { // MO mano de obra electrica
            valorUtilidadFinal = costoKamati * factorMo * polizaAc;
        } else if ([1, 2, 3, 4, 5, 6, 7].includes(abreviatura)) {
            valorUtilidadFinal = costoKamati * siemensAc * polizaAc;
        } else if (abreviatura === 10) { // PC PHOENIX CONTACT
            valorUtilidadFinal = costoKamati * pcAc * polizaAc;
        } else if (abreviatura === 9) { // PILZ
            valorUtilidadFinal = costoKamati * pilzAc * polizaAc;
        } else if (abreviatura === 11) { // Rittal
            valorUtilidadFinal = costoKamati * rittalAc * polizaAc;
        } else if ([14, 15, 16, 17].includes(abreviatura)) {
            valorUtilidadFinal = costoKamati * factorMo * polizaAc;
        } else if (abreviatura === 12) {
            valorUtilidadFinal = costoKamati * factorVAc * polizaAc;
        }

        // Actualizar los valores de los inputs de utilidad
        if (valorUtilidad) {
            valorUtilidad.value = `$ ${formatNumberWithDots(toFixedWithComma(valorUtilidadFinal, 2, ''))}`;
        }

        let valorUtilidadTo = cantidad * valorUtilidadFinal;
        if (valorUtilidadTotal) {
            valorUtilidadTotal.value = `$ ${formatNumberWithDots(toFixedWithComma(valorUtilidadTo, 2, ''))}`;
        }
    }


    calculateTotals(divContainer, tableBody);
}


