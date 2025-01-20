// Función para actualizar los materiales desde el input
async function updateFilasTurnosDesdeInput(input) {
    // Buscar la fila más cercana con la clase 'trClassMateriales' desde el input
    const fila = input.closest('.tr_new_tbody_turnounique_Class');
    if (!fila) {
        console.error('Fila de turnos no encontrada.');
        return;
    }


    // Crear el objeto de datos con los valores de los campos dentro de la fila
    const data = {
        horaInicioTurno: fila.querySelector('.starTimeClassActividades').value,
        horaFinTurno: fila.querySelector('.endTimeClassActividades').value,
        tipoTurno: fila.querySelector('.tipoDia-classActividades').value,
        idTurno: fila.querySelector('.hidden_idId_turno_ActividadeUnique_CLASS').value,
        identificadorTurno: fila.querySelector('.hidden_idIdentificadorActividadeUnique_CLASS').value
    };



    // Enviar datos al servidor
    try {
        const response = await fetch('../phpServer/updateTurnosActividades.php', {
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


// Función para agregar listeners a los campos de las filas
// Función para agregar listeners a los campos de las filas existentes y nuevas
async function addListenersToRowFieldsTurnoActividades() {
    function addListeners(fila) {
        const inputs = fila.querySelectorAll('input[type="text"], input[type="time"]');
        inputs.forEach(input => {
            if (!input.hasAttribute('data-listener-input')) {
                input.addEventListener('input', () => updateFilasTurnosDesdeInput(input));
                input.setAttribute('data-listener-input', 'true');
            }
        });

        const selects = fila.querySelectorAll('.tipoDia-classActividades');
        selects.forEach(select => {
            if (!select.hasAttribute('data-listener-change')) {
                select.addEventListener('change', () => {
                    updateFilasTurnosDesdeInput(select);
                });
                select.setAttribute('data-listener-change', 'true');
            }
        });
    }

    // Añadir listeners a las filas existentes
    const filas = document.querySelectorAll('.tr_new_tbody_turnounique_Class');
    filas.forEach(fila => addListeners(fila));

    // Observar cambios en el DOM para nuevas filas
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.classList.contains('tr_new_tbody_turnounique_Class')) {
                            addListeners(node); // Añadir listeners a la nueva fila
                        } else if (node.closest('.tr_new_tbody_turnounique_Class')) {
                            addListeners(node.closest('.tr_new_tbody_turnounique_Class'));
                        }
                    }
                });
            }
        });
    });

    // Observa el contenedor que contiene las filas
    const contenedor = document.querySelector('.table-actividades-Class-container');
    if (contenedor) {
        observer.observe(contenedor, { childList: true, subtree: true });
    } else {
        console.error('Contenedor principal no encontrado para MutationObserver.');
    }

    // Delegación de eventos global como respaldo
    document.body.addEventListener('input', (event) => {
        if (event.target.closest('.tr_new_tbody_turnounique_Class')) {
            updateFilasTurnosDesdeInput(event.target);
        }
    });

    document.body.addEventListener('change', (event) => {
        if (event.target.closest('.tr_new_tbody_turnounique_Class')) {
            updateFilasTurnosDesdeInput(event.target);
        }
    });
}

export { addListenersToRowFieldsTurnoActividades };


