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
                try {
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
                } catch (e) {
                    console.error('Error al parsear la respuesta JSON:', e);
                    console.error('Respuesta recibida:', xhr.responseText);
                    mostrarAlerta('danger', 'Error al procesar la respuesta del servidor.');
                }
            } else {
                console.error('Error en la solicitud AJAX:', xhr.status, xhr.statusText);
                mostrarAlerta('danger', 'Error en la solicitud al servidor.');
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
                try {
                    let response = JSON.parse(xhr.responseText);
    
                    // Si hay datos de usuario, asignarlos a los campos correspondientes
                    if (response.usuario) {
                        document.getElementById('primerNombre').value = response.usuario.primer_nombre;
                        document.getElementById('idInputHiddenID').value = response.usuario.id_Usuarios;
                        document.getElementById('segundoNombre').value = response.usuario.segundo_nombre;
                        document.getElementById('primerApellido').value = response.usuario.primer_apellido;
                        document.getElementById('segundoApellido').value = response.usuario.segundo_apellido;
                        document.getElementById('numeroIdentificacion').value = response.usuario.numero_identificacion;
                        document.getElementById('correoElectronico').value = response.usuario.correo_electronico;
                        document.getElementById('numeroTelefonoMovil').value = response.usuario.numero_telefono_movil;
                        document.getElementById('direccionResidencia').value = response.usuario.direccion_residencia;
                        document.getElementById('sedeLaboral').value = response.usuario.sede_laboral;
                        document.getElementById('idcargo_area').value = response.usuario.id_Cargo_Usuario;
    
                        // Actualizar el campo select_JefesUsuarioId después de 1 segundo
                        setTimeout(function() {
                            document.getElementById('select_JefesUsuarioId').value = response.usuario.id_jefe_area;
                        }, 500); // 1000 milisegundos = 1 segundo
    
                        // Actualizar otros campos inmediatamente
                        document.getElementById('select_TipoRolUsuarioID').value = response.usuario.id_Rol_Usuario;
                        document.getElementById('select_estado_user').value = response.usuario.estado_usuario;
                    }
    
                    // Si hay áreas de jefe, marcamos los checkboxes correspondientes
                    if (response.areas_jefe && response.areas_jefe.length > 0) {
                        let areasJefe = response.areas_jefe;
    
                        // Recorre los checkboxes y marca los que coincidan con los valores de `areas_jefe`
                        let checkboxes = document.querySelectorAll('input[name="id_cargos_seleccionados[]"]');
                        checkboxes.forEach(function(checkbox) {
                            if (areasJefe.includes(parseInt(checkbox.value))) {
                                checkbox.checked = true; // Marcar checkbox
                            } else {
                                checkbox.checked = false; // Desmarcar checkbox
                            }
                        });
                    } else {
                        // Si no hay áreas_jefe, desmarcar todos los checkboxes
                        let checkboxes = document.querySelectorAll('input[name="id_cargos_seleccionados[]"]');
                        checkboxes.forEach(function(checkbox) {
                            checkbox.checked = false;
                        });
                    }
    
                } catch (e) {
                    console.error('Error al parsear la respuesta JSON:', e);
                    console.error('Respuesta recibida:', xhr.responseText);
                    mostrarAlerta('danger', 'Error al procesar la respuesta del servidor.');
                }
            } else {
                console.error('Error en la solicitud AJAX:', xhr.status, xhr.statusText);
                mostrarAlerta('danger', 'Error en la solicitud al servidor.');
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