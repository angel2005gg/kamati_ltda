document.addEventListener('DOMContentLoaded', function() {
    // Función para mostrar una alerta en la página
    function mostrarAlerta(tipo, mensaje) {
        const alertContainer = document.getElementById('alertContainerLimpiar');
        const alertHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show fixed-alert" role="alert" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); width: 50%; text-align: center;">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        alertContainer.innerHTML = alertHTML;
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    }

    // Asignar eventos de clic a todos los botones "Seleccionar" existentes
    document.querySelectorAll('.seleccionar').forEach(button => {
        button.addEventListener('click', function() {
            let numeroIdentificacion = this.getAttribute('data-id');
            obtenerDetallesUsuario(numeroIdentificacion);
        });
    });

    document.getElementById('txt_filtro_nombre').addEventListener('input', function() {
        let nombre = this.value;

        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../procesar/procesarFiltroUser.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                let tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = '';

                response.forEach(function(row) {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.primer_nombre}</td>
                        <td>${row.segundo_nombre}</td>
                        <td>${row.primer_apellido}</td>
                        <td>${row.segundo_apellido}</td>
                        <td>${row.numero_identificacion}</td>
                        <td>${row.correo_electronico}</td>
                        <td>${row.numero_telefono_movil}</td>
                        <td>${row.direccion_residencia}</td>
                        <td>${row.sede_laboral}</td>
                        <td>${row.nombre_area}</td>
                        <td>${row.nombre_cargo}</td>
                        <td>${row.estado_usuario}</td>
                        <td><button type="button" class="button_color seleccionar" data-id="${row.numero_identificacion}">Seleccionar</button></td>
                    `;
                    tableBody.appendChild(tr);
                });

                // Añadir eventos a los nuevos botones
                document.querySelectorAll('.seleccionar').forEach(button => {
                    button.addEventListener('click', function() {
                        let numeroIdentificacion = this.getAttribute('data-id');
                        obtenerDetallesUsuario(numeroIdentificacion);
                    });
                });
            }
        };

        xhr.send('nombre=' + encodeURIComponent(nombre));
    });

    // Obtener detalles del usuario
    function obtenerDetallesUsuario(numeroIdentificacion) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../procesar/procesarSeleccionar.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);

                // Asignar los valores a los campos de texto
                document.getElementById('primerNombre').value = response.primer_nombre;
                document.getElementById('idInputHiddenID').value = response.id_Usuarios;
                document.getElementById('segundoNombre').value = response.segundo_nombre;
                document.getElementById('primerApellido').value = response.primer_apellido;
                document.getElementById('segundoApellido').value = response.segundo_apellido;
                document.getElementById('numeroIdentificacion').value = response.numero_identificacion;
                document.getElementById('correoElectronico').value = response.correo_electronico;
                document.getElementById('numeroTelefonoMovil').value = response.numero_telefono_movil;
                document.getElementById('direccionResidencia').value = response.direccion_residencia;
                document.getElementById('sedeLaboral').value = response.sede_laboral;
                document.getElementById('idcargo_area').value = response.id_Cargo_Usuario;
                document.getElementById('select_estado_user').value = response.estado_usuario;                
            }
        };

        xhr.send('numero_identificacion=' + encodeURIComponent(numeroIdentificacion));
    }

    // Limpiar campos del formulario
    document.getElementById('limpiar_campos').addEventListener('click', function() {
        // Limpiar inputs con clase 'input'
        document.querySelectorAll('input.limpiar').forEach(input => {
            input.value = '';
        });

        // Limpiar selects con clase 'input'
        document.querySelectorAll('select.limpiar').forEach(select => {
            select.selectedIndex = 0;
        });

        // Mostrar la alerta de limpieza
        mostrarAlerta('warning', 'Los campos han sido limpiados.');
    });
});