import { addNormalRow, addTurnRow } from './addRowsAzxs.js';
import { showDeleteModal } from './showDeleteMOdalActividadesFilas.js';
import { showDeleteModalTurno } from './showDeleteMOdalTurnoFilas.js';
import { setupToggleButton } from './toggleInputCxa.js'; // Importa la función para el toggle original
import { setupToggleButtonCosto } from './toggleInputCostosXscv.js'; // Importa la función para el toggle de costos
import { calculateCostoKamatiUnitario } from './calculateCostoKamatiUnitarioSx.js';
import { initializeTable } from './calculateSumColumns.js';
import { setupCheckboxToggle } from './toggleInputFactor.js';
import { checkFactoresIndependent } from './checkFactoresIndependientes.js';
import { actualizarValores, iniciarListeners } from './abreviaturasKamatiTotales.js';
import { insertarDatosActividades } from '../jsServer/insertDataIdentificadorActividades.js';
import { insertarFactoresIndependientesActividades } from '../jsServer/insertDataFactoresIndependientesActividades.js';
import { enviarDatosViaticosActividadesIndependientes } from '../jsServer/insertViaticosActividadesIndependientes.js';
import { enviarDatosTarifaCargosActividadesIndependientes } from '../jsServer/insertTarifaCargoActividadesIndependientes.js';
import { enviarTurnoYActividades } from '../jsServer/insertTurnosActividades.js';
import { insertarIdsDatosActividades } from '../jsServer/insertIdsIdentificadorActividades.js';
import { saveSingleRowDataTurnos } from '../jsServer/insertFilaDataTurnoAdicional.js';
import { saveSingleRowDataActividades } from '../jsServer/insertFilaDataActividadesAdicional.js';



