import { calculateTotalsMaquinaria } from "./calculateTotalsMaquinaria.js";
import { deleteRowFromServerMaquinaria } from "../jsServer/deleteRowTablaMaquinaria.js";


export async function showDeleteModalMaquinaria(row, deleteModal, divContainer, tableBody, ids= null) {
    let rowToDelete = row; // Asignamos la fila que se desea eliminar

    // Mostramos el modal de eliminación
    deleteModal.show();

    // Obtenemos el botón de confirmación de eliminación
    const confirmDeleteButton = document.getElementById('confirmDeleteModalMaquinaria');

    // Agregamos el evento click al botón de confirmación
    confirmDeleteButton.addEventListener('click', function() {
        if (rowToDelete) {
            deleteRowFromServerMaquinaria(row, ids);
            rowToDelete.remove();   // Eliminamos la fila
            rowToDelete = null;     // Reiniciamos la variable
            deleteModal.hide();     // Ocultamos el modal
            calculateTotalsMaquinaria(divContainer, tableBody);      // Recalculamos los totales
        }
    }, { once: true });  // Usamos `{ once: true }` para asegurarnos de que el evento se ejecute solo una vez
}