// Función para actualizar los materiales desde el input
async function updateFactoresAdicionalesMaterialesDesdeInput(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Mat"]');
    
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;

    // Obtener los valores de los campos hidden relacionados con los factores
    const hiddenFactorSiemenes = contenedor.querySelector('.class_hidden_Factor_Simateriales').value;
    const hiddenFactorPilz = contenedor.querySelector('.class_hidden_Factor_Pimateriales').value;
    const hiddenFactorRittal = contenedor.querySelector('.class_hidden_Factor_Rimateriales').value;
    const hiddenFactorPc = contenedor.querySelector('.class_hidden_Factor_Pcmateriales').value;

    // Determinar qué input fue modificado y su campo hidden asociado
    let inputValorActualizado;
    if (input.matches('.factor_siemensClassMateriales')) {
        inputValorActualizado = hiddenFactorSiemenes;
    } else if (input.matches('.factor_pilzClassMateriales')) {
        inputValorActualizado = hiddenFactorPilz;
    } else if (input.matches('.factor_rittalClassMateriales')) {
        inputValorActualizado = hiddenFactorRittal;
    } else if (input.matches('.factor_phoenixcontactClassMateriales')) {
        inputValorActualizado = hiddenFactorPc;
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
    console.log(data.idIdentificadorMateriales)

    console.log('Datos enviados:', data);

    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateFactoresAdicionalesMat.php', {
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
function agregarEventListenersFactorAdicionalesMateriales() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.factor_siemensClassMateriales') ||
            event.target.matches('.factor_pilzClassMateriales') ||
            event.target.matches('.factor_rittalClassMateriales') ||
            event.target.matches('.factor_phoenixcontactClassMateriales')
        ) {
            setTimeout(() => {
                updateFactoresAdicionalesMaterialesDesdeInput(event.target);
            }, 500);
        }
    });
}

export { updateFactoresAdicionalesMaterialesDesdeInput, agregarEventListenersFactorAdicionalesMateriales };