export async function cloneTable(baseRow, deleteModal, ids, deleteModalTurno) {
    const originalTableContainer = document.querySelector('.table-actividades-Class-container');
    const clonedTableContainer = originalTableContainer.cloneNode(true);
    // Clona todo el contenedor de la tabla
    const someElementTable = clonedTableContainer.querySelector('.tableActividades_clas');
    if (someElementTable) {
        someElementTable.classList.remove('table_original_actividades_class_unique');
    }

    if (!originalTableContainer) {
        console.error('No se encontró la tabla original.');
        return;
    }
    // Guardar los valores seleccionados en los selects de la tabla original
    const nombreTablaActividades = clonedTableContainer.querySelector('.nombre_table-actividadesClass');
    const checkNActividades = clonedTableContainer.querySelector('.hiddenTableInput_actividades_unqueVal');
    const factorMo = clonedTableContainer.querySelector('.factorMoHiddenClass');
    const factorO = clonedTableContainer.querySelector('.factorOHiddenClass');
    const factorV = clonedTableContainer.querySelector('.viaticosHiddenClass');
    const factorPo = clonedTableContainer.querySelector('.polizaHiddenClaas');
    const viaticos = clonedTableContainer.querySelector('.class-viaticosHidden');
    const cargos = clonedTableContainer.querySelector('.tbody_actividades-hiddenClass');
    const tbodyAc = clonedTableContainer.querySelector('.tbodyActividades_Clas');
    const totalKamatiAc = clonedTableContainer.querySelector('.txt_total_kamatiActividadesClass');
    const totalClienteAc = clonedTableContainer.querySelector('.txt_total_clienteActividadesClass');

    if (nombreTablaActividades && checkNActividades && factorMo && factorO && factorV && factorPo && viaticos) {
        nombreTablaActividades.classList.add('nombreTablaActividades_unique_class_cloned');
        checkNActividades.classList.add('checkNActividades_unique_class_cloned');
        factorMo.classList.add('factorMoHiddenClass_unique_class_cloned');
        factorO.classList.add('factorOHiddenClass_unique_class_cloned');
        factorV.classList.add('viaticosHiddenClass_unique_class_cloned');
        factorPo.classList.add('polizaHiddenClaas_unique_class_cloned');
        viaticos.classList.add('viaticos_tobody_unique_cloned');
        cargos.classList.add('cargos_tbody_unique_cloned');
        tbodyAc.classList.add('tbodyAc_cloned_tbody_ac_trs');
        totalKamatiAc.classList.add('txt_total_kamatiActividadesClass_CLONED');
        totalClienteAc.classList.add('txt_total_clienteActividadesClass_CLONED');
    }
    // Generar un nuevo ID único para el contenedor clonado
    const newId = `id_Ac${ids}`;
    clonedTableContainer.id = newId;
    clonedTableContainer.classList.add('tablaIdentificadorCloned');
    const tabla = clonedTableContainer.querySelector('.tbodyActividades_Clas');
    const rowsAc = clonedTableContainer.querySelectorAll('.filaclonableunica_actividades_Class');
    // Llama a la función pasar el ID dinámico generado
    if (ids == 0) {
        const insertId = await insertarDatosActividades(clonedTableContainer);
        const newId = `id_Ac${insertId}`;
        console.log(insertId); // Genera un ID único
        clonedTableContainer.id = newId;  // Asigna el ID al contenedor

        if (insertId) {
            await insertarFactoresIndependientesActividades(insertId, clonedTableContainer);
            await enviarDatosViaticosActividadesIndependientes(insertId, clonedTableContainer);
            await enviarDatosTarifaCargosActividadesIndependientes(insertId, clonedTableContainer);
            setTimeout(() => {
                enviarTurnoYActividades(insertId, clonedTableContainer);
            }, 150);
        }
    } else {
        // Pasa el ID dinámico (newId) y el valor de ids a la función insertarIdsDatosMateriales
        requestAnimationFrame(async () => {
            await insertarIdsDatosActividades(ids, baseRow, deleteModal, deleteModalTurno);
        });
    }



    rowsAc.forEach((row) => {
        row.classList.add('clonedValoresKamatiFila');
        const alimetacionAc = row.querySelectorAll('.hidden_valor_total_alimentacion_class_uniqueAc');
        const transporteAc = row.querySelectorAll('.hidden_valor_total_transporte_class_uniqueAc');
        const transporteAcUtilidad = row.querySelectorAll('.hidden_valor_total_alimentacion_class_uniqueAc_utilidad');
        const alimentacionAcUtilidad = row.querySelectorAll('.hidden_valor_total_transporte_class_uniqueAc_utilidad');
        const diasKamatiValorSin = row.querySelectorAll('.valor_diasKamati_classHiddenValorAc_unique_for_clon');
        const diasClienteValorSin = row.querySelectorAll('.valor_diasCliente_classHiddenValorAc_unique_for_clon');
        const alimentacionAcHidden = row.querySelectorAll('.costoAlimentacion_hidden_uniqueclass_estadoButton');
        const transporteAcHidden = row.querySelectorAll('.class_transporteHidden_unique');


        // Aplica la clase clonedValoresKamatiAbreviations a todos los campos de abreviatura
        alimentacionAcHidden.forEach((element) => {
            element.classList.add('clonedAlimentacionClassUniqueHiddenn');
        });
        transporteAcHidden.forEach((element) => {
            element.classList.add('clonedTransporteClassUniqueHiddenn');
        });
        transporteAcUtilidad.forEach((element) => {
            element.classList.add('clonedTransporteClassUniqueUtilidad');
        });
        alimentacionAcUtilidad.forEach((element) => {
            element.classList.add('clonedAlimentacionClassUniqueUtilidad');
        });
        alimetacionAc.forEach((element) => {
            element.classList.add('clonedAlimentacionClassUnique');
        });
        transporteAc.forEach((element) => {
            element.classList.add('clonedTransporteClassUnique');
        });
        diasKamatiValorSin.forEach((element) => {
            element.classList.add('clonedValoresKamati');
        });
        diasClienteValorSin.forEach((element) => {
            element.classList.add('clonedValoresCliente');
        });

        // Selecciona todos los campos de costo kamati y abreviatura dentro de la fila
        const abreviaturaElements = row.querySelectorAll('.abreviaturas_nomClass');

        // Aplica la clase clonedValoresKamatiAbreviations a todos los campos de abreviatura
        abreviaturaElements.forEach((element) => {
            element.classList.add('clonedValoresKamatiAbreviations');
        });
    });


    const selectValues = Array.from(originalTableContainer.querySelectorAll('select')).map(select => select.value);


    // Limpia los valores de los inputs y selects, excluyendo los que están dentro de hidden_ac_div o tienen la clase 'nows_class'
    originalTableContainer.querySelectorAll('input, textarea').forEach(input => {
        if (!input.closest('.hidden_ac_div') && !input.classList.contains('nows_class')) {
            input.value = ''; // Limpia solo si no está dentro de hidden_ac_div o no tiene la clase 'nows_class'
        }
    });
    originalTableContainer.querySelectorAll('select').forEach(select => {
        if (!select.closest('.hidden_ac_div')) {
            select.selectedIndex = -1; // Solo limpiar si no está dentro de hidden_ac_div
        }
    });
    clonedTableContainer.querySelectorAll('.numberInput').forEach(textarea => {
        textarea.addEventListener('input', function () {
            const value = this.value;
            let number = value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

            // Formatear el número con puntos cada tres dígitos
            let formattedNumber = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Actualizar el valor del textarea con el número formateado
            this.value = formattedNumber;
        });
    });
    originalTableContainer.querySelectorAll('.mantener-valor').forEach(input => input.value = 'false');
    // Establece un nuevo ID para el contenedor clonado


    // Selecciona el div donde quieres insertar la tabla antes
    const referenciaDiv = document.getElementById('idTablaAbreviaturasKamati-ID');

    // Inserta la tabla clonada antes del div de referencia
    document.body.insertBefore(clonedTableContainer, referenciaDiv);
    // Elimina el botón de guardar de la tabla clonada
    const saveButton = clonedTableContainer.querySelector('button[name="button_guardar_tabla_actividades"]');
    if (saveButton) {
        saveButton.remove();
    }
    // Reasigna los valores seleccionados en los selects de la tabla clonada
    const clonedSelects = clonedTableContainer.querySelectorAll('select');
    clonedSelects.forEach((select, index) => {
        select.value = selectValues[index]; // Asigna el valor original
    });
    const tableBody = clonedTableContainer.querySelector('.tbodyActividades_Clas');

    // Inicializa Sortable en el tbody de la tabla clonada
    initializeSortable(clonedTableContainer);
    // Reasigna los event listeners para la tabla clonada
    reassignEventListeners(baseRow, clonedTableContainer, clonedTableContainer, ids, deleteModal, deleteModalTurno);
    // Aplica el formateo de puntos a los inputs de la tabla clonada
    applyFormattingToInputs(clonedTableContainer);
    // Inicializa los botones de toggle para la tabla clonada
    setupToggleButton(clonedTableContainer); // Inicializa el toggle de input original
    setupToggleButtonCosto(clonedTableContainer, tableBody); // Inicializa el toggle de input para costos
    setupCheckboxToggle(baseRow, clonedTableContainer, tableBody);
    // Limpia la tabla original, dejando solo las filas de normal y turno
    clearOriginalTable(baseRow, originalTableContainer, clonedTableContainer, deleteModal, deleteModalTurno);
    calculateCostoKamatiUnitario(tableBody, clonedTableContainer);
    initializeTable(tableBody, clonedTableContainer);


}

