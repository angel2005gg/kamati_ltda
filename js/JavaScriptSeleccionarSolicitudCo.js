document.addEventListener('DOMContentLoaded', function() {
    // Asignar eventos de clic a todos los botones "Seleccionar" existentes
    document.querySelectorAll('.seleccionar').forEach(button => {
        button.addEventListener('click', function() {
            let id_Permiso = this.getAttribute('data-id');
            obtenerDetallesUsuario(id_Permiso);
        });
    });

    document.getElementById('txt_filtro_solicitud').addEventListener('input', function() {
        let nombrePermiso = this.value;

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../procesar/procesarFiltroSolicitud.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                let table_body_select = document.getElementById('table_body_select');

                table_body_select.innerHTML = '';

                response.forEach(function(row) {
                    let tr = document.createElement('tr');
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
                        <td><button type="button" class="button_color seleccionar" data-id="${row.id_Permisos}">Seleccionar</button></td>
                    `;
                    table_body_select.appendChild(tr);
                });
                
                // Añadir eventos a los nuevos botones
                document.querySelectorAll('.seleccionar').forEach(button => {
                    button.addEventListener('click', function() {
                        let id_Permiso = this.getAttribute('data-id');
                        obtenerDetallesUsuario(id_Permiso);
                    });
                });
            }
        };

        xhr.send('nombreP=' + encodeURIComponent(nombrePermiso));
    });

    // Obtener detalles del usuario
    function obtenerDetallesUsuario(id_Permiso) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../procesar/procesarSeleccionarSolicitud.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);

                // Asignar los valores a los campos de texto
                document.getElementById('txt_date_eleboracion').value = response.fecha_elaboracion;
                document.getElementById('txt_date_eleboracion1').value = response.fecha_elaboracion;
                document.getElementById('txt_nombre').value = response.primer_nombre + " " + response.segundo_nombre + " " + response.primer_apellido + " " + response.segundo_apellido;
                document.getElementById('txt_nombre1').value = response.primer_nombre + " " + response.segundo_nombre + " " + response.primer_apellido + " " + response.segundo_apellido;
                document.getElementById('txt_identificacion').value = response.numero_identificacion;
                document.getElementById('txt_identificacion1').value = response.numero_identificacion;
                document.getElementById('txt_cargo').value = response.nombre_cargo;
                document.getElementById('txt_cargo1').value = response.nombre_cargo;
                document.getElementById('txt_area').value = response.nombre_area;
                document.getElementById('txt_area1').value = response.nombre_area;
                document.getElementById('txt_sede').value = response.sede_laboral;
                document.getElementById('txt_sede1').value = response.sede_laboral;
                document.getElementById('txt_tipo_permiso').value = response.tipo_permiso;
                document.getElementById('txt_tipo_permiso1').value = response.tipo_permiso;
                document.getElementById('txt_tipo_tiempo').value = response.tiempo;
                document.getElementById('txt_tipo_tiempo1').value = response.tiempo;
                document.getElementById('txt_cantidad_tiempo').value = response.cantidad_tiempo;
                document.getElementById('txt_cantidad_tiempo1').value = response.cantidad_tiempo;
                document.getElementById('date_inicio').value = response.fecha_inicio_novedad;                
                document.getElementById('date_inicio1').value = response.fecha_inicio_novedad;                
                document.getElementById('date_fin').value = response.fecha_fin_novedad;                
                document.getElementById('date_fin1').value = response.fecha_fin_novedad;                
                document.getElementById('txt_dias_compensados').value = response.dias_compensados;                
                document.getElementById('txt_dias_compensados1').value = response.dias_compensados;                
                document.getElementById('txt_cantidad_dias_compensados').value = response.cantidad_dias_compensados;                
                document.getElementById('txt_cantidad_dias_compensados1').value = response.cantidad_dias_compensados;                
                document.getElementById('txt_total_horas').value = response.total_horas_permiso;                
                document.getElementById('txt_total_horas1').value = response.total_horas_permiso;                
                document.getElementById('textarea_motivo').value = response.motivo_novedad;                
                document.getElementById('textarea_motivo1').value = response.motivo_novedad;                             
            }
        };

        xhr.send('id_permiso=' + encodeURIComponent(id_Permiso));
    }

    // Limpiar campos del formulario si es necesario
});