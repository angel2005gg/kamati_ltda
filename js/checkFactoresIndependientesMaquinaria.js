import { updateCostoKamatiUnitarioMaquinaria } from "./updateCostoKamatiUnitarioMaquinaria.js";
export async function checkFactoresIndependientesMaquinaria(baseRow, divContainer, tableBody) {
    // Seleccionamos los elementos dentro del contenedor dinámico
    const toggleCheckbox = divContainer.querySelector('.hidden_checkBoxmaquinariaClass');
    const toggleCheckboxHidden = divContainer.querySelector('.hiddenInputFactoresIndependientesClasMaquinariaClassUnique');
    const divWithHidden = divContainer.querySelector('.divHiddenMaquinariaClass');
    const inputHiddenDivHidden = divContainer.querySelector('.hiddenInputFactoresIndependientesClasMaquinaria');

    // Verificamos si el checkbox y el div con el contenido oculto existen
    if (toggleCheckbox && divWithHidden) {
        // Si el valor del input hidden es "1", activamos el checkbox
        if (toggleCheckboxHidden.value === "1" && !toggleCheckbox.checked) {
            console.log('El valor es 1, activando checkbox automáticamente');
            toggleCheckbox.checked = true; // Marcar el checkbox automáticamente
            toggleCheckbox.dispatchEvent(new Event('change')); // Disparar el evento change
            updateCostoKamatiUnitarioMaquinaria(baseRow, divContainer, tableBody); // Llamamos a la función para actualizar los costos
        }
        
        // Lógica del checkbox en el evento change
        toggleCheckbox.addEventListener('change', function () {
            console.log("Evento de cambio disparado");
            if (this.checked) {
                toggleCheckboxHidden.value = '1';
                divWithHidden.removeAttribute('hidden'); // Mostramos el div oculto
                inputHiddenDivHidden.value = 'check';
                updateCostoKamatiUnitarioMaquinaria(baseRow, divContainer, tableBody); // Llamamos a la función para actualizar los costos
            } else {
                toggleCheckboxHidden.value = '0';
                divWithHidden.setAttribute('hidden', true); // Ocultamos el div
                inputHiddenDivHidden.value = 'noCheck';
                updateCostoKamatiUnitarioMaquinaria(baseRow, divContainer, tableBody); // Llamamos a la función para actualizar los costos
            }
        });
    } else {
        console.error('No se encontraron los elementos necesarios dentro del contenedor dinámico');
    }
}