// Función para limpiar la tabla original, dejando solo las filas de normal y turno
function clearOriginalTable(baseRow, originalTableContainer, clonedTableContainer, deleteModal, deleteModalTurno) {
    const tbody = originalTableContainer.querySelector('.tbodyActividades_Clas');

    if (tbody) {
        tbody.innerHTML = ''; // Limpiar todas las filas del tbody
        // Agregar fila de turno y normal
        addTurnRow(tbody, deleteModalTurno, clonedTableContainer);
        addNormalRow(baseRow, tbody, deleteModal, clonedTableContainer);
        initializeTable(tbody, clonedTableContainer);


    }
}

// Función para inicializar Sortable en el tbody de la tabla clonada
function initializeSortable(tableContainer) {
    const tbody = tableContainer.querySelector('.tbodyActividades_Clas');
    if (tbody) {
        Sortable.create(tbody, {
            animation: 150,
            handle: 'tr'
        });
    } else {
        console.error('No se encontró el tbody en la tabla clonada.');
    }
}

// Función para reasignar los event listeners a la tabla clonada
async function reassignEventListeners(baseRow, tableContainer, clonedTableContainer, insertId, deleteModal, deleteModalTurno) {
    const tbody = tableContainer.querySelector(".tbodyActividades_Clas");



    // Reasigna los event listeners para los botones de eliminar en la tabla clonada
    const deleteButtons = tableContainer.querySelectorAll('.delete-btn');
    deleteButtons.forEach(deleteButton => {
        deleteButton.addEventListener('click', function () {
            const rowToDelete = deleteButton.closest('.filaclonableunica_actividades_Class .clonedValoresKamatiFila');
            showDeleteModal(rowToDelete, deleteModal, clonedTableContainer, tbody);

        });
    });
    // Reasigna los event listeners para los botones de eliminar en la tabla clonada
    const deleteButtonsTurno = tableContainer.querySelectorAll('.borrar_turno_Ac_class');
    deleteButtonsTurno.forEach(deleteButtonTurn => {
        deleteButtonTurn.addEventListener('click', function () {
            const rowToDelete = deleteButtonTurn.closest('.tr_new_tbody_turnounique_Class');
            showDeleteModalTurno(rowToDelete, deleteModalTurno, clonedTableContainer, tbody);

        });
    });

    // Reasigna el event listener al botón para añadir una nueva fila normal en la tabla clonada
    const addNormalRowButton = tableContainer.querySelector('button[name="button_nueva_fila_actividades"]');
    if (addNormalRowButton) {
        addNormalRowButton.addEventListener('click', function () {
            try {
                const newRow = addNormalRow(baseRow, tbody, deleteModal, clonedTableContainer);
                console.log(newRow);
                const abreviaturasNom = newRow.querySelectorAll('.abreviaturas_nomClass');
                const textareaToModify = newRow.querySelectorAll('.valor_diasKamati_classHiddenValorAc_unique_for_clon');
                const costoElementsUtilidad = newRow.querySelectorAll('.valor_diasCliente_classHiddenValorAc_unique_for_clon');
                const costoAlimentacion = newRow.querySelectorAll('.costoAlimentacion_hidden_uniqueclass_estadoButton');
                const totalAlimentacion = newRow.querySelectorAll('.hidden_valor_total_alimentacion_class_uniqueAc');
                const totalAlimentacionUtilidad = newRow.querySelectorAll('.hidden_valor_total_alimentacion_class_uniqueAc_utilidad');
                const costoTransporte = newRow.querySelectorAll('.class_transporteHidden_unique');
                const totalTransporte = newRow.querySelectorAll('.hidden_valor_total_transporte_class_uniqueAc');
                const totalTransporteUtilidad = newRow.querySelectorAll('.hidden_valor_total_transporte_class_uniqueAc_utilidad');
                if (newRow) {
                    setTimeout(() => {
                        saveSingleRowDataActividades(newRow);
                    }, 200);
                    setTimeout(() => {
                        // Añade una clase a los elementos seleccionados
                        if (newRow) {
                            newRow.classList.add('clonedValoresKamatiFila');

                            abreviaturasNom.forEach((element) => {
                                element.classList.add('clonedValoresKamatiAbreviations');
                            });
                            costoAlimentacion.forEach((element) => {
                                element.classList.add('clonedAlimentacionClassUniqueHiddenn');
                            });
                            totalAlimentacion.forEach((element) => {
                                element.classList.add('clonedAlimentacionClassUnique');
                            });
                            totalAlimentacionUtilidad.forEach((element) => {
                                element.classList.add('clonedAlimentacionClassUniqueUtilidad');
                            });
                            costoTransporte.forEach((element) => {
                                element.classList.add('clonedTransporteClassUniqueHiddenn');
                            });
                            totalTransporte.forEach((element) => {
                                element.classList.add('clonedTransporteClassUnique');
                            });
                            totalTransporteUtilidad.forEach((element) => {
                                element.classList.add('clonedTransporteClassUniqueUtilidad');
                            });
        
                            // Aplica la clase clonedValoresKamati a todos los campos de costo kamati
                            textareaToModify.forEach((element) => {
                                element.classList.add('clonedValoresKamati');
                            });
                            // Aplica la clase clonedValoresKamati a todos los campos de costo utilidad total 
                            costoElementsUtilidad.forEach((element) => {
                                element.classList.add('clonedValoresCliente');
                            });
                        }
                    }, 350);
                    // Selecciona el select y textarea en la nueva fila
                    
                    
                } else {
                    console.error('La nueva fila no se creó correctamente');
                }
            } catch (error) {
                            console.error('Error al agregar nueva fila:', error);
                        }
            // Llama a la función para añadir una nueva fila
            // Verifica que newRow no sea indefinido
            
        });
    }
    // Reasigna el event listener al botón para añadir una nueva fila tipo turno en la tabla clonada
    const addTurnRowButton = tableContainer.querySelector('button[name="button_nuevo_turno"]');
    if (addTurnRowButton) {
        addTurnRowButton.addEventListener('click', function () {
            const newTurn = addTurnRow(tbody, deleteModalTurno, clonedTableContainer);
            saveSingleRowDataTurnos(newTurn, insertId);
           

        });
    }

    clonedTableContainer.querySelectorAll('tbody tr').forEach(row => {

        row.querySelectorAll('textarea, input').forEach(input => {
            input.addEventListener('input', (event) => {
                const currentRow = event.currentTarget.closest('tr'); // Obtiene la fila actual
                checkFactoresIndependent(currentRow, clonedTableContainer, tbody);
                setupCheckboxToggle(currentRow, clonedTableContainer, tbody);
            });
        });

        // Asignar event listeners a los selects de la fila clonada
        row.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', (event) => {
                const currentRow = event.currentTarget.closest('tr'); // Obtiene la fila actual
                checkFactoresIndependent(currentRow, clonedTableContainer, tbody);
                setupCheckboxToggle(currentRow, clonedTableContainer, tbody);
            });
        });

        checkFactoresIndependent(baseRow, clonedTableContainer, tbody);
        actualizarValores();
        iniciarListeners();
    });
}
// Función para aplicar el formateo de puntos a los inputs de la tabla clonada
function applyFormattingToInputs(tableContainer) {
    const valorInputs = tableContainer.querySelectorAll('.valor-input');

    valorInputs.forEach(input => {
        formatInputValue(input); // Formatear al cargar la página

        input.addEventListener('input', function () {
            const cursorPosition = input.selectionStart;
            const originalLength = input.value.length;

            // Eliminar cualquier carácter no numérico
            let cleanValue = input.value.replace(/[^0-9]/g, '');
            let formattedValue = '';

            // Añadir puntos de miles
            for (let i = cleanValue.length; i > 0; i -= 3) {
                formattedValue = cleanValue.substring(i - 3, i) + (i !== cleanValue.length ? '.' : '') + formattedValue;
            }

            input.value = formattedValue;

            // Ajustar la posición del cursor
            const newLength = formattedValue.length;
            const newCursorPosition = cursorPosition + (newLength - originalLength);
            input.setSelectionRange(newCursorPosition, newCursorPosition);
        });

        input.addEventListener('focusout', function () {
            formatInputValue(input); // Formatear cuando se pierde el enfoque
        });
    });

    function formatInputValue(input) {
        let cleanValue = input.value.replace(/[^0-9]/g, '');
        let formattedValue = '';

        for (let i = cleanValue.length; i > 0; i -= 3) {
            formattedValue = cleanValue.substring(i - 3, i) + (i !== cleanValue.length ? '.' : '') + formattedValue;
        }

        input.value = formattedValue;
    }
}