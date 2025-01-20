// Función para actualizar los materiales desde el input
async function updateFilasMaquinariaDesdeInput(input) {
    // Buscar la fila más cercana con la clase 'trClassMateriales' desde el input
    const fila = input.closest('.filasViaticos_tr_class_unique_ac');
    if (!fila) {
        console.error('Fila de materiales no encontrada.');
        return;
    }
 

    // Crear el objeto de datos con los valores de los campos dentro de la fila
    const data = {
        idIdentificador: fila.querySelector('.identificadorUpdateacUnique').value,
        idViatico: fila.querySelector('.iDUpdateacUnique').value,
        valorViatico: quitarPuntosMiles(fila.querySelector('.valorActividadesViaticosUnique').value)          
    };

    

    // Enviar datos al servidor
    try {
        const response = await fetch('../phpServer/updateViaticosActividadesInd.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

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

function quitarPuntosMiles(valor) {
    // Eliminar todos los caracteres que no sean dígitos o puntos decimales
    valor = valor.replace(/[^0-9.,]/g, '');

    // Eliminar puntos de miles
    valor = valor.replace(/\./g, '');

    // Reemplazar la coma por un punto para decimales
    valor = valor.replace(',', '.');

    return valor;
}

// Función para agregar listeners a los campos de las filas
// Función para agregar listeners a los campos de las filas existentes y nuevas
async function addListenersToRowFieldsViaticosActividades() {
    function addListeners(fila) {
        const inputs = fila.querySelectorAll('input[type="text"]');
        inputs.forEach(input => {
            if (!input.hasAttribute('data-listener-input')) {
                input.addEventListener('input', () => updateFilasMaquinariaDesdeInput(input));
                input.setAttribute('data-listener-input', 'true');
            }
        });
    }

    const filas = document.querySelectorAll('.filasViaticos_tr_class_unique_ac');
    filas.forEach(fila => addListeners(fila));

    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1 && node.classList.contains('filasViaticos_tr_class_unique_ac')) {
                    addListeners(node);
                }
            });
        });
    });

    const contenedor = document.querySelector('.table-actividades-Class-container');
    if (contenedor) {
        observer.observe(contenedor, { childList: true });
    } else {
        console.error('Contenedor principal no encontrado.');
    }
}

export { addListenersToRowFieldsViaticosActividades };


