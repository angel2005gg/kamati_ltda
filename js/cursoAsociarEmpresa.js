$(document).ready(function() {

    // Inicializar Select2 en el select de usuario/contratista
    $('#id_usuario').select2({
        ajax: {
            url: 'cursoAsociarEmpresa.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // Enviar el término de búsqueda y el tipo (usuarios o contratistas)
                console.log('Enviando búsqueda:', {
                    term: params.term,
                    tipo: $('#tipo_busqueda').val()
                });
                return {
                    term: params.term || '',
                    tipo: $('#tipo_busqueda').val()
                };
            },
            processResults: function (data) {
                console.log('Datos recibidos:', data);
                
                if (!data || data.error) {
                    console.error('Error en datos:', data);
                    return { results: [] };
                }
                
                // Mapear los resultados para que Select2 entienda
                const results = data.map(item => ({
                    id: item.id,
                    text: item.nombre,
                    tipo: item.tipo
                }));

                console.log('Resultados procesados:', results);
                return { results: results };
            },
            cache: false
        },
        minimumInputLength: 1,
        placeholder: 'Buscar usuario o contratista...',
        allowClear: true,
        language: {
            inputTooShort: function() {
                return "Por favor ingrese al menos 1 caracter";
            },
            noResults: function() {
                const tipo = $('#tipo_busqueda').val();
                return `No se encontraron ${tipo === 'contratistas' ? 'contratistas' : 'usuarios'}`;
            },
            searching: function() {
                const tipo = $('#tipo_busqueda').val();
                return `Buscando ${tipo === 'contratistas' ? 'contratistas' : 'usuarios'}...`;
            }
        }
    });

    // Al cambiar el tipo de búsqueda (usuarios vs. contratistas)
    $('#tipo_busqueda').on('change', function() {
        console.log('Tipo de búsqueda cambiado a:', $(this).val());
        // Limpiar la selección actual y el campo de área
        $('#id_usuario').val(null).trigger('change');
        $('#area_usuario').val('');
        // Opcional: actualizar el placeholder del select
        let nuevoPlaceholder = $(this).val() === 'contratistas' ? 'Buscar contratista...' : 'Buscar usuario...';
        $('#id_usuario').attr('data-placeholder', nuevoPlaceholder);
    });

    // Al seleccionar un elemento (usuario o contratista) en el Select2
    $('#id_usuario').on('select2:select', function(e) {
        var data = e.params.data;
        console.log('Datos seleccionados:', data);
        
        // Actualizamos el campo oculto con el tipo seleccionado (usuario o contratista)
        $('#tipo_usuario').val(data.tipo);
        
        if (data.tipo === 'contratista') {
            // Para contratistas, asignamos "Contratista" en el campo de área
            $('#area_usuario').val('Contratista');
    
            // Buscamos si ya existe una opción con ese valor
            var option = $('#id_usuario').find("option[value='" + data.id + "']");
            if (option.length) {
                // Si existe, actualizamos su texto para que muestre el nombre del contratista
                option.text(data.text);
                console.log("Opción existente actualizada para contratista:", option);
            } else {
                // Si no existe, creamos y agregamos la nueva opción
                var newOption = new Option(data.text, data.id, true, true);
                $('#id_usuario').append(newOption);
                console.log("Nueva opción agregada para contratista:", newOption);
            }
            // Forzamos la selección y actualizamos Select2
            $('#id_usuario').val(data.id).trigger('change');
            console.log("Valor actual del select:", $('#id_usuario').val());
        } else {
            // Para usuarios, extraemos el ID numérico si viene con el prefijo "usuario_"
            let usuarioId = data.id;
            if (usuarioId.indexOf('usuario_') === 0) {
                usuarioId = usuarioId.substring('usuario_'.length);
            }
            console.log("ID numérico del usuario:", usuarioId);
        
            const formData = new FormData();
            formData.append('id', usuarioId);
            $.ajax({
                url: 'obtener_area_usuario.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.area) {
                        $('#area_usuario').val(response.area);
                    } else {
                        $('#area_usuario').val('');
                        console.error('Error al obtener el área:', response.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                    $('#area_usuario').val('');
                }
            });
        }
        
    });
    
    
    
    
    
    // Configurar el datepicker para la fecha de inicio
    $('#fecha_inicio').datepicker({
        dateFormat: 'yy-mm-dd',
        changeMonth: true,
        changeYear: true,
        yearRange: '-100:+10'
    });

    // Al cambiar la empresa, cargar los cursos correspondientes vía AJAX
    $('#selectEmpresa').on('change', function() {
        const empresaId = $(this).val();
        if (empresaId) {
            $.ajax({
                url: 'cursoAsociarEmpresa.php',
                type: 'GET',
                data: { action: 'getCursos', empresa_id: empresaId },
                dataType: 'json',
                success: function(data) {
                    $('#selectCurso').empty().append('<option value="">Seleccione un curso...</option>');
                    if (data && data.length > 0) {
                        data.forEach(function(curso) {
                            if (curso && curso.nombre_curso_fk) {
                                $('#selectCurso').append(
                                    `<option value="${curso.id_curso_empresa}" data-duracion="${curso.duracion}">
                                        ${curso.nombre_curso_fk} (${curso.duracion} meses)
                                    </option>`
                                );
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los cursos:', error);
                }
            });
        } else {
            $('#selectCurso').empty().append('<option value="">Seleccione un curso...</option>');
        }
    });

    // Actualizar la fecha fin en función de la fecha de inicio y la duración del curso
    $('#selectCurso, #fecha_inicio').on('change', function() {
        const selectedCurso = $('#selectCurso').find('option:selected');
        const duracion = selectedCurso.data('duracion');
        const fechaInicio = $('#fecha_inicio').val();

        if (fechaInicio && duracion) {
            const fechaFin = new Date(fechaInicio);
            fechaFin.setMonth(fechaFin.getMonth() + parseInt(duracion));
            $('#fecha_fin').val($.datepicker.formatDate('yy-mm-dd', fechaFin));
        }
    });

    // Validación del formulario
    const form = document.querySelector('.needs-validation');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

});
