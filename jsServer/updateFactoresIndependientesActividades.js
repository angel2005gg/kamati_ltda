// Función para actualizar los materiales desde el input
async function updateFactoresIndependientesActividadesDesdeInput(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Ac"]');
    
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;

    // Obtener los valores de los campos hidden relacionados con los factores
    const hiddenFactorMoActividades = contenedor.querySelector('.class_hidden_factorMo_ActividadesUnique').value;
    const hiddenFactorOActividades = contenedor.querySelector('.class_hidden_factorO_ActividadesUnique').value;
    const hiddenFactorVActividades = contenedor.querySelector('.class_hidden_factorVi_ActividadesUnique').value;
    const hiddenFactorPolizaActividades = contenedor.querySelector('.class_hidden_factorPo_ActividadesUnique').value;

    // Determinar qué input fue modificado y su campo hidden asociado
    let inputValorActualizado;
    if (input.matches('.factorMoHiddenClass')) {
        inputValorActualizado = hiddenFactorMoActividades;
    } else if (input.matches('.factorOHiddenClass')) {
        inputValorActualizado = hiddenFactorOActividades;
    } else if (input.matches('.viaticosHiddenClass')) {
        inputValorActualizado = hiddenFactorVActividades;
    } else if (input.matches('.polizaHiddenClaas')) {
        inputValorActualizado = hiddenFactorPolizaActividades;
    }

    if (!inputValorActualizado) {
        console.error('No se encontró el campo hidden correspondiente.');
        return;
    }

    // Crear el objeto con los datos a enviar
    let data = {
        idIdentificadorActividades: idContenedor.replace('id_Ac', ''),
        factorActualizadoActividades: inputValorActualizado, // Este es el valor clave actualizado
        valorActualizadoActividades: input.value // Valor ingresado en el input
    };

    console.log('Datos enviados:', data);

    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateFactoresIndependientesAct.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // Obtener la respuesta del servidor
        const respuesta = await response.json();
        console.log('Respuesta del servidor:', respuesta);

        if (respuesta.status === 'success') {
            console.log('Actualización exitosa');
        } else {
            console.error('Error al actualizar:', respuesta.message);
        }
    } catch (error) {
        console.error('Error al enviar los datos al servidor:', error);
    }
}

// Función para agregar listeners dinámicos usando MutationObserver
function agregarEventListenersFactorActividades() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.factorMoHiddenClass') ||
            event.target.matches('.factorOHiddenClass') ||
            event.target.matches('.viaticosHiddenClass') ||
            event.target.matches('.polizaHiddenClaas')
        ) {
            setTimeout(() => {
                updateFactoresIndependientesActividadesDesdeInput(event.target);
            }, 400);
        }
    });
}

export { updateFactoresIndependientesActividadesDesdeInput, agregarEventListenersFactorActividades };