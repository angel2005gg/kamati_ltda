import { saveTableMaquinaria } from '../js/saveTableMaquinaria.js';

async function fetchAndSaveTableDataMaquinaria() {
    try {
        const baseRow = document.getElementById('baseRowMaquinaria');
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModalMaquinaria'), {});

        console.log(baseRow);
        const response = await fetch('../phpServer/updateSelectIdentificadorMaquinaria.php', {
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
                saveTableMaquinaria(deleteModal, baseRow, id);
            });
        } else {
            console.error('No se encontraron IDs o la respuesta es inválida');
        }
    } catch (error) {
        console.error('Error al obtener los IDs:', error);
    }
}

export { fetchAndSaveTableDataMaquinaria };