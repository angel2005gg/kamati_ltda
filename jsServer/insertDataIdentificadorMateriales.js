// Función para realizar la llamada a la API y obtener el insertId
export async function insertarDatosMateriales(tablaMateriales) {
    try {
        const response = await fetch('../phpServer/insertDataIdentificadorMateriales.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombreTablaMateriales: document.getElementById('nombre_materialesMats').value,
                id_hidden_table_materiales: document.getElementById('hiddenInputFactoresIndependientesIdUno').value,
                txtTotalCliente_materialesValor: document.getElementById('txtTotalCliente_materiales').value,
                txtTotalKamati_materialesValor: document.getElementById('txtTotalKamati_materiales').value,
            }),
            
        });
        
        
        const result = await response.json();

        if (result.success && result.insertId) {
            const insertId = result.insertId; // Obtiene el insertId

            // Coloca el insertId en el input oculto
            const txtHiddenIdIdentificador = tablaMateriales.querySelector('.txt_id_identificador_Materiales');
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