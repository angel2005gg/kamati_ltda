import { updateCostoKamatiUnitarioMaquinaria } from "./updateCostoKamatiUnitarioMaquinaria.js";
import { calculateTotalsMaquinaria } from "./calculateTotalsMaquinaria.js";
import { updateCurrencyFormatting } from "./updateCurrencyFormatingMateriales.js";
import { addNewRowMaquinaria } from "./addNewRowMaquinaria.js";
import { showDeleteModalMaquinaria } from "./showDeleteMOdalMaquinaria.js";
import { assignDeleteEventListenersMaquinaria } from "./assignDeleteEventListenersMaquinaria.js";
import { setupCheckboxToggleMaquinaria } from "./toggleInputFactorMaquinaria.js";
import { checkFactoresIndependientesMaquinaria } from "./checkFactoresIndependientesMaquinaria.js";
import { actualizarValores, iniciarListeners } from './abreviaturasKamatiTotales.js';
import { insertarDatosMaquinaria } from '../jsServer/insertDataIdentificadorMaquinaria.js';
import { insertarFactoresIndependientesMaquinaria } from '../jsServer/insertDataFactoresIndependientesMaquinaria.js';
import { insertarFactoresAdicionalesMaquinaria } from '../jsServer/insertDataFactoresAdicionalesMaquinaria.js';
import { saveTableDataMaquinaria } from '../jsServer/insertDataMaquinariaTable.js';
import { insertarIdsDatosMaquinaria } from '../jsServer/insertIdsIdentificadorMaquinaria.js';
import { saveSingleRowDataMaquinaria } from '../jsServer/insertFilaDataMaquinariaAdicional.js';

