// updateSelectCotizaciones.js

// Función para obtener los datos de la cotización desde el servidor
async function obtenerDatosCotizacion() {
    try {
        const response = await fetch('../phpServer/updateSelectCotizaciones.php'); // Ajusta la ruta
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const textoRespuesta = await response.text();
        console.log('Respuesta del servidor:', textoRespuesta);

        const datos = JSON.parse(textoRespuesta);

        // Aquí puedes manejar los datos obtenidos y actualizarlos en la UI
        if (datos.success && datos.dom) {
            document.getElementById('fechaActual').value = datos.dom.fechaCotizacion;
            document.getElementById('nombreProyectoCotizacionId').value = datos.dom.nombre_cotizacion;
            document.getElementById('codigoProyectoCotizacionId').value = datos.dom.codigo_cotizacion;
            document.getElementById('nombreClienteCotizacionId').value = datos.dom.nombreCliente;
            
            // Formatear los valores para mostrarlos con puntos
            document.getElementById('txt_identificacion_usd').value = formatearConPuntos(datos.dom.dolarCotizacion);
            document.getElementById('txt_identificacion_eur').value = formatearConPuntos(datos.dom.euroCotizacion);
        } else {
            console.error('No se pudieron obtener los datos de la cotización:', datos.message);
        }
    } catch (error) {
        console.error('Error al obtener los datos de la cotización:', error);
    }
}

// Función para formatear con puntos (cada 3 dígitos)
function formatearConPuntos(valor) {
    return valor.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

// Función para guardar los datos en el servidor
async function guardarDatosEnServidor() {
    const fechaActual = document.getElementById('fechaActual').value;
    const nombreCotizacion = document.getElementById('nombreProyectoCotizacionId').value;
    const codigoCotizacion = document.getElementById('codigoProyectoCotizacionId').value;
    const nombreCliente = document.getElementById('nombreClienteCotizacionId').value;

    // Obtener los valores de USD y EUR, eliminando los puntos
    const dolar = document.getElementById('txt_identificacion_usd').value.replace(/\./g, '');
    const euro = document.getElementById('txt_identificacion_eur').value.replace(/\./g, '');

    const data = {
        fechaActual: fechaActual,
        nombreCotizacion: nombreCotizacion,
        codigoCotizacion: codigoCotizacion,
        nombreCliente: nombreCliente,
        dolar: dolar,
        euro: euro
    };

    try {
        const response = await fetch('../phpServer/updateSelectCotizaciones.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const respuesta = await response.json();

        if (respuesta.success) {
            console.log(respuesta.message);
        } else {
            console.log(respuesta.message);
        }
    } catch (error) {
        console.error('Error al guardar los datos en el servidor:', error);
    }
}

// Función para agregar los event listeners a los inputs
function agregarEventListeners() {
    const inputs = [
        'fechaActual',
        'nombreProyectoCotizacionId',
        'codigoProyectoCotizacionId',
        'nombreClienteCotizacionId',
        'txt_identificacion_usd',
        'txt_identificacion_eur'
    ];

    inputs.forEach((id) => {
        const inputElement = document.getElementById(id);
        if (inputElement) {
            inputElement.addEventListener('input', () => {
                guardarDatosEnServidor();  // Ejecuta la función cuando se cambie el valor del input
            });
        }
    });
}

// Exportar las funciones
export { obtenerDatosCotizacion, guardarDatosEnServidor, agregarEventListeners };