// Función que se ejecuta al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    // Función para obtener los IDs de los registros
    function obtenerIdsDeRegistros() {
        // Aquí debes realizar una solicitud al servidor para obtener los IDs generados
        fetch('../controlador/ControladorCotizaciones.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                menu: 'crearCotizacion',
                accion: 'obtenerIds', // Este sería un nuevo caso que necesitas agregar en tu controlador
            }),
        })
        .then(response => response.json())
        .then(data => {
            // Asumimos que 'data' es un objeto con los IDs generados
            document.getElementById('hidden_InputFactorGlobalNameMo').value = data.idMo || '';
            document.getElementById('hidden_InputFactorGlobalNameO').value = data.idO || '';
            document.getElementById('hidden_InputFactorGlobalNameV').value = data.idV || '';
            document.getElementById('hidden_InputFactorGlobalNameP').value = data.idP || '';
        })
        .catch(error => {
            console.error('Error al obtener los IDs:', error);
        });
    }

    // Llamar a la función para obtener los IDs cuando se cargue la página
    obtenerIdsDeRegistros();
});