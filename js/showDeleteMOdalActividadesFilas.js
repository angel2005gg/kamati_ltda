import { initializeTable } from './calculateSumColumns.js';
import {deleteRowFromServerActividades} from '../jsServer/deleteRowTablaActividadesFilas.js';



export async function showDeleteModal(row, deleteModal, container, tableBodys, ids = null) {
    let rowToDelete = row; // Asignamos la fila que se desea eliminar

    // Mostramos el modal de eliminación
    deleteModal.show();

    // Obtenemos el botón de confirmación de eliminación
    const confirmDeleteButton = document.querySelector('.confirmar_button_deleteAc');

    // Agregamos el evento click al botón de confirmación
    confirmDeleteButton.addEventListener('click', function() {
        if (rowToDelete) {
            deleteRowFromServerActividades(row); // Llamamos a deleteRowFromServer dentro del modal
            rowToDelete.remove();   // Eliminamos la fila
            rowToDelete = null;     // Reiniciamos la variable
            deleteModal.hide();     // Ocultamos el modal
            initializeTable(tableBodys, container);   
        }
    }, { once: true });  // Usamos `{ once: true }` para asegurarnos de que el evento se ejecute solo una vez
}