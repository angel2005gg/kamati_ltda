import { updateCostoKamatiUnitario } from "./updateCostoKamatiUnitarioMateriales.js";
import { updateCurrencyFormatting } from "./updateCurrencyFormatingMateriales.js";
import { addNewRow } from "./addNewRowMat.js";
import { showDeleteModal } from "./showDeleteMOdalMateriales.js";
import { assignDeleteEventListeners } from "./assignDeleteEventListenersMateriales.js";
import { setupCheckboxToggleMateriales } from "./toggleInputFactorMateriales.js";
import { checkFactoresIndependientesMaterialess } from "./checkFactoresIndependientesMateriales.js";
import { actualizarValores, iniciarListeners } from "./abreviaturasKamatiTotales.js";
import { insertarDatosMateriales } from "../jsServer/insertDataIdentificadorMateriales.js";
import { insertarFactoresIndependientes } from "../jsServer/insertDataFactoresIndependientes.js";
import { insertarFactoresAdicionalesMat } from "../jsServer/insertDataFactoresAdicionalesMateriales.js";
import { insertarIdsDatosMateriales } from "../jsServer/insertIdsIdentificadorMateriales.js";
import { saveTableData } from "../jsServer/insertDataMaterialesTable.js";
import { saveSingleRowData } from "../jsServer/insertFilaDataMaterialesAdicional.js";



export async function saveTable(deleteModal, baseRow, ids) {
    // Recoger los valores de los inputs de la tabla original

    // Clonar el contenedor de la tabla de materiales
    const originalTableContainer = document.querySelector('.table_materialesClas');
    const clonedTableContainer = originalTableContainer.cloneNode(true);
    const someElementTable = clonedTableContainer.querySelector('.table_materialesClass');
    if (someElementTable) {
        someElementTable.classList.remove('table_original_materiales_class_unique');
        someElementTable.classList.add('tabla_tbody_unique_cloned');
    }

    const inputFactorIndependiente = clonedTableContainer.querySelector('.hiddenInputFactoresIndependientesClasess');
    if (inputFactorIndependiente) {
        inputFactorIndependiente.classList.add('hiddenInputFactoresIndependientesUniqueCloned');
    }

    const classNombreTablaMateriales = clonedTableContainer.querySelector('.nombre_table-materialesClass');
    if (classNombreTablaMateriales) {
        classNombreTablaMateriales.classList.add('nombre_tabla_unique_cloned_class');
    }
    const classFactorMO = clonedTableContainer.querySelector('.factor_MoClassMateriales');
    if (classFactorMO) {
        classFactorMO.classList.add('factor_Mo_unique_cloned_class');
    }
    const classFactorO = clonedTableContainer.querySelector('.factor_OClassMateriales');
    if (classFactorO) {
        classFactorO.classList.add('factor_O_unique_cloned_class');
    }
    const classFactorv = clonedTableContainer.querySelector('.factor_VClassMateriales');
    if (classFactorv) {
        classFactorv.classList.add('factor_V_unique_cloned_class');
    }
    const classFactoPo = clonedTableContainer.querySelector('.factor_polizaClassMateriales');
    if (classFactoPo) {
        classFactoPo.classList.add('factor_Po_unique_cloned_class');
    }
    const classFactorSm = clonedTableContainer.querySelector('.factor_siemensClassMateriales');
    if (classFactorSm) {
        classFactorSm.classList.add('factor_Sm_unique_cloned_class');
    }
    const classFactorPilz = clonedTableContainer.querySelector('.factor_pilzClassMateriales');
    if (classFactorPilz) {
        classFactorPilz.classList.add('factorPilz_unique_cloned_class');
    }
    const classFactorRt = clonedTableContainer.querySelector('.factor_rittalClassMateriales');
    if (classFactorRt) {
        classFactorRt.classList.add('factor_Rt_unique_cloned_class');
    }
    const classFactorPx = clonedTableContainer.querySelector('.factor_phoenixcontactClassMateriales');
    if (classFactorPx) {
        classFactorPx.classList.add('factor_Px_unique_cloned_class');
    }
    const classTrMaterialesCabeza = clonedTableContainer.querySelector('.classNormal_uniqueMaterial');
    if (classTrMaterialesCabeza) {
        classTrMaterialesCabeza.classList.add('classNormal_Material_clonedUnique');
    }
    const classTrMateriales = clonedTableContainer.querySelector('.trClassMateriales');
    if (classTrMateriales) {
        classTrMateriales.classList.add('class_Cloned_trMateriales_unique');
    }


    const txtTotalKamatiMateriales = clonedTableContainer.querySelector('.txtTotalKamatiMaterialesClass');
    const txtTotalClienteMateriales = clonedTableContainer.querySelector('.txtTotalClienteMaterialesClass');

    if (txtTotalKamatiMateriales && txtTotalClienteMateriales) {
        txtTotalKamatiMateriales.classList.add('totalUnique_kamati_class_cloned');
        txtTotalClienteMateriales.classList.add('totalUnique_cliente_class_cloned');
    }



    // Generar un nuevo id único para el clon
    const newId = `id_Mat${ids}`;  // Genera un ID único
    clonedTableContainer.id = newId;  // Asigna el ID al contenedor
    clonedTableContainer.classList.add('tablaIdentificadorCloned');  // Añade la clase a la tabla clonada

    // Verifica que el ID se ha asignado correctamente antes de llamar a la función

    const rows = clonedTableContainer.querySelectorAll('tbody tr');

    // Llama a la función pasar el ID dinámico generado
    if (ids == 0) {
        const insertId = await insertarDatosMateriales(clonedTableContainer);
        const newId = `id_Mat${insertId}`;
        console.log(insertId); // Genera un ID único
        clonedTableContainer.id = newId;  // Asigna el ID al contenedor

        if (insertId) {
            await insertarFactoresIndependientes(insertId, clonedTableContainer);
            await insertarFactoresAdicionalesMat(insertId, clonedTableContainer);
            await saveTableData(insertId, clonedTableContainer);

        }
    } else {
        // Pasa el ID dinámico (newId) y el valor de ids a la función insertarIdsDatosMateriales
        requestAnimationFrame(async () => {

            await insertarIdsDatosMateriales(ids, baseRow, deleteModal);
        });
    }


    // Recorre todas las filas y aplica las clases a cada una
    rows.forEach((row) => {
        // Agrega la clase a cada fila
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

    // Eliminar el botón con nombre "button_guardar_tabla_materiales" del clon
    const saveButton = clonedTableContainer.querySelector('button[name="button_guardar_tabla_materiales"]');
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
    const nombreTableMaquinaria = originalTableContainer.querySelector('.nombre_table-materialesClass');
    if (nombreTableMaquinaria) {
        nombreTableMaquinaria.value = '';
    }
    originalTableBody.innerHTML = ''; // Elimina todas las filas de la tabla original


    // Añadir una fila en blanco en la tabla original
    addNewRow(originalTableBody, baseRow, deleteModal, originalTableContainer);

    originalTableBody.querySelectorAll('input, textarea').forEach(input => input.value = '');
    originalTableBody.querySelectorAll('select').forEach(select => select.selectedIndex = -1);
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

    // Asignar event listeners a los inputs y selects en el contenedor clonado
    function addListenersToClonedTable(container, ids) {
        const tableBodys = container.querySelector('tbody');
        container.querySelectorAll('tbody tr').forEach(row => {
            const deleteButton = row.querySelector('.delete-btn');

            deleteButton.addEventListener('click', () => {
                console.log('Valor de ids antes de showDeleteModal:', ids);
                // Mostrar el modal de eliminación
                showDeleteModal(row, deleteModal, container, tableBodys, ids);
            });

            // Asignar event listeners a los inputs y textareas de la fila clonada
            row.querySelectorAll('textarea, input').forEach(input => {
                input.addEventListener('input', (event) => {
                    const currentRow = event.currentTarget.closest('tr'); // Obtiene la fila actual
                    updateCostoKamatiUnitario(currentRow, container, tableBodys); // Actualiza el costo para esta fila
                    checkFactoresIndependientesMaterialess(currentRow, container, tableBodys); // Verifica factores independientes
                });
            });

            // Asignar event listeners a los selects de la fila clonada
            row.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', (event) => {
                    const currentRow = event.currentTarget.closest('tr'); // Obtiene la fila actual
                    updateCostoKamatiUnitario(currentRow, container, tableBodys); // Recalcula los costos basados en el select
                    updateCurrencyFormatting(select); // Actualiza el formateo de moneda si es necesario
                    checkFactoresIndependientesMaterialess(currentRow, container, tableBodys); // Verifica factores independientes
                });
            });

            // Verificar los factores independientes en cada fila justo después de clonar
            checkFactoresIndependientesMaterialess(row, container, tableBodys);
            setupCheckboxToggleMateriales(row, container, tableBodys); // Asegúrate de que se pasan los parámetros correctos
        });
    }
    // Selecciona el div donde quieres insertar la tabla antes
    const referenciaDiv = document.getElementById('idTablaAbreviaturasKamati-ID');

    // Añadir márgenes al contenedor de la tabla clonada
    clonedTableContainer.style.marginBottom = '100px'; // Espacio debajo de la tabla clonada
    // Inserta la tabla clonada antes del div de referencia
    document.body.insertBefore(clonedTableContainer, referenciaDiv);

    addListenersToClonedTable(clonedTableContainer, ids);
    actualizarValores();
    iniciarListeners(); // Luego asignar los listeners
    // Reasigna el event listener al botón para añadir una nueva fila normal en la tabla clonada
    const newAddRowButton = clonedTableContainer.querySelector('button[name="button_nueva_fila_materiales"]');
    if (newAddRowButton) {
        newAddRowButton.addEventListener('click', async function () {
            // Llama a la función para añadir una nueva fila
            const tableBody = clonedTableContainer.querySelector('tbody');
            try {
                // Espera a que la promesa de addNewRow se resuelva
                const newRow = await addNewRow(tableBody, baseRow, deleteModal, clonedTableContainer);
                const costoElements = newRow.querySelectorAll('.cost-kamati-total');
                const abreviaturaElements = newRow.querySelectorAll('.abreviatura-lista');
                const costoElementsUtilidad = newRow.querySelectorAll('.value-total-input');

                if (newRow) {
                    newRow.classList.add('clonedValoresKamatiFila');
                    // Llama a la función saveSingleRowData para enviar los datos al servidor
                    const insertId = ids; // Cambia este valor si tienes un ID real que usar
                    saveSingleRowData(newRow, insertId);
                    // Agrega la clase a cada fila

                    // Selecciona todos los campos de costo kamati y abreviatura dentro de la fila


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
                } else {
                    console.error('La nueva fila no se creó correctamente');
                }
            } catch (error) {
                console.error('Error al agregar nueva fila:', error);
            }
        });
    }
    // Asignar funcionalidad de arrastrar y soltar a la tabla clonada
    new Sortable(clonedTableContainer.querySelector('tbody'), {
        animation: 150,
        ghostClass: 'sortable-ghost'
    });

    assignDeleteEventListeners(deleteModal, clonedTableContainer, ids);
    // Asignar eventos de eliminación
}