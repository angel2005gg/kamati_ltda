document.getElementById('fecha').addEventListener('change', function(event) {
    const fecha = event.target.value;

    fetch('../procesar/procesarFiltroFechas.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `fecha=${fecha}`,
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('table_permisos_completos');
        tableBody.innerHTML = '';

        data.forEach(row => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td>${row.primer_nombre}</td>
                <td>${row.primer_apellido}</td>
                <td>${row.fecha_elaboracion}</td>
                <td>${row.tipo_permiso}</td>
                <td>${row.tiempo}</td>
                <td>${row.cantidad_tiempo}</td>
                <td>${row.fecha_inicio_novedad}</td>
                <td>${row.fecha_fin_novedad}</td>
                <td>${row.dias_compensados}</td>
                <td>${row.cantidad_dias_compensados}</td>
                <td>${row.total_horas_permiso}</td>
                <td>${row.motivo_novedad}</td>
                <td>${row.remuneracion}</td>
                <td>${row.estado_permiso}</td>
            `;

            tableBody.appendChild(tr);
        });
    })
    .catch(error => console.error('Error:', error));
});