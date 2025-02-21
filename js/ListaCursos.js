$(document).ready(function() {
    // Cerrar sugerencias al hacer clic fuera del contenedor
    $(document).click(function(e) {
        if (!$(e.target).closest('.busqueda-container').length) {
            $('#sugerencias').empty();
        }
    });

    // Evento "keyup" en el input para filtrar la tabla en tiempo real
    $('#nombre_usuario').on('keyup', function() {
        filtrarUsuarios();
        // Vaciar el contenedor de sugerencias (si existiera)
        $('#sugerencias').empty();
    });

    // Aplicar filtros automáticamente cuando cambian los selects (redirige la página)
    $('select').on('change', function() {
        aplicarFiltros();
    });
});

// Función para filtrar la tabla en tiempo real según lo escrito en el input
function filtrarUsuarios() {
    var input = document.getElementById('nombre_usuario');
    var filter = input.value.toLowerCase();
    var table = document.getElementById('tablaUsuarios');
    var rows = table.getElementsByTagName('tr');
    
    for (var i = 0; i < rows.length; i++) {
        // Se revisa todo el contenido de la fila
        var rowText = rows[i].textContent || rows[i].innerText;
        rows[i].style.display = rowText.toLowerCase().indexOf(filter) > -1 ? "" : "none";
    }
}

// Función para aplicar todos los filtros (redirige a la URL con los parámetros)
function aplicarFiltros() {
    var filtros = {
        nombre_usuario: $('#nombre_usuario').val(),
        area: $('select[name="area"]').val(),
        año_inicio: $('select[name="año_inicio"]').val(),
        mes_inicio: $('select[name="mes_inicio"]').val(),
        año_fin: $('select[name="año_fin"]').val(),
        mes_fin: $('select[name="mes_fin"]').val(),
        nombre_curso: $('select[name="nombre_curso"]').val(),
        empresa: $('select[name="empresa"]').val(),
        estado: $('select[name="estado"]').val()
    };
    
    // Eliminar parámetros vacíos
    Object.keys(filtros).forEach(function(key) {
        if (!filtros[key]) {
            delete filtros[key];
        }
    });
    
    var params = new URLSearchParams(filtros);
    window.location.href = 'ListaCursos.php?' + params.toString();
}

// Función para mostrar/ocultar el contenedor de filtros (opcional)
function toggleFiltro(filtroId) {
    $('.filtro-container').not(`#filtro${filtroId.charAt(0).toUpperCase() + filtroId.slice(1)}`).removeClass('visible');
    var contenedor = document.getElementById(`filtro${filtroId.charAt(0).toUpperCase() + filtroId.slice(1)}`);
    contenedor.classList.toggle('visible');
}
