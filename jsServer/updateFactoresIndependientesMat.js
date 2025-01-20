// Función para actualizar los materiales desde el input
async function updateFactoresIndependientesMaterialesDesdeInput(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Mat"]');
    
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;

    // Obtener los valores de los campos hidden relacionados con los factores
    const hiddenFactorMo = contenedor.querySelector('.class_hidden_factorMo_materiales').value;
    const hiddenFactorO = contenedor.querySelector('.class_hidden_factorO_materiales').value;
    const hiddenFactorV = contenedor.querySelector('.class_hidden_factorPo_materiales').value;
    const hiddenFactorPoliza = contenedor.querySelector('.class_hidden_factorVi_materiales').value;

    // Determinar qué input fue modificado y su campo hidden asociado
    let inputValorActualizado;
    if (input.matches('.factor_MoClassMateriales')) {
        inputValorActualizado = hiddenFactorMo;
    } else if (input.matches('.factor_OClassMateriales')) {
        inputValorActualizado = hiddenFactorO;
    } else if (input.matches('.factor_VClassMateriales')) {
        inputValorActualizado = hiddenFactorV;
    } else if (input.matches('.factor_polizaClassMateriales')) {
        inputValorActualizado = hiddenFactorPoliza;
    }

    if (!inputValorActualizado) {
        console.error('No se encontró el campo hidden correspondiente.');
        return;
    }

    // Crear el objeto con los datos a enviar
    let data = {
        idIdentificadorMateriales: idContenedor.replace('id_Mat', ''),
        factorActualizado: inputValorActualizado, // Este es el valor clave actualizado
        valorActualizado: input.value // Valor ingresado en el input
    };

    console.log('Datos enviados:', data);

    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateFactoresIndependientesMat.php', {
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
function agregarEventListenersFactorMateriales() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.factor_MoClassMateriales') ||
            event.target.matches('.factor_OClassMateriales') ||
            event.target.matches('.factor_VClassMateriales') ||
            event.target.matches('.factor_polizaClassMateriales')
        ) {
            setTimeout(() => {
                updateFactoresIndependientesMaterialesDesdeInput(event.target);
            }, 500);
        }
    });
}

export { updateFactoresIndependientesMaterialesDesdeInput, agregarEventListenersFactorMateriales };