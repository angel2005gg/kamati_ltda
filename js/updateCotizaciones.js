function updateCotizacionJs() {
    // Obtener los valores de los inputs
    const fechaActual = document.getElementById('fechaActual').value;
    const nombreCliente = document.getElementById('nombreClienteCotizacionId').value;
    const nombreProyecto = document.getElementById('nombreProyectoCotizacionId').value;
    const codigoProyecto = document.getElementById('codigoProyectoCotizacionId').value;
    let dolarParaElProyecto = document.getElementById('txt_identificacion_usd').value;
    let euroParaElProyecto = document.getElementById('txt_identificacion_eur').value;

    // Eliminar puntos de los valores de dólar y euro
    dolarParaElProyecto = dolarParaElProyecto.replace(/\./g, '');
    euroParaElProyecto = euroParaElProyecto.replace(/\./g, '');

    // Obtener el ID de cotización de la sesión
    const idCotizacion = sessionStorage.getItem('id_cotizacion');

    // Verificar si el ID está disponible
    if (!idCotizacion) {
        console.error('No se encontró el ID de la cotización');
        return;
    }

    // Crear un objeto con los datos a enviar
    const data = {
        id_cotizacion: idCotizacion,
        nombre_cotizacion: nombreProyecto,
        codigo_cotizacion: codigoProyecto,
        fecha_actual: fechaActual,
        nombre_cliente: nombreCliente,
        dolarCotizacion: dolarParaElProyecto,
        euroCotizacion: euroParaElProyecto
    };

    fetch('../controlador/procesarCotizaciones.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        // Verifica el contenido de la respuesta
        return response.text(); // Cambiar a .text() para ver el HTML
    })
    .then(text => {
        console.log(text); // Muestra el contenido de la respuesta en la consola
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}