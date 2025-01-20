// Función para realizar la llamada a la API y obtener el insertId
export async function insertarDatosActividades(tablaActividades) {
    try {
        const response = await fetch('../phpServer/insertDataIdentificadorActividades.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                nombreTablaActividades: document.getElementById('nombreTablaActividades').value,
                id_hidden_table_Actividades: document.getElementById('hiddenInputFactoresIndependientesActividadesUno').value,
                txtTotalCliente_ActividadesValor: document.getElementById('txt_total_clienteActividadesIdUnique').value,
                txtTotalKamati_ActividadesValor: document.getElementById('txt_total_kamatiActividadesIdUnique').value,
            }),
            
        });
        
        
        const result = await response.json();

        if (result.success && result.insertId) {
            const insertId = result.insertId; // Obtiene el insertId

            // Coloca el insertId en el input oculto
            const txtHiddenIdIdentificador = tablaActividades.querySelector('.txt_id_identificador_Actividades');
            if (txtHiddenIdIdentificador) {
                txtHiddenIdIdentificador.value = insertId; // Asigna el insertId como valor
                txtHiddenIdIdentificador.id = `txt_id_identificador_Actividades_${Date.now()}`; // Da un ID único
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