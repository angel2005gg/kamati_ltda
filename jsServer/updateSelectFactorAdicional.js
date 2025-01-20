// updateSelectCotizaciones.js

// Función para obtener los datos de la cotización desde el servidor
async function obtenerDatosCotizacionFacAd() { 
    try {
        const response = await fetch('../phpServer/updateSelectFactorAdicional.php'); // Ajusta la ruta según sea necesario
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const textoRespuesta = await response.text();
        console.log('Respuesta del servidor:', textoRespuesta); // Verifica lo que el servidor realmente está enviando

        const datos = JSON.parse(textoRespuesta);

        // Actualizar los valores obtenidos del servidor en los campos de la UI
        if (datos.success && datos.dom) {
            datos.dom.forEach((factor) => {
                if (factor.nombre === 'Siemens') {
                    document.getElementById('siemensGlobal').value = factor.valor || '';
                    document.getElementById('id_inputHidden_FactoresAdicionalesSiemens').value = factor.id || '';  
                } else if (factor.nombre === 'Pilz') {
                    document.getElementById('pilzGlobal').value = factor.valor || '';
                    document.getElementById('id_inputHidden_FactoresAdicionalesPilz').value = factor.id || '';
                } else if (factor.nombre === 'Rittal') {
                    document.getElementById('rittalGlobal').value = factor.valor || '';
                    document.getElementById('id_inputHidden_FactoresAdicionalesRittal').value = factor.id || '';
                } else if (factor.nombre === 'PhxCnt') {
                    document.getElementById('phoenixGlobal').value = factor.valor || '';
                    document.getElementById('id_inputHidden_FactoresAdicionalesPhxContact').value = factor.id || '';
                }
            });
        } else {
            console.error('No se encontraron datos o hubo un error:', datos.message || 'No se encontraron datos.');
        }
    } catch (error) {
        console.error('Error al obtener los datos de la cotización:', error);
    }
}

// Función que envía los datos del campo actualizado al servidor
async function updateCotizacionJsdFacAd(idFactor, valor) {
    const data = {
        idFactor: idFactor,
        valorFactor: valor
    };

    try {
        const response = await fetch('../phpServer/updateSelectFactorAdicional.php', {
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

        // Manejo de la respuesta del servidor después de actualizar
        const respuestaDatos = JSON.parse(respuestaTexto);
        if (!respuestaDatos.success) {
            console.error('Error al actualizar el factor:', respuestaDatos.message);
        }
    } catch (error) {
        console.error('Error al actualizar el factor en el servidor:', error);
    }
}

// Función para agregar event listeners a los inputs que deben actualizar datos
function agregarEventListenersFactoresAd() {
    const campos = [
        { inputId: 'siemensGlobal', hiddenInputId: 'id_inputHidden_FactoresAdicionalesSiemens' },
        { inputId: 'pilzGlobal', hiddenInputId: 'id_inputHidden_FactoresAdicionalesPilz' },
        { inputId: 'rittalGlobal', hiddenInputId: 'id_inputHidden_FactoresAdicionalesRittal' },
        { inputId: 'phoenixGlobal', hiddenInputId: 'id_inputHidden_FactoresAdicionalesPhxContact' },
    ];

    campos.forEach(({ inputId, hiddenInputId }) => {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener('change', () => {
                const idFactor = document.getElementById(hiddenInputId).value;
                const valor = inputElement.value;
                updateCotizacionJsdFacAd(idFactor, valor);  // Llama a la función de actualización con el ID y valor
            });
        }
    });
}

// Exportar las funciones necesarias
export { obtenerDatosCotizacionFacAd, updateCotizacionJsdFacAd, agregarEventListenersFactoresAd };