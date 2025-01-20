document.addEventListener('DOMContentLoaded', function() {
    // Asignar eventos de clic a todos los botones "Seleccionar" existentes
    document.querySelectorAll('.seleccionar').forEach(button => {
        button.addEventListener('click', function() {
            let id_Cargos = this.getAttribute('data-id');
            obtenerDetallesUsuario(id_Cargos);
        });
    });

    document.getElementById('txt_filtro_cargo').addEventListener('input', function() {
        let nombrePermiso = this.value;
    
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../procesar/procesarFiltroCargoJefeAdminLin.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    
        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);
                let table_body_cargo = document.getElementById('table_body_cargo');
    
                table_body_cargo.innerHTML = '';
    
                response.forEach(function(row) {
                    let tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.nombre_cargo}</td>
                        <td>${row.nombre_area}</td>
                        <td>${row.estado_cargo}</td>
                        <td><button type="button" class="button_color seleccionar" data-id="${row.id_Cargo}">Seleccionar</button></td>
                    `;
                    table_body_cargo.appendChild(tr);
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
    
        // Agregar una condición para manejar si nombrePermiso está vacío
        let postData = nombrePermiso ? 'cargoNombre=' + encodeURIComponent(nombrePermiso) : '';
    
        xhr.send(postData);
    });

    // Obtener detalles del usuario
    function obtenerDetallesUsuario(id_Cargos) {
        let xhr = new XMLHttpRequest();
        xhr.open('POST', '../procesar/procesarSeleccionarCargo.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);

                // Asignar los valores a los campos de texto
                document.getElementById('txt_nombre_cargo').value = response.nombre_cargo;
                document.getElementById('txt_nombre_area').value = response.id_area_fk;
                document.getElementById('txt_estado_cargo').value = response.estado_cargo;
                document.getElementById('txt_hidden_id').value = response.id_Cargo;

            }
        };

        xhr.send('id_Cargo=' + encodeURIComponent(id_Cargos));
    }

   
});