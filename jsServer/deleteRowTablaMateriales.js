export async function deleteRowFromServer(row, insertId) {

    const idTablaMateriales = row.querySelector('.id_fila_materialesTable_class_clonedUpdate')?.value || '';
    const idIdentificador = row.querySelector('.id_fila_materialesTable_class').value || '';
    console.log('ID de tabla:', idTablaMateriales);  // Verifica el valor de idTablaMateriales
    console.log('IDentificador:', idIdentificador);  // Verifica el valor de idTablaMateriales

    if (!idTablaMateriales || !idIdentificador) {
        console.error('ID de tabla o identificador faltante.');
        return;
    }

    const data = {
        id_TablaMateriales: idTablaMateriales,
        id_Identificador: idIdentificador
    };

    try {
        const response = await fetch('../phpServer/deleteRowTablaMateriales.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.status === 'success') {
            console.log(result.message);
            row.remove(); // Elimina la fila del DOM
        } else {
            console.error(result.message);
        }
    } catch (error) {
        console.error('Error en la solicitud:', error);
    }
}