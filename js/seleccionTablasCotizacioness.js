let tablaActual = ''; // Variable para almacenar la tabla actual seleccionada

// Función para inicializar eventos
$(document).ready(function() {
    // Evento cuando se cambia el valor del select
    $('#select_tipo_tabla').change(function() {
        let nuevaTabla = $(this).val(); // Obtener el valor seleccionado

        // Verificar si hay datos en la tabla actual antes de cambiar
        if (hayDatosEnTabla()) {
            tablaActual = nuevaTabla; // Actualizar tabla actual
            mostrarModalConfirmacion(); // Mostrar modal de confirmación
        } else {
            tablaActual = nuevaTabla; // Actualizar tabla actual directamente
            mostrarTablaSeleccionada(); // Mostrar la tabla seleccionada
        }
    });
});

function cancelarCambioTabla() {
    $('#modalConfirmacion').modal('hide'); // Ocultar modal usando jQuery
    $('#select_tipo_tabla').val(tablaActual); // Restaurar valor anterior del select
    
    // Restaurar el índice del select a - 1 (ninguna opción seleccionada)
    const select = document.getElementById('select_tipo_tabla');
    select.selectedIndex = -1;
}

// Función para verificar si hay datos en la tabla actual
function hayDatosEnTabla() {
    // Lógica para verificar si hay datos en la tabla actual
    // Aquí puedes implementar tu lógica específica
    return true; // Ejemplo básico, siempre devuelve true
}

// Función para mostrar la tabla seleccionada
function mostrarTablaSeleccionada() {
    // Ocultar todas las tablas
    $('#tablaMateriales, #tablaActividades, #tablaMaquinaria').hide();

    // Mostrar la tabla seleccionada
    if (tablaActual === '1') {
        $('#tablaMateriales').show();
    } else if (tablaActual === '2') {
        $('#tablaActividades').show();
    } else if (tablaActual === '3') {
        $('#tablaMaquinaria').show();
    }
}