function mostrarModalConfirmacion() {
    $('#modalConfirmacion').modal('show'); // Mostrar modal usando jQuery
}

// Función para cancelar el cambio de tabla


// Función para confirmar el cambio de tabla
function confirmarCambioTabla() {
    $('#modalConfirmacion').modal('hide'); // Ocultar modal usando jQuery
    mostrarTablaSeleccionada(); // Mostrar la tabla seleccionada
}