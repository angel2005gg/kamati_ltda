import { calculateCostoKamatiUnitario } from "./calculateCostoKamatiUnitarioSx.js";

export async function setupCheckboxToggle(row, originalTableContainer, tbody) {
    // Buscar el checkbox, los inputs y el campo hidden dentro de la fila clonada
    const checkbox = row.querySelector('.check_new_Factor-ClassActividades');
    const inputFactor = row.querySelector('.input-new-factor-Actividades-class');
    const hiddenInput = row.querySelector('.inputHidden-new-factor-Actividades-class');
    const hiddenEstadoCheckBox = row.querySelector('.input_hidden_check_unique_class_ac');
    
    // Verificar que todos los elementos necesarios existen
    if (checkbox && inputFactor && hiddenInput && hiddenEstadoCheckBox) {
        // Comprobar el estado inicial del checkbox y actualizar los elementos según el valor del estado
        if (hiddenEstadoCheckBox.value === "1") {
            checkbox.checked = true;
            inputFactor.removeAttribute('disabled');
            hiddenInput.value = 'true';
        } else {
            checkbox.checked = false;
            inputFactor.setAttribute('disabled', true);
            hiddenInput.value = 'false';
        }

        // Llamada inicial para actualizar el costo
        calculateCostoKamatiUnitario(tbody, originalTableContainer);

        // Evento de cambio que siempre se activa
        checkbox.addEventListener('change', function() {
            if (checkbox.checked) {
                // Habilitar el input y establecer el hidden input a true
                inputFactor.removeAttribute('disabled');
                inputFactor.value = '';
                hiddenInput.value = 'true';
                hiddenEstadoCheckBox.value = '1'; // Actualizar valor hidden a "1"
            } else {
                // Deshabilitar el input y establecer el hidden input a false
                inputFactor.setAttribute('disabled', true);
                inputFactor.value = '';
                hiddenInput.value = 'false';
                hiddenEstadoCheckBox.value = '0'; // Actualizar valor hidden a "0"
            }

            // Actualizar costos
            calculateCostoKamatiUnitario(tbody, originalTableContainer);
        });
    } else {
        console.error('No se encontraron los elementos necesarios en la fila.');
    }
}