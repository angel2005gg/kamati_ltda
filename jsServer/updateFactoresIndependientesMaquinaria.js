// Función para actualizar los materiales desde el input
async function updateFactoresIndependientesMaquinariaDesdeInput(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Maq"]');
    
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;

    // Obtener los valores de los campos hidden relacionados con los factores
    const hiddenFactorMoMaquinaria = contenedor.querySelector('.class_hidden_factorMo_maquinaria').value;
    const hiddenFactorOMaquinaria = contenedor.querySelector('.class_hidden_factorO_maquinaria').value;
    const hiddenFactorVMaquinaria = contenedor.querySelector('.class_hidden_factorVi_maquinaria').value;
    const hiddenFactorPolizaMaquinaria = contenedor.querySelector('.class_hidden_factorPo_maquinaria').value;

    // Determinar qué input fue modificado y su campo hidden asociado
    let inputValorActualizado;
    if (input.matches('.factor_MoClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorMoMaquinaria;
    } else if (input.matches('.factor_OClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorOMaquinaria;
    } else if (input.matches('.factor_VClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorVMaquinaria;
    } else if (input.matches('.factor_polizaClassMaquinariaUnique')) {
        inputValorActualizado = hiddenFactorPolizaMaquinaria;
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

    console.log('Datos enviados:', data);

    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateFactoresIndependientesMaq.php', {
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
function agregarEventListenersFactorMaquinaria() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.factor_MoClassMaquinariaUnique') ||
            event.target.matches('.factor_OClassMaquinariaUnique') ||
            event.target.matches('.factor_VClassMaquinariaUnique') ||
            event.target.matches('.factor_polizaClassMaquinariaUnique')
        ) {
            setTimeout(() => {
                updateFactoresIndependientesMaquinariaDesdeInput(event.target);
            }, 500);
        }
    });
}

export { updateFactoresIndependientesMaquinariaDesdeInput, agregarEventListenersFactorMaquinaria };