$(document).ready(function() {
    // Agrega un evento change a los input radio
    function handleRadioChange() {
        let selectedValue = $('input[name="radio1"]:checked').val();
        let selectedValueRadio2 = $('input[name="radio2"]:checked').val();
        let valor = $('input[name="inputHorasDias"]').val();
      

        if (selectedValue === 'Vacaciones') {
            $('#diascompensados').prop('disabled', false);
            $('#radioHoras').prop('disabled', true);
            $('#radioDias').prop('checked', true);
            $('#inputHorasDias').prop('disabled', false);
            

            actualizarSelectDias(parseInt(valor));
            if(selectedValueRadio2 === 'dias'){
                $('#txt_total_horas').prop('disabled', true).val("");
                $('#txt_total_horas1').val("");
               
            }else if(selectedValueRadio2 === 'horas'){
                $('#txt_total_horas').prop('disabled', true).val("");   
                $('#txt_total_horas1').val("");   
                
            }


            
        } else if(selectedValue === 'Cumpleaños' || selectedValue === 'DiaFamilia' || selectedValue === 'Vacaciones'){
            $('#cantidad_dias').prop('disabled', true).val('');
            $('#diascompensados').prop('disabled', true).val('');
            $('#radioDias').prop('checked', true);
            $('#inputHorasDias').prop('disabled', true).val(1);

            if(selectedValueRadio2 === 'dias'){
                $('#txt_total_horas').prop('disabled', true).val("");
                $('#txt_total_horas1').val("");
            }
        } else if(selectedValue === 'Permiso' || selectedValue === 'Licencia'){
            $('#cantidad_dias').prop('disabled', true).val('');
            $('#diascompensados').prop('disabled', true).val('');
            $('#radioHoras').prop('disabled', false);
            $('#inputHorasDias').prop('disabled', false);

            if(selectedValueRadio2 === 'dias'){
                $('#txt_total_horas').prop('disabled', true).val(valor*8);
                $('#txt_total_horas1').val(valor*8);
            }else if(selectedValueRadio2 === 'horas'){
                $('#txt_total_horas').prop('disabled', true).val(valor);   
                $('#txt_total_horas1').val(valor);   
            }
        }
        
        if (selectedValue === 'Permiso' && selectedValueRadio2 === 'horas') {
            if(valor >= 16){
                $('#licencia').prop('checked', true);
            }
        } else if(selectedValue === 'Permiso' && selectedValueRadio2 === 'dias'){
            if(valor >= 2){
                $('#licencia').prop('checked', true);
            }
            
        } else if(selectedValue === 'Licencia' && selectedValueRadio2 === 'horas'){
            if(valor < 16){
                $('#permiso').prop('checked', true);
            }
        } else if(selectedValue === 'Licencia' && selectedValueRadio2 === 'dias'){
            if(valor < 2){
                $('#permiso').prop('checked', true);
            }
        }

        function actualizarSelectDias(cantidadDias) {
                    let selectDias = $('#cantidad_dias');
                    selectDias.empty(); // Limpia las opciones actuales
            
                    // Agrega las nuevas opciones basadas en la cantidad de días
                    for (let i = 1; i <= cantidadDias; i++) {
                        selectDias.append(new Option(i, i));
                    }
                }

        // Realiza una solicitud AJAX para enviar el valor al servidor
        $.ajax({
            url: '../controlador/ServletPermisos.php',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                opcion: selectedValue,
                cantidad_dias: valor
            }),
            success: function(response) {
                console.log('Respuesta del servidor:', response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud.');
            }
        });
    }

    // Escucha cambios en los radios radio1 
    $('input[type="radio"][name="radio1"]').on('change', handleRadioChange);

    // Escucha cambios en los radios radio2
    $('input[type="radio"][name="radio2"]').on('change', handleRadioChange);

    $('input[type="text"][name="inputHorasDias"]').on('change', handleRadioChange);

    $('input[type="text"][name="txt_total_horas"]').on('change', handleRadioChange);
    $('input[type="text"][name="txt_total_horas1"]').on('change', handleRadioChange);
    



    $('#diascompensados').on('change', function() {
        let selectedValue = $(this).val();
        // Si el valor seleccionado es 'Si', activa el input #cantidad_dias, de lo contrario, desactívalo
        if (selectedValue === 'Si') {
            $('#cantidad_dias').prop('disabled', false);
        } else if(selectedValue === 'No'){
            $('#cantidad_dias').prop('disabled', true).val('');
        }
    });

    
    
});