import { cloneTable } from '../js/saveTablesAzcx.js';


async function fetchAndSaveTableDataActividades() {
    try {
        const baseRow = document.getElementById('fila-clonable');
        const deleteModal = new bootstrap.Modal(document.querySelector('.deletemodalActividadesUniquemodal'), {});
        const deleteModalTurno = new bootstrap.Modal(document.querySelector('.deletemodalActividadesUniquemodal_turnos'), {});

        console.log(baseRow);
        const response = await fetch('../phpServer/updateSelectIdentificadorActividades.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            }
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor: ' + response.statusText);
        }

        const ids = await response.json();

        if (Array.isArray(ids) && ids.length > 0) {
            ids.forEach(id => {
                console.log(`Llamando a saveTable con ID: ${id}`);
                cloneTable(baseRow,deleteModal,id, deleteModalTurno);
            });
        } else {
            console.error('No se encontraron IDs o la respuesta es inválida');
        }
    } catch (error) {
        console.error('Error al obtener los IDs:', error);
    }
}

export { fetchAndSaveTableDataActividades };