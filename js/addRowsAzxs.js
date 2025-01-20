import { setupToggleButtonCosto } from './toggleInputCostosXscv.js';
import { setupToggleButton } from './toggleInputCxa.js';
import { setupCheckboxToggle } from './toggleInputFactor.js';
import { calculateCostoKamatiUnitario } from './calculateCostoKamatiUnitarioSx.js';
import { showDeleteModal } from './showDeleteMOdalActividadesFilas.js';
import { showDeleteModalTurno } from './showDeleteMOdalTurnoFilas.js';


// Función para clonar la fila normal
export function addNormalRow(baseRow, tbody, deleteModal, divContainerActividades) {
    const rowToClone = baseRow;
    if (!rowToClone) {
        console.error('No se encontró la fila para clonar.');
        return;
    }

    // Clonar la fila
    const newRow = rowToClone.cloneNode(true);
    tbody.appendChild(newRow);
    
   
    // Después de agregar la nueva fila, buscar hacia arriba la fila más cercana con la clase 'tr_new_tbody_turnounique_Class'
    // Después de agregar la nueva fila, buscar hacia arriba la fila más cercana con la clase 'tr_new_tbody_turnounique_Class'
    let closestTurnoRow = newRow.previousElementSibling; // Comienza desde el elemento anterior
    let closestDataIdTurno = null;
    while (closestTurnoRow) {
        if (closestTurnoRow.classList.contains('tr_new_tbody_turnounique_Class')) {
            closestDataIdTurno = closestTurnoRow.getAttribute('data-idTurno');
            break;
        }
        closestTurnoRow = closestTurnoRow.previousElementSibling; // Seguir buscando hacia arriba
    }

    // Si se encontró una fila con 'data-idTurno', asignar este valor a la nueva fila
    if (closestDataIdTurno) {
        newRow.setAttribute('data-idTurno', closestDataIdTurno);
    }

    // Limpia o asigna valores específicos a los inputs en la nueva fila
    const inputs = newRow.querySelectorAll("input, select, textarea");
    inputs.forEach(input => {
        if (input.classList.contains('mantener-valor')) {
            input.value = 'false'; // Mantener el valor 'false' en campos con clase 'mantener-valor'
            input.classList.add('hidden'); // Oculta el campo si es necesario
        } else {
            input.value = ''; // Limpia los demás inputs
        }

        // Habilita la edición
        input.removeAttribute('readonly');
    });

    // Hacer visible el select con id 'select_nombreCotizaciones' y ocultar 'input_hidden_externoId'
    const selectCotizaciones = newRow.querySelector('#select_nombreCotizaciones');
    if (selectCotizaciones) {
        selectCotizaciones.classList.remove('hidden'); // Asegura que no esté oculto
    }

    const inputHiddenExternoId = newRow.querySelector('#input_hidden_externoId');
    if (inputHiddenExternoId) {
        inputHiddenExternoId.classList.add('hidden'); // Oculta el input
    }

    const inputFacAd = newRow.querySelector('#input-new-factor-id');
    if (inputFacAd) {
        inputFacAd.setAttribute('disabled', true);
    }

    const inputCheckbox = newRow.querySelector('#chec_new-factor-id');
    if (inputCheckbox) {
        inputCheckbox.checked = false;
    }

    const textAreaCostoExterno = newRow.querySelector('#textAreaExternoCosto');
    if (textAreaCostoExterno) {
        textAreaCostoExterno.setAttribute('disabled', true);
    }

    const viaticos = {
        costoAlimentacion: newRow.querySelector('#textarea_costo_alt'),
        costoTransporte: newRow.querySelector('#textarea_costo_trs'),
        hiddenAlimentacion: newRow.querySelector('#hidden_alimentacionId'),
        hiddenTransporte: newRow.querySelector('#hidden_transporteId'),
    };
    if (viaticos.costoAlimentacion && viaticos.costoTransporte) {
        viaticos.costoAlimentacion.removeAttribute('style');
        viaticos.costoTransporte.removeAttribute('style');
        viaticos.hiddenAlimentacion.value = 'true';
        viaticos.hiddenTransporte.value = 'true';
    }

    // Añadir event listener para botón de eliminación
    const deleteButton = newRow.querySelector('.delete-btn');
    if (deleteButton) {
        deleteButton.addEventListener('click', function () {
            showDeleteModal(newRow, deleteModal, divContainerActividades, tbody);
        });
    }

    // Reasignar el formateo de inputs
    const valorInputs = newRow.querySelectorAll('.valor-input');
    valorInputs.forEach(input => {
        formatInputValue(input);

        input.addEventListener('input', function () {
            const cursorPosition = input.selectionStart;
            const originalLength = input.value.length;

            let cleanValue = input.value.replace(/[^0-9]/g, '');
            let formattedValue = '';

            for (let i = cleanValue.length; i > 0; i -= 3) {
                formattedValue = cleanValue.substring(i - 3, i) + (i !== cleanValue.length ? '.' : '') + formattedValue;
            }

            input.value = formattedValue;

            const newLength = formattedValue.length;
            const newCursorPosition = cursorPosition + (newLength - originalLength);
            input.setSelectionRange(newCursorPosition, newCursorPosition);
        });

        input.addEventListener('focusout', function () {
            formatInputValue(input);
        });
    });

    // Configura botones toggle y cálculo
    setupToggleButtonCosto(newRow);
    setupToggleButton(newRow);
    calculateCostoKamatiUnitario(tbody, divContainerActividades);

    // Llamar a setupCheckboxToggle para habilitar el checkbox en la nueva fila
    setupCheckboxToggle(newRow, divContainerActividades, tbody);

    return newRow;
}
// Función para formatear el valor en un input
function formatInputValue(input) {
    let cleanValue = input.value.replace(/[^0-9]/g, '');
    let formattedValue = '';

    for (let i = cleanValue.length; i > 0; i -= 3) {
        formattedValue = cleanValue.substring(i - 3, i) + (i !== cleanValue.length ? '.' : '') + formattedValue;
    }

    input.value = formattedValue;
}

