import { calculateTotals } from "./calculateTotalsMateriales.js";
import { deleteRowFromServer } from '../jsServer/deleteRowTablaMateriales.js';


export async function showDeleteModal(row, deleteModal, container, tableBodys, ids = null) {
    let rowToDelete = row; // Asignamos la fila que se desea eliminar

    // Mostramos el modal de eliminación
    deleteModal.show();

    // Obtenemos el botón de confirmación de eliminación
    const confirmDeleteButton = document.getElementById('confirmDeleteModalMateriales');

    // Agregamos el evento click al botón de confirmación
    confirmDeleteButton.addEventListener('click', function() {
        if (rowToDelete) {
            deleteRowFromServer(row, ids); // Llamamos a deleteRowFromServer dentro del modal
            rowToDelete.remove();   // Eliminamos la fila
            rowToDelete = null;     // Reiniciamos la variable
            deleteModal.hide();     // Ocultamos el modal
            calculateTotals(container, tableBodys);   
        }
    }, { once: true });  // Usamos `{ once: true }` para asegurarnos de que el evento se ejecute solo una vez
}