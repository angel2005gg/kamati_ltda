// updateSelectCotizaciones.js

// Función para eliminar los puntos de miles y obtener un número válido
function quitarPuntosMiles(valor) {
    return valor.replace(/\./g, ''); // Elimina todos los puntos
}

// Función para agregar puntos de miles a un número
function agregarPuntosMiles(valor) {
    return valor.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Función para obtener los datos de la cotización desde el servidor
async function obtenerDatosCargos() {
    try {
        const response = await fetch('../phpServer/updateSelectCargos.php'); // Ajusta la ruta
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const textoRespuesta = await response.text();
        console.log('Respuesta del servidor:', textoRespuesta);

        const datos = JSON.parse(textoRespuesta);

        // Verificar los valores antes de asignarlos
        console.log('Datos:', datos);

        if (datos.success && datos.dom) {
            // Iterar sobre los datos obtenidos y asignar los valores de los cargos
            datos.dom.forEach((cargo, index) => {
                // Asignar valores a los campos de entrada
                if (index === 0) {
                    // Para el primer cargo
                    document.getElementById('valorDiarioGlobalCargo_0').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_0').value = cargo.id || '';  // Asignar el ID del primer cargo
                } else if (index === 1) {
                    // Para el segundo cargo
                    document.getElementById('valorDiarioGlobalCargo_1').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_1').value = cargo.id || '';  // Asignar el ID del segundo cargo
                } else if (index === 2) {
                    // Para el tercer cargo
                    document.getElementById('valorDiarioGlobalCargo_2').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_2').value = cargo.id || '';  // Asignar el ID del tercer cargo
                } else if (index === 3) {
                    // Para el cuarto cargo
                    document.getElementById('valorDiarioGlobalCargo_3').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_3').value = cargo.id || '';  // Asignar el ID del cuarto cargo
                } else if (index === 4) {
                    // Para el quinto cargo
                    document.getElementById('valorDiarioGlobalCargo_4').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_4').value = cargo.id || '';  // Asignar el ID del quinto cargo
                } else if (index === 5) {
                    // Para el sexto cargo
                    document.getElementById('valorDiarioGlobalCargo_5').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_5').value = cargo.id || '';  // Asignar el ID del sexto cargo
                } else if (index === 6) {
                    // Para el séptimo cargo
                    document.getElementById('valorDiarioGlobalCargo_6').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_6').value = cargo.id || '';  // Asignar el ID del séptimo cargo
                } else if (index === 7) {
                    // Para el octavo cargo
                    document.getElementById('valorDiarioGlobalCargo_7').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_7').value = cargo.id || '';  // Asignar el ID del octavo cargo
                } else if (index === 8) {
                    // Para el noveno cargo
                    document.getElementById('valorDiarioGlobalCargo_8').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_8').value = cargo.id || '';  // Asignar el ID del noveno cargo
                } else if (index === 9) {
                    // Para el décimo cargo
                    document.getElementById('valorDiarioGlobalCargo_9').value = agregarPuntosMiles(cargo.tarifa || '');
                    document.getElementById('id_hidden_cargo_9').value = cargo.id || '';  // Asignar el ID del décimo cargo
                }
            });

            console.log('Campos actualizados con los datos de los cargos');
        } else {
            console.error('No se encontraron datos o hubo un error.');
        }
    } catch (error) {
        console.error('Error al obtener los datos de los cargos:', error);
    }
}

// Función que envía los datos del campo actualizado al servidor
async function updateCargos(idCargosCot, valorCargosCot) {
    // Eliminar puntos de miles antes de enviar el valor al servidor
    const valorSinPuntos = quitarPuntosMiles(valorCargosCot);

    const data = {
        idCargosCot: idCargosCot,
        valorCargosCot: valorSinPuntos // Enviar el valor sin puntos
    };

    try {
        const response = await fetch('../phpServer/updateSelectCargos.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const respuestaTexto = await response.text();
        console.log('Respuesta del servidor:', respuestaTexto);
    } catch (error) {
        console.error('Error al actualizar el factor en el servidor:', error);
    }
}

// Función para agregar event listeners a los inputs que deben actualizar datos
function agregarEventListenesCargos() {
    const campos = [
        { inputId: 'valorDiarioGlobalCargo_0', hiddenInputId: 'id_hidden_cargo_0' },
        { inputId: 'valorDiarioGlobalCargo_1', hiddenInputId: 'id_hidden_cargo_1' },
        { inputId: 'valorDiarioGlobalCargo_2', hiddenInputId: 'id_hidden_cargo_2' },
        { inputId: 'valorDiarioGlobalCargo_3', hiddenInputId: 'id_hidden_cargo_3' },
        { inputId: 'valorDiarioGlobalCargo_4', hiddenInputId: 'id_hidden_cargo_4' },
        { inputId: 'valorDiarioGlobalCargo_5', hiddenInputId: 'id_hidden_cargo_5' },
        { inputId: 'valorDiarioGlobalCargo_6', hiddenInputId: 'id_hidden_cargo_6' },
        { inputId: 'valorDiarioGlobalCargo_7', hiddenInputId: 'id_hidden_cargo_7' },
        { inputId: 'valorDiarioGlobalCargo_8', hiddenInputId: 'id_hidden_cargo_8' },
        { inputId: 'valorDiarioGlobalCargo_9', hiddenInputId: 'id_hidden_cargo_9' },
    ];
    campos.forEach(({ inputId, hiddenInputId }) => {
        const inputElement = document.getElementById(inputId);
        if (inputElement) {
            inputElement.addEventListener('change', () => {
                const idFactor = document.getElementById(hiddenInputId).value;
                const valor = inputElement.value;

                // Eliminar puntos de miles antes de actualizar el valor
                updateCargos(idFactor, valor);
            });
        }
    });
}

// Exportar las funciones necesarias
export { obtenerDatosCargos, updateCargos, agregarEventListenesCargos };