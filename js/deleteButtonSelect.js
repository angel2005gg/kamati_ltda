document.addEventListener('DOMContentLoaded', function() {
    // Obtén todas las filas del cuerpo de la tabla
    const rows = document.querySelectorAll('.tabla_solicitudes .del_btn_select tr');

    // Recorre cada fila
    rows.forEach(row => {
        // Encuentra la celda de la columna "Estado"
        const estadoCell = row.querySelector('td:nth-child(14)'); // 14 es la columna "Estado"
        const seleccionarButton = row.querySelector('.seleccionar');

        // Verifica si el estado es "Aprobado"
        if (estadoCell && estadoCell.textContent.trim() === "Aprobado") {
            // Si es "Aprobado", oculta el botón "Seleccionar"
            seleccionarButton.style.display = 'none';
        }
    });
});