export async function saveTableMaquinaria(deleteModal, baseRow, ids) {
    // Clonar el contenedor de la tabla de maquinaria
    const originalTableContainer = document.querySelector('.table_maquinariaClas');
    const clonedTableContainer = originalTableContainer.cloneNode(true);
    const someElementTable = clonedTableContainer.querySelector('.table_maquinariaClass');
    if (someElementTable) {
        someElementTable.classList.remove('table_original_maquinaria_class_unique');
    }

    const nombreMaquinaria = clonedTableContainer.querySelector('.nombre_table-maquinariaClass');
    const factorMo = clonedTableContainer.querySelector('.factor_MoClassMaquinariaUnique');
    const factorO = clonedTableContainer.querySelector('.factor_OClassMaquinariaUnique');
    const factorV = clonedTableContainer.querySelector('.factor_VClassMaquinariaUnique');
    const factorP = clonedTableContainer.querySelector('.factor_polizaClassMaquinariaUnique');
    const factorSm = clonedTableContainer.querySelector('.factor_siemensClassMaquinariaUnique');
    const factorPl = clonedTableContainer.querySelector('.factor_pilzClassMaquinariaUnique');
    const factorRt = clonedTableContainer.querySelector('.factor_rittalClassMaquinariaUnique');
    const factorPc = clonedTableContainer.querySelector('.factor_phoenixcontactClassMaquinariaUnique');
    const inputHiddenFactoresInd = clonedTableContainer.querySelector('.hiddenInputFactoresIndependientesClasMaquinariaClassUnique');
    const tableMaquinaria = clonedTableContainer.querySelector('.table_maquinariaClass');
    const trMaquinaria = clonedTableContainer.querySelector('.trClassMquinaria');
    const totalKamatiMaquinaria = clonedTableContainer.querySelector('.txtTotalKamatiMaquinaria');
    const totalClienteMaquinaria = clonedTableContainer.querySelector('.txtTotalClienteMaquinaria');


    
    if (nombreMaquinaria && factorMo && factorO && factorV && factorP && factorSm && factorPl && factorRt && factorPc && tableMaquinaria && trMaquinaria && totalKamatiMaquinaria && totalClienteMaquinaria) {
        nombreMaquinaria.classList.add('nombre_table_maquinariaClass_clonedUnique');
        factorMo.classList.add('factor_MoClassMaquinariaUnique_cloned');
        factorO.classList.add('factor_OClassMaquinariaUnique_cloned');
        factorV.classList.add('factor_VClassMaquinariaUnique_cloned');
        factorP.classList.add('factor_polizaClassMaquinariaUnique_cloned');
        factorSm.classList.add('factor_siemensClassMaquinariaUnique_cloned');
        factorPl.classList.add('factor_pilzClassMaquinariaUnique_cloned');
        factorRt.classList.add('factor_rittalClassMaquinariaUnique_cloned');
        factorPc.classList.add('factor_phoenixcontactClassMaquinariaUnique_cloned');
        inputHiddenFactoresInd.classList.add('input_hidden_factoresIndependiente_clones_unique');
        tableMaquinaria.classList.add('table_original_maquinaria_clonedUnique');
        trMaquinaria.classList.add('trClassMquinaria_unique_cloned');
        totalKamatiMaquinaria.classList.add('txtTotalKamatiMaquinaria_cloned');
        totalClienteMaquinaria.classList.add('txtTotalClienteMaquinaria_cloned');
    }
    // Generar un nuevo ID único para el contenedor clonado
    const newId = `id_Maq${ids}`;
    clonedTableContainer.id = newId;
    clonedTableContainer.classList.add('tablaIdentificadorCloned');
     // Llama a la función pasar el ID dinámico generado
     if (ids == 0) {
        const insertId = await insertarDatosMaquinaria(clonedTableContainer);
        const newId = `id_Maq${insertId}`; 
        console.log(insertId); // Genera un ID único
        clonedTableContainer.id = newId;  // Asigna el ID al contenedor

        if (insertId) {
            await insertarFactoresIndependientesMaquinaria(insertId, clonedTableContainer);
            await insertarFactoresAdicionalesMaquinaria(insertId, clonedTableContainer);
            await saveTableDataMaquinaria(insertId, clonedTableContainer);
        }
    } else {
        // Pasa el ID dinámico (newId) y el valor de ids a la función insertarIdsDatosMateriales
        requestAnimationFrame(async () => {
            await insertarIdsDatosMaquinaria(ids, baseRow, deleteModal);
        });
    }

    // // Seleccionamos la fila (tr) en lugar de un campo específico
    // // Selecciona todas las filas dentro del tbody
    const rows = clonedTableContainer.querySelectorAll('tbody tr');

    rows.forEach((row) => {
        row.classList.add('clonedValoresKamatiFila');

        // Selecciona todos los campos de costo kamati y abreviatura dentro de la fila
        const costoElements = row.querySelectorAll('.cost-kamati-total');
        const abreviaturaElements = row.querySelectorAll('.abreviatura-lista');
        const costoElementsUtilidad = row.querySelectorAll('.value-total-input');
        

        // Aplica la clase clonedValoresKamatiAbreviations a todos los campos de abreviatura
        abreviaturaElements.forEach((element) => {
            element.classList.add('clonedValoresKamatiAbreviations');
        });

        // Aplica la clase clonedValoresKamati a todos los campos de costo kamati
        costoElements.forEach((element) => {
            element.classList.add('clonedValoresKamati');
        });
        // Aplica la clase clonedValoresKamati a todos los campos de costo utilidad total 
        costoElementsUtilidad.forEach((element) => {
            element.classList.add('clonedValoresCliente');
        });
    });
    

    // Eliminar el botón con nombre "button_guardar_tabla_maquinaria" del clon
    const saveButton = clonedTableContainer.querySelector('button[name="button_guardar_tabla_maquinaria"]');
    if (saveButton) {
        saveButton.remove();
    }

    // Recoger los valores seleccionados de los selects antes de clonar
    const originalSelects = originalTableContainer.querySelectorAll('select');
    const selectedValues = [];
    originalSelects.forEach(select => {
        selectedValues.push(select.value);
    });

    // Asignar los valores seleccionados a los selects clonados
    const clonedSelects = clonedTableContainer.querySelectorAll('select');
    clonedSelects.forEach((select, index) => {
        select.value = selectedValues[index];
    });

    // Limpiar los inputs y selects de la tabla original y dejar solo una fila
    const originalTableBody = originalTableContainer.querySelector('tbody');
    const nombreTableMaquinaria = originalTableContainer.querySelector('.nombre_table-maquinariaClass');

    if (nombreTableMaquinaria) {
        nombreTableMaquinaria.value = '';
    }
    originalTableBody.innerHTML = ''; // Elimina todas las filas de la tabla original

    // Añadir una fila en blanco en la tabla original
    addNewRowMaquinaria(originalTableBody, baseRow, deleteModal, originalTableContainer);

    originalTableBody.querySelectorAll('input, textarea').forEach(input => input.value = '');
    originalTableBody.querySelectorAll('select').forEach(select => select.selectedIndex = -1);
     // ** Añadir el formateo a los campos numberInput aquí **
     clonedTableContainer.querySelectorAll('.numberInput').forEach(textarea => { 
        textarea.addEventListener('input', function() {
            const value = this.value;
            let number = value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

            // Formatear el número con puntos cada tres dígitos
            let formattedNumber = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Actualizar el valor del textarea con el número formateado
            this.value = formattedNumber;
        });
    });

    // Función para asignar event listeners y lógica a las filas clonadas
    function addListenersToClonedTable(container) {
        const tableBody = container.querySelector('tbody');

        container.querySelectorAll('tbody tr').forEach(row => {
            const deleteButton = row.querySelector('.delete-btn');
            deleteButton.addEventListener('click', () => showDeleteModalMaquinaria(row, deleteModal, container, tableBody, ids));

            // Asignar event listeners a los inputs y textareas de la fila clonada
            row.querySelectorAll('textarea, input').forEach(input => {
                input.addEventListener('input', (event) => {
                    const currentRow = event.currentTarget.closest('tr');
                    updateCostoKamatiUnitarioMaquinaria(currentRow, container, tableBody);
                    calculateTotalsMaquinaria(container, tableBody);
                    checkFactoresIndependientesMaquinaria(currentRow, container, tableBody);
                    setupCheckboxToggleMaquinaria(currentRow, container, tableBody);
                });
            });

            // Asignar event listeners a los selects de la fila clonada
            row.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', (event) => {
                    const currentRow = event.currentTarget.closest('tr');
                    updateCostoKamatiUnitarioMaquinaria(currentRow, container, tableBody);
                    calculateTotalsMaquinaria(container, tableBody);
                    updateCurrencyFormatting(select);
                    checkFactoresIndependientesMaquinaria(currentRow, container, tableBody);
                    setupCheckboxToggleMaquinaria(currentRow, container, tableBody);
                });
            });

            // Llamar a checkFactoresIndependientesMaquinaria inmediatamente después de clonar
            checkFactoresIndependientesMaquinaria(row, container, tableBody);
        });
    }

    // Llamar a la función para añadir listeners al contenedor clonado
    addListenersToClonedTable(clonedTableContainer);
    assignDeleteEventListenersMaquinaria(deleteModal, clonedTableContainer);
    actualizarValores();
    iniciarListeners();
    
    // Reasigna el event listener al botón para añadir una nueva fila normal en la tabla clonada
    const newAddRowButton = clonedTableContainer.querySelector('button[name="button_agregar_fila_Maquinaria"]');
    if (newAddRowButton) {
        newAddRowButton.addEventListener('click', function () {
            // Llama a la función para añadir una nueva fila
            const tableBody = clonedTableContainer.querySelector('tbody');
            const newRow = addNewRowMaquinaria(tableBody, baseRow, deleteModal, clonedTableContainer);
            // Verifica que newRow no sea indefinido
            if (newRow) {
                // Selecciona el select y textarea en la nueva fila
                const selectToModify = newRow.querySelector('.abreviatura-lista');
                const textareaToModify = newRow.querySelector('.cost-kamati-total');
                const cliente = newRow.querySelector('.value-total-input');
                // Añade una clase a los elementos seleccionados
                if (newRow) {
                    newRow.classList.add('clonedValoresKamatiFila');
                    saveSingleRowDataMaquinaria(newRow, ids);
                }
                if (selectToModify) {
                    selectToModify.classList.add('clonedValoresKamatiAbreviations'); // Reemplaza 'nueva-clase-select' por la clase que desees añadir
                }
                if (textareaToModify) {
                    textareaToModify.classList.add('clonedValoresKamati'); // Reemplaza 'nueva-clase-textarea' por la clase que desees añadir
                }
                if (cliente) {
                    cliente.classList.add('clonedValoresCliente'); // Reemplaza 'nueva-clase-textarea' por la clase que desees añadir
                }
            } else {
                console.error('La nueva fila no se creó correctamente');
            }
        });
    }

    // Asignar funcionalidad de arrastrar y soltar a la tabla clonada
    new Sortable(clonedTableContainer.querySelector('tbody'), {
        animation: 150,
        ghostClass: 'sortable-ghost'
    });

    const referenciaDiv = document.getElementById('idTablaAbreviaturasKamati-ID');
    // Inserta la tabla clonada antes del div de referencia
    document.body.insertBefore(clonedTableContainer, referenciaDiv);
}