export async function deleteRowFromServerMaquinaria(row, insertId) {

    const idTablaMaquinaria = row.querySelector('.id_fila_MaquinariaTable_class_clonedUpdate')?.value || '';
    const idIdentificadorMaquinaria = row.querySelector('.id_fila_MaquinariaTable_class').value || '';
    console.log('ID de tabla:', idTablaMaquinaria);  // Verifica el valor de idTablaMaquinaria
    console.log('IDentificador:', idIdentificadorMaquinaria);  // Verifica el valor de idTablaMaquinaria

    if (!idTablaMaquinaria || !idIdentificadorMaquinaria) {
        console.error('ID de tabla o identificador faltante.');
        return;
    }

    const data = {
        id_TablaMaquinaria: idTablaMaquinaria,
        id_IdentificadorMaquinaria: idIdentificadorMaquinaria
    };

    try {
        const response = await fetch('../phpServer/deleteRowTablaMaquinaria.php', {
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