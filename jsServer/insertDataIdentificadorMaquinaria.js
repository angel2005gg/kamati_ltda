// Función para realizar la llamada a la API y obtener el insertId
export async function insertarDatosMaquinaria(tablaMaquinaria) {
    try {
        const response = await fetch('../phpServer/insertDataIdentificadorMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombreTablaMaquinaria: document.getElementById('nombre_maquinariaIDUnique').value,
                id_hidden_table_maquinaria: document.getElementById('hiddenInputFactoresIndependientesMaquinariaIdUno').value,
                txtTotalCliente_maquinariaValor: document.getElementById('txtTotalCliente_maquinaria').value,
                txtTotalKamati_maquinariaValor: document.getElementById('txtTotalKamati_maquinaria').value,
            }),
            
        });
        
        
        const result = await response.json();

        if (result.success && result.insertId) {
            const insertId = result.insertId; // Obtiene el insertId

            // Coloca el insertId en el input oculto
            const txtHiddenIdIdentificador = tablaMaquinaria.querySelector('.txt_id_identificador_Materiales');
            if (txtHiddenIdIdentificador) {
                txtHiddenIdIdentificador.value = insertId; // Asigna el insertId como valor
                txtHiddenIdIdentificador.id = `txt_id_identificador_Materiales_${Date.now()}`; // Da un ID único
            }
            console.log(insertId);
            return insertId; // Retorna el insertId para usarlo en otras funciones
        } else {
            console.error('Error en la inserción:', result.message);
        }
    } catch (error) {
        if (error instanceof TypeError) {
            console.error('Error de red:', error);
        } else {
            console.error('Error desconocido:', error);
        }
    }
}