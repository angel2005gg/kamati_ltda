export async function deleteRowFromServerActividades(row) {

    console.log(row);
    const idTablaActividades = row.querySelector('.class_hidden_id_uniqueAc')?.value || '';
    
    console.log('ID de tabla:', idTablaActividades);  // Verifica el valor de idTablaActividades
 
    const data = {
        id_TablaActividades: idTablaActividades
    };

    try {
        const response = await fetch('../phpServer/deleteRowTablaActividadesFilas.php', {
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