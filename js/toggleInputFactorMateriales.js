import { updateCostoKamatiUnitario } from "./updateCostoKamatiUnitarioMateriales.js";

export async function setupCheckboxToggleMateriales(row, divContainer, tableBody) {
    // Buscar el checkbox, los inputs y el campo hidden dentro de la fila clonada
    const checkbox = row.querySelector('.checkBoxk_newFactorClass');
    const inputFactor = row.querySelector('.inputNewFactorClas');
    const hiddenInput = row.querySelector('.inputHiddenNewFactorClas');
    const hiddenEstadoCheckBox = row.querySelector('.hidden_estado_unique_checkBox'); 

    if (checkbox && inputFactor && hiddenInput && hiddenEstadoCheckBox) {
        // Habilitar/deshabilitar el checkbox y el input según el estado actual
        if (hiddenEstadoCheckBox.value === "1") {
            checkbox.checked = true;
            inputFactor.removeAttribute('disabled');
            hiddenInput.value = 'true';
        } else {
            checkbox.checked = false;
            inputFactor.setAttribute('disabled', true);
            hiddenInput.value = 'false';
        }

        updateCostoKamatiUnitario(row, divContainer, tableBody); // Llamamos a la función inicial

        // Evento de cambio que siempre se activa
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                inputFactor.removeAttribute('disabled');
                inputFactor.value = '';
                hiddenInput.value = 'true';
                hiddenEstadoCheckBox.value = '1'; // Actualizar valor hidden a "1"
            } else {
                inputFactor.setAttribute('disabled', true);
                inputFactor.value = '';
                hiddenInput.value = 'false';
                hiddenEstadoCheckBox.value = '0'; // Actualizar valor hidden a "0"
            }
            updateCostoKamatiUnitario(row, divContainer, tableBody); // Actualización de costos
        });
    } else {
        console.error('No se encontraron los elementos necesarios en la fila.');
    }
}