// Función para clonar la fila con la clase 'no-mover'
export function addTurnRow(tbody, deleteModalTurno, divContainerActividades) {
    const rowToClone = document.querySelector('tr.no-mover');

    if (!rowToClone) {
        console.error('No se encontró la fila para clonar.');
        return;
    }

    // Clonar la fila
    const newRow = rowToClone.cloneNode(true);

    // Limpiar valores
    const inputs = newRow.querySelectorAll("input, select");
inputs.forEach(input => {
    if (input.tagName.toLowerCase() === "select" && input.classList.contains("tipoDia-classActividades")) {
        // Seleccionar siempre la opción con índice 1 (segunda opción)
        input.selectedIndex = 1;
    } else {
        // Limpiar todos los demás campos
        input.value = '';
    }
});

    // Asignar un atributo data-idTurno con un valor aleatorio
    const randomId = Math.floor(Math.random() * 10000); // Generar un número aleatorio entre 0 y 9999
    newRow.setAttribute('data-idTurno', randomId);

    // Añadir la clase 'tr_new_tbody_turnounique_Class' a la nueva fila
    newRow.classList.add('tr_new_tbody_turnounique_Class');

    // Añadir event listener para eliminar fila
    const deleteButton = newRow.querySelector('.borrar_turno_Ac_class');
    if (deleteButton) {
        deleteButton.addEventListener('click', function () {
            showDeleteModalTurno(newRow, deleteModalTurno, divContainerActividades, tbody);
        });
    }

    // Añadir nueva fila
    tbody.appendChild(newRow);

    // Llamar cálculo al agregar fila
    calculateCostoKamatiUnitario(tbody, divContainerActividades);

    return newRow;
   
}