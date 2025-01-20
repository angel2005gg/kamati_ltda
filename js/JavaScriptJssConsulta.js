$(document).ready(function() {
    // Guardamos el valor inicial del select
    let valorPrevio = $('#idcargo_area').val();

    // Verificamos cada 1 segundo si el valor ha cambiado
    setInterval(function() {
        const valorActual = $('#idcargo_area').val();
        
        // Si detectamos un cambio de valor
        if (valorActual !== valorPrevio) {
            valorPrevio = valorActual; // Actualizamos el valor previo

            // Llamada AJAX para obtener el id_area_fk
            $.ajax({
                url: '../procesar/procesarJefes.php',
                type: 'POST',
                data: { action: 'consultarArea', id_cargo: valorActual },
                dataType: 'json',
                success: function(response) {
                    const idArea = response.id_area_fk;

                    if (idArea) {
                        // Llamada AJAX para obtener los detalles del jefe inmediato
                        $.ajax({
                            url: '../procesar/procesarJefes.php',
                            type: 'POST',
                            data: { action: 'consultarJefeArea', id_area: idArea },
                            dataType: 'json',
                            success: function(data) {
                                const selectInmediato = $('#select_JefesUsuarioId');
                                selectInmediato.empty(); // Vaciar el select

                                if (data.length > 0) {
                                    data.forEach(function(jefe) {
                                        const option = $('<option></option>')
                                            .attr('value', jefe.id_Usuarios)
                                            .text(jefe.primer_nombre + ' ' + jefe.primer_apellido);
                                        selectInmediato.append(option);
                                    });
                                } else {
                                    selectInmediato.append('<option value="">No hay jefes inmediatos</option>');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("Error al consultar el jefe inmediato: ", error);
                            }
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    console.error(xhr.responseText);
                }
            });
        }
    }, 200); // Intervalo de 1 segundo
});