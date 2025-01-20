// Función para actualizar los materiales desde el input
async function updateMaterialesDesdeInputActividades(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Ac"]');
   
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;
    const checkFactoresActividadesa = contenedor.querySelector('.hiddenTableInput_actividades_unqueVal').value;
    const nombreTablaActividadesa = contenedor.querySelector('.nombre_table-actividadesClass').value;
   

    // Obtener los totales y asegurarse de que sean números válidos
    let totalKamatis = quitarPuntosMiles(contenedor.querySelector('.txt_total_kamatiActividadesClass').value);
    let totalClientes = quitarPuntosMiles(contenedor.querySelector('.txt_total_clienteActividadesClass').value);

    // Asegurarse de que los valores son números flotantes válidos
    totalKamatis = parseFloat(totalKamatis);
    totalClientes = parseFloat(totalClientes);

    if (isNaN(totalKamatis) || isNaN(totalClientes)) {
        console.error('Los valores de totalKamati o totalCliente no son números válidos.');
        return;
    }

    // Crear el objeto con los datos a enviar
    let data = {
        idIdentificadorActividades: idContenedor.replace('id_Ac', ''),
        checkFactoresActividades: checkFactoresActividadesa,
        nombreTablaActividades: nombreTablaActividadesa,
        totalKamatiAc: totalKamatis,
        totalClienteAc: totalClientes
    };
    
    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateIdentificadorActividades.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // Obtener la respuesta del servidor
        const respuesta = await response.json();
        console.log('Respuesta del servidor:', respuesta);

        // Comprobar la respuesta
        if (respuesta.status === 'success') {
            // Acción en caso de éxito (puedes añadir más lógica aquí)
            console.log('Actualización exitosa');
        } else {
            // Acción en caso de error
            console.error('Error al actualizar:', respuesta.message);
        }
    } catch (error) {
        console.error('Error al enviar los datos al servidor:', error);
    }
}


// Función para agregar listeners dinámicos usando MutationObserver
function agregarEventListenersActividades() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.txt_total_kamatiActividadesClass') ||
            event.target.matches('.txt_total_clienteActividadesClass') ||
            event.target.matches('.hiddenTableInput_actividades_unqueVal') ||
            event.target.matches('.nombre_table-actividadesClass')||
            event.target.matches('.cantidad_actividades_unique') ||
            event.target.matches('.cantidad_persona_class_unique') ||
            event.target.matches('.costo-externio-unitario-input') 
            
        ) {
            setTimeout(() => {
                updateMaterialesDesdeInputActividades(event.target);
            }, 400);
        }
    });

    const observer = new MutationObserver((mutationsList) => {
        mutationsList.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                if (
                    mutation.target.matches('.txt_total_kamatiActividadesClass') ||
                    mutation.target.matches('.txt_total_clienteActividadesClass') ||
                    mutation.target.matches('.hiddenTableInput_actividades_unqueVal')||
                    mutation.target.matches('.cantidad_actividades_unique') ||
                    mutation.target.matches('.cantidad_persona_class_unique') ||
                    mutation.target.matches('.costo-externio-unitario-input') 
                    
                ) {
                    setTimeout(() => {
                        updateMaterialesDesdeInputActividades(mutation.target);
                    }, 400);
                }
            }
        });
    });

    observer.observe(contenedor, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['value']
    });
}

// Función para eliminar puntos de miles, símbolo de moneda y otros caracteres no numéricos// Función para eliminar puntos de miles, símbolos no numéricos y convertir comas en puntos decimales
function quitarPuntosMiles(valor) {
    // Eliminar todos los caracteres que no sean dígitos, puntos o comas
    valor = valor.replace(/[^0-9.,]/g, '');

    // Eliminar puntos de miles
    valor = valor.replace(/\./g, '');

    // Reemplazar la coma por un punto para decimales
    valor = valor.replace(',', '.');

    return valor;
}
export { updateMaterialesDesdeInputActividades, agregarEventListenersActividades };