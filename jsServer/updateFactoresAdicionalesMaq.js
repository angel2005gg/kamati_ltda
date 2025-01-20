// Función para actualizar los materiales desde el input
async function updateFactoresAdicionalesMaquinarias(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Maq"]');
    
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;

    // Obtener los valores de los campos hidden relacionados con los factores
    const hiddenFactorSiemenes = contenedor.querySelector('.class_hidden_Factor_Simaquinaria').value;
    const hiddenFactorPilz = contenedor.querySelector('.class_hidden_Factor_Pimaquinaria').value;
    const hiddenFactorRittal = contenedor.querySelector('.class_hidden_Factor_Rimaquinaria').value;
    const hiddenFactorPc = contenedor.querySelector('.class_hidden_Factor_Pcmaquinaria').value;

    // Determinar qué input fue modificado y su campo hidden asociado
    let inputValorActualizado;
    if (input.matches('.factor_siemensClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorSiemenes;
    } else if (input.matches('.factor_pilzClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorPilz;
    } else if (input.matches('.factor_rittalClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorRittal;
    } else if (input.matches('.factor_phoenixcontactClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorPc;
    }

    if (!inputValorActualizado) {
        console.error('No se encontró el campo hidden correspondiente.');
        return;
    }

    // Crear el objeto con los datos a enviar
    let data = {
        idIdentificadorMaquinaria: idContenedor.replace('id_Maq', ''),
        factorActualizado: inputValorActualizado, // Este es el valor clave actualizado
        valorActualizado: input.value // Valor ingresado en el input
    };
    console.log(data.idIdentificadorMateriales)

    console.log('Datos enviados:', data);

    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateFactoresAdicionalesMaq.php', {
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
function agregarEventListenersFactorAdicionalesMaq() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.factor_siemensClassMaquinariaUnique') ||
            event.target.matches('.factor_pilzClassMaquinariaUnique') ||
            event.target.matches('.factor_rittalClassMaquinariaUnique') ||
            event.target.matches('.factor_phoenixcontactClassMaquinariaUnique')
        ) {
            setTimeout(() => {
                updateFactoresAdicionalesMaquinarias(event.target);
            }, 500);
        }
    });
}

export { updateFactoresAdicionalesMaquinarias, agregarEventListenersFactorAdicionalesMaq };