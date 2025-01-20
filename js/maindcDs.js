// Importaciones de las funciones necesarias
import { addNormalRow, addTurnRow } from './addRowsAzxs.js';
import { cloneTable } from './saveTablesAzcx.js';
import { setupToggleButton } from './toggleInputCxa.js';
import { setupToggleButtonCosto } from './toggleInputCostosXscv.js';
import { calculateCostoKamatiUnitario } from './calculateCostoKamatiUnitarioSx.js'; 
import { setupCheckboxToggle } from './toggleInputFactor.js'; 
import { checkFactoresIndependent } from './checkFactoresIndependientes.js'; 
import { initializeTable } from './calculateSumColumns.js'; // Importa la nueva función

document.addEventListener('DOMContentLoaded', () => {
    const divContainerActividades = document.querySelector('.table-actividades-Class-container');
    const originalTableContainer = document.querySelector('.table-actividades-Class-container');
    const tbody = originalTableContainer.querySelector('.tbodyActividades_Clas');
    const baseRow = document.querySelector('.filaclonableunica_actividades_Class'); // Asegúrate de tener una fila base definida en el HTML
    // Configuración para el modal de eliminación

    const deleteModal = new bootstrap.Modal(document.querySelector('.deletemodalActividadesUniquemodal'), {});
    const deleteModalTurno = new bootstrap.Modal(document.querySelector('.deletemodalActividadesUniquemodal_turnos'), {});
    const ids = 0;
    // Configuración para alternar botones y costos
    setupToggleButton(originalTableContainer);  
    setupToggleButtonCosto(originalTableContainer,tbody);

    // Desactivar selects al cargar la página (esto es opcional según el comportamiento deseado)
    document.querySelectorAll(".select_res").forEach(select => select.selectedIndex = -1);

    // Lógica para añadir nuevas filas normales
    document.querySelector("button[name='button_nueva_fila_actividades']").addEventListener('click', function () {
        addNormalRow(baseRow, tbody, deleteModal, divContainerActividades);
        initializeTable(originalTableContainer, divContainerActividades); // Recalcular y añadir event listeners después de agregar una fila
    });

    // Lógica para añadir nuevas filas de turno
    document.querySelector("button[name='button_nuevo_turno']").addEventListener('click', function () {
        addTurnRow(tbody, deleteModalTurno, divContainerActividades);
        initializeTable(originalTableContainer, divContainerActividades); // Recalcular y añadir event listeners después de agregar una fila
    });

    // Guardar la tabla clonada
    const cloneButton = document.querySelector('button[name="button_guardar_tabla_actividades"]');
    cloneButton.addEventListener('click', function() {
        cloneTable(baseRow,deleteModal,ids, deleteModalTurno);
        setTimeout(() => {
            location.reload(true);
        }, 1000);
    });


    
    // Llamar a la función de cálculo del costo unitario al cargar la página
    calculateCostoKamatiUnitario(tbody, originalTableContainer);

    checkFactoresIndependent(baseRow, divContainerActividades,tbody);

    // Configurar el checkbox para el input factor en la fila clonable
    setupCheckboxToggle(baseRow, divContainerActividades, tbody);

    // // Inicializar la tabla con los event listeners y cálculos para las filas ya presentes
    // initializeTable(originalTableContainer, divContainerActividades);
    // toggleTableDisplay();
    initializeTable(originalTableContainer, divContainerActividades);
});