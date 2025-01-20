import { saveTable } from '../js/saveTableMateriales.js';

async function fetchAndSaveTableData() {
    try {
        const baseRow = document.getElementById('baseRow');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModalMateriales'), {});

        console.log(baseRow);
        const response = await fetch('../phpServer/updateSelectIdentificadorMateriales.php', {
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
                saveTable(deleteModal, baseRow, id);
            });
        } else {
            console.error('No se encontraron IDs o la respuesta es inválida');
        }
    } catch (error) {
        console.error('Error al obtener los IDs:', error);
    }
}

export { fetchAndSaveTableData };