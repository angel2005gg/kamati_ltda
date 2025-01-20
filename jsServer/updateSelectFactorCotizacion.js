// updateSelectCotizaciones.js

// Función para obtener los datos de la cotización desde el servidor
async function obtenerDatosCotizacionFac() {
    try {
        const response = await fetch('../phpServer/updateSelectFactorCotizaciones.php'); // Ajusta la ruta
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const textoRespuesta = await response.text();
        console.log('Respuesta del servidor:', textoRespuesta);

        
        const datos = JSON.parse(textoRespuesta);

        // Actualizar los valores obtenidos del servidor en los campos de la UI
        if (datos.success && datos.dom) {
            // Asignar los valores de factor a los campos correspondientes
            datos.dom.forEach((factor, index) => {
                if (factor.nombre === 'FactorMo') {
                    document.getElementById('factorMoGlobal').value = factor.valor || '';
                    document.getElementById('hidden_InputFactorGlobalNameMo').value = factor.id || '';  // Asignar el ID en el campo hidden
                } else if (factor.nombre === 'FactorO') {
                    document.getElementById('factorOGlobal').value = factor.valor || '';
                    document.getElementById('hidden_InputFactorGlobalNameO').value = factor.id || '';  // Asignar el ID en el campo hidden
                } else if (factor.nombre === 'Viaticos') {
                    document.getElementById('viaticosGlobal').value = factor.valor || '';
                    document.getElementById('hidden_InputFactorGlobalNameV').value = factor.id || '';  // Asignar el ID en el campo hidden
                } else if (factor.nombre === 'Poliza') {
                    document.getElementById('polizaGlobal').value = factor.valor || '';
                    document.getElementById('hidden_InputFactorGlobalNameP').value = factor.id || '';  // Asignar el ID en el campo hidden
                }
            });
        } else {
            console.error('No se encontraron datos o hubo un error.');
        }
    } catch (error) {
        console.error('Error al obtener los datos de la cotización:', error);
    }
}

// Función que envía los datos del campo actualizado al servidor
// Función para actualizar un factor de cotización en el servidor
async function updateCotizacionJsdFac(idFactor, valor) {

    const data = {
        idFactor: idFactor, // ID del factor a actualizar
        valorFactor: valor  // Nuevo valor para el factor
    };

    try {
        // Hacer la solicitud POST al servidor
        const response = await fetch('../phpServer/updateSelectFactorCotizaciones.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data) // Convertir los datos a formato JSON
        });

        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        // Leer la respuesta como texto
        const respuestaTexto = await response.text();
        console.log('Respuesta del servidor:', respuestaTexto); // Imprimir la respuesta del servidor
    } catch (error) {
        // Manejar errores
        console.error('Error al actualizar el factor en el servidor:', error);
    }
}

// Función para agregar event listeners a los inputs que deben actualizar datos
function agregarEventListenersFactores() {
    const campos = [
        { inputId: 'factorMoGlobal', hiddenInputId: 'hidden_InputFactorGlobalNameMo' },
        { inputId: 'factorOGlobal', hiddenInputId: 'hidden_InputFactorGlobalNameO' },
        { inputId: 'viaticosGlobal', hiddenInputId: 'hidden_InputFactorGlobalNameV' },
        { inputId: 'polizaGlobal', hiddenInputId: 'hidden_InputFactorGlobalNameP' },
    ];

    campos.forEach(({ inputId, hiddenInputId }) => {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener('change', () => {
                const idFactor = document.getElementById(hiddenInputId).value;
                const valor = inputElement.value;
                updateCotizacionJsdFac(idFactor, valor);  // Llama a la función de actualización con el ID y valor
            });
        }
    });
}

// Exportar las funciones necesarias
export { obtenerDatosCotizacionFac, updateCotizacionJsdFac, agregarEventListenersFactores };