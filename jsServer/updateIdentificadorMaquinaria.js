// Función para actualizar los materiales desde el input
async function updateMaterialesDesdeInputMaquinaria(input) {
    // Obtener el contenedor del elemento
    const contenedor = input.closest('[id^="id_Maq"]');
   
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }

    // Obtener los valores necesarios del contenedor
    const idContenedor = contenedor.id;
    const checkFactoresMaquinariaa = contenedor.querySelector('.hiddenInputFactoresIndependientesClasMaquinariaClassUnique').value;
    const nombreTablaMaquinariaa = contenedor.querySelector('.nombre_table-maquinariaClass').value;
   

    // Obtener los totales y asegurarse de que sean números válidos
    let totalKamatis = quitarPuntosMiles(contenedor.querySelector('.txtTotalKamatiMaquinaria').value);
    let totalClientes = quitarPuntosMiles(contenedor.querySelector('.txtTotalClienteMaquinaria').value);

    // Asegurarse de que los valores son números flotantes válidos
    totalKamatis = parseFloat(totalKamatis);
    totalClientes = parseFloat(totalClientes);

    if (isNaN(totalKamatis) || isNaN(totalClientes)) {
        console.error('Los valores de totalKamati o totalCliente no son números válidos.');
        return;
    }

    // Crear el objeto con los datos a enviar
    let data = {
        idIdentificadorMaquinaria: idContenedor.replace('id_Maq', ''),
        checkFactoresMaquinaria: checkFactoresMaquinariaa,
        nombreTablaMaquinaria: nombreTablaMaquinariaa,
        totalKamati: totalKamatis,
        totalCliente: totalClientes
    };
    
    try {
        // Enviar los datos al servidor utilizando fetch
        const response = await fetch('../phpServer/updateIdentificadorMaquinaria.php', {
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
function agregarEventListenersMaquinaria() {
    const contenedor = document.body;

    contenedor.addEventListener('input', (event) => {
        if (
            event.target.matches('.txtTotalKamatiMaquinaria') ||
            event.target.matches('.txtTotalClienteMaquinaria') ||
            event.target.matches('.hiddenInputFactoresIndependientesClasMaquinariaClassUnique') ||
            event.target.matches('.nombre_table-maquinariaClass')||
            event.target.matches('.materialescantidadTableMaquinaria') ||
            event.target.matches('.precio_lista_input_class_Maquinaria')
        ) {
            setTimeout(() => {
                updateMaterialesDesdeInputMaquinaria(event.target);
            }, 500);
        }
    });

    const observer = new MutationObserver((mutationsList) => {
        mutationsList.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'value') {
                if (
                    mutation.target.matches('.txtTotalKamatiMaquinaria') ||
                    mutation.target.matches('.txtTotalClienteMaquinaria') ||
                    mutation.target.matches('.hiddenInputFactoresIndependientesClasMaquinariaClassUnique')||
                    mutation.target.matches('.materialescantidadTableMaquinaria') ||
                    mutation.target.matches('.precio_lista_input_class_Maquinaria')
                ) {
                    setTimeout(() => {
        
                        updateMaterialesDesdeInputMaquinaria(mutation.target);
                    }, 500);
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
export { updateMaterialesDesdeInputMaquinaria, agregarEventListenersMaquinaria };