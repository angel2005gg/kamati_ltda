import { updateCostoKamatiUnitarioMaquinaria } from './updateCostoKamatiUnitarioMaquinaria.js';
import { actualizarValoresCliente, iniciarListenersCliente } from './abreviaturasClienteTotales.js';
import { assignDeleteEventListenersMaquinaria } from './assignDeleteEventListenersMaquinaria.js';
import { updateCurrencyFormatting } from './updateCurrencyFormatingMateriales.js';
import { addNewRowMaquinaria } from './addNewRowMaquinaria.js';
import { setupCheckboxToggleMaquinaria } from './toggleInputFactorMaquinaria.js';
import { saveTableMaquinaria } from './saveTableMaquinaria.js';
import { checkFactoresIndependientesMaquinaria } from './checkFactoresIndependientesMaquinaria.js';

// Llamada a la función en tu archivo principal
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll(".select_reset").forEach(select => select.selectedIndex = -1);
    const divContainer = document.querySelector('.table_maquinariaClas');
    const saveTableButton = document.querySelector('button[name="button_guardar_tabla_maquinaria"]');
    const tableBody = document.getElementById('tableBodyMaquinaria');
    const baseRow = document.getElementById('baseRowMaquinaria'); // Asegúrate de tener una fila base definida en el HTML
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModalMaquinaria'), {});
    const ids = 0;
    // Inicialización de Sortable en el cuerpo de la tabla
    new Sortable(tableBody, {
        animation: 150,
        ghostClass: 'sortable-ghost'
    });
    
    saveTableButton.addEventListener('click', function() {
        
        saveTableMaquinaria(deleteModal, baseRow, ids);
        setTimeout(() => {
            location.reload(true);
        }, 1000);
        // Recarga suave de la página con un pequeño retraso
         // Espera 500ms antes de recargar
    });

    // Ejecutar updateCostoKamatiUnitario para todas las filas al cargar la página
    const rows = tableBody.querySelectorAll('tr'); // Obtiene todas las filas del cuerpo de la tabla
    rows.forEach(row => updateCostoKamatiUnitarioMaquinaria(row, divContainer, tableBody)); // Llama a la función para cada fila

    assignDeleteEventListenersMaquinaria(deleteModal, divContainer);
    updateCurrencyFormatting();
    setupCheckboxToggleMaquinaria(baseRow, divContainer, tableBody);
    // Llamar a la lógica para mostrar/ocultar el div con el checkbox
    checkFactoresIndependientesMaquinaria(baseRow, divContainer, tableBody);

    // Event listener para agregar una nueva fila al presionar un botón específico
    document.getElementById('button_agregar_fila_Maquinaria').addEventListener('click', function() {
        addNewRowMaquinaria(tableBody, baseRow, deleteModal, divContainer); // Llama a la función para añadir una nueva fila
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

    iniciarListenersCliente();
    actualizarValoresCliente();
});