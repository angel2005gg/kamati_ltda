export async function deleteRowFromServerTurnos(row, insertId) {

    const idTablaTurnos = row.querySelector('.hidden_idId_turno_ActividadeUnique_CLASS')?.value || '';

    console.log('ID de tabla:', idTablaTurnos);  // Verifica el valor de idTablaTurnos // Verifica el valor de idTablaTurnos

    if (!idTablaTurnos) {
        console.error('ID de tabla o identificador faltante.');
        return;
    }

    const data = {
        id_TablaTurno: idTablaTurnos
    };

    try {
        const response = await fetch('../phpServer/deleteRowTablaAcTurnos.php', {
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