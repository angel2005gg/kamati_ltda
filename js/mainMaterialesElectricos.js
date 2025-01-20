import { actualizarValores, iniciarListeners } from './abreviaturasKamatiTotales.js';
import { updateCostoKamatiUnitario } from './updateCostoKamatiUnitarioMateriales.js';
import { assignDeleteEventListeners } from './assignDeleteEventListenersMateriales.js';
import { updateCurrencyFormatting } from './updateCurrencyFormatingMateriales.js';
import { addNewRow } from './addNewRowMat.js';
import { setupCheckboxToggleMateriales } from './toggleInputFactorMateriales.js';
import { saveTable } from './saveTableMateriales.js';
import { checkFactoresIndependientesMaterialess } from './checkFactoresIndependientesMateriales.js';

// Llamada a la función en tu archivo principal
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(".select_reset").forEach(select => select.selectedIndex = -1);
    const divContainer = document.querySelector('.table_materialesClas');
    const saveTableButton = document.querySelector('button[name="button_guardar_tabla_materiales"]');
    const tableBody = document.getElementById('tableBody');
    const baseRow = document.getElementById('baseRow'); // Asegúrate de tener una fila base definida en el HTML
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModalMateriales'), {});
    const ids = 0;
    // Inicialización de Sortable en el cuerpo de la tabla
    new Sortable(tableBody, {
        animation: 150,
        ghostClass: 'sortable-ghost'
    });

    saveTableButton.addEventListener('click', function() {
        saveTable(deleteModal, baseRow, ids);
        // Recarga suave de la página con un pequeño retraso
        setTimeout(() => {
            location.reload(true); // true asegura que se recargue desde el servidor y no desde el caché
        }, 1000); // Espera 500ms antes de recargar
    });

    // Ejecutar updateCostoKamatiUnitario para todas las filas al cargar la página
    const rows = tableBody.querySelectorAll('tr'); // Obtiene todas las filas del cuerpo de la tabla
    rows.forEach(row => updateCostoKamatiUnitario(row, divContainer, tableBody)); // Llama a la función para cada fila

    assignDeleteEventListeners(deleteModal, divContainer);
    updateCurrencyFormatting();
    setupCheckboxToggleMateriales(baseRow, divContainer, tableBody);
    
    // Llamar a la lógica para mostrar/ocultar el div con el checkbox
    checkFactoresIndependientesMaterialess(baseRow, divContainer, tableBody);

    // Event listener para agregar una nueva fila al presionar un botón específico
    document.getElementById('button_agregar_fila').addEventListener('click', function() {
        addNewRow(tableBody, baseRow, deleteModal, divContainer); // Llama a la función para añadir una nueva fila
    });

    // Formateo de números en los campos con la clase .numberInput
    document.querySelectorAll('.numberInput').forEach(textarea => {
        textarea.addEventListener('input', function() {
            const value = this.value;
            let number = value.replace(/\D/g, ''); // Eliminar caracteres no numéricos

            // Formatear el número con puntos cada tres dígitos
            let formattedNumber = number.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            // Actualizar el valor del textarea con el número formateado
            this.value = formattedNumber;
        });
    });

    // Iniciar listeners para los elementos clonados dinámicamente
    iniciarListeners();
    // **Llamar a actualizarValores() una vez al cargar la página**
    actualizarValores();

   
});