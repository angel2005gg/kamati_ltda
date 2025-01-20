// updateSelectCotizaciones.js

// Función para eliminar los puntos de miles y obtener un número válido
function quitarPuntosMiles(valor) {
    return valor.replace(/\./g, ''); // Elimina todos los puntos
}

// Función para agregar puntos de miles a un número
function agregarPuntosMiles(valor) {
    return valor.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Función para obtener los datos de la cotización desde el servidor
async function obtenerDatosViaticos() {
    try {
        const response = await fetch('../phpServer/updateSelectViaticos.php'); // Ajusta la ruta
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const textoRespuesta = await response.text();
        console.log('Respuesta del servidor:', textoRespuesta);

        const datos = JSON.parse(textoRespuesta);

        // Verificar los valores antes de asignarlos
        console.log('Datos:', datos);

        if (datos.success && datos.dom) {
            // Iterar sobre los datos obtenidos y asignar los valores de los viáticos
            datos.dom.forEach((viatico, index) => {
                // Asignar valores a los campos de entrada
                if (index === 0) {
                    // Para el primer viático
                    document.getElementById('valorDiarioGlobalViaticos_0').value = agregarPuntosMiles(viatico.valor || '');
                    document.getElementById('id_hidden_viaticos_0').value = viatico.id || '';  // Asignar el ID del primer viático
                } else if (index === 1) {
                    // Para el segundo viático
                    document.getElementById('valorDiarioGlobalViaticos_1').value = agregarPuntosMiles(viatico.valor || '');
                    document.getElementById('id_hidden_viaticos_1').value = viatico.id || '';  // Asignar el ID del segundo viático
                }
            });

            console.log('Campos actualizados con los datos de viáticos');
        } else {
            console.error('No se encontraron datos o hubo un error.');
        }
    } catch (error) {
        console.error('Error al obtener los datos de los viáticos:', error);
    }
}

// Función que envía los datos del campo actualizado al servidor
async function updateViaticos(idViaticos, valorViaticos) {
    // Eliminar puntos de miles antes de enviar el valor al servidor
    const valorSinPuntos = quitarPuntosMiles(valorViaticos);

    const data = {
        idViaticos: idViaticos,
        valorViaticos: valorSinPuntos // Enviar el valor sin puntos
    };

    try {
        const response = await fetch('../phpServer/updateSelectViaticos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const respuestaTexto = await response.text();
        console.log('Respuesta del servidor:', respuestaTexto);
    } catch (error) {
        console.error('Error al actualizar el factor en el servidor:', error);
    }
}

// Función para agregar event listeners a los inputs que deben actualizar datos
function agregarEventListenesViaticos() {
    const campos = [
        { inputId: 'valorDiarioGlobalViaticos_0', hiddenInputId: 'id_hidden_viaticos_0' },
        { inputId: 'valorDiarioGlobalViaticos_1', hiddenInputId: 'id_hidden_viaticos_1' },
    ];

    campos.forEach(({ inputId, hiddenInputId }) => {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener('change', () => {
                const idFactor = document.getElementById(hiddenInputId).value;
                const valor = inputElement.value;

                // Eliminar puntos de miles antes de actualizar el valor
                updateViaticos(idFactor, valor);
            });
        }
    });
}

// Exportar las funciones necesarias
export { obtenerDatosViaticos, updateViaticos, agregarEventListenesViaticos };