// Función para actualizar los materiales desde el input
async function updateFilasMaquinariaDesdeInput(input) {
    // Buscar la fila más cercana con la clase 'trClassMateriales' desde el input
    const fila = input.closest('.trClassMquinaria');
    if (!fila) {
        console.error('Fila de materiales no encontrada.');
        return;
    }
    console.log(fila.querySelector('.id_fila_MaquinariaTable_class').value);

    // Crear el objeto de datos con los valores de los campos dentro de la fila
    const data = {
        idIdentificador: fila.querySelector('.id_fila_MaquinariaTable_class').value,
        cantidadMateriales: quitarPuntosMiles(fila.querySelector('.materialescantidadTableMaquinaria').value),
        id_TablaMateriales: fila.querySelector('.id_fila_MaquinariaTable_class_clonedUpdate').value,

        // Validación y asignación de valores para campos de texto
        unidadesMateriales: fila.querySelector('.select_unidades_maquinaria_table_class').value.trim() === "" ? "" : fila.querySelector('.select_unidades_maquinaria_table_class').value,
        abreviaturaMateriales: fila.querySelector('.select_abreviatura_maquinaria_class').value.trim() === "" ? "" : fila.querySelector('.select_abreviatura_maquinaria_class').value,
        referenciaMateriales: (fila.querySelector('.textarea_referencia_maquinaria_class').value.trim() === "" ? "" : fila.querySelector('.textarea_referencia_maquinaria_class').value).replace(/,/g, ""),
        material: (fila.querySelector('.textarea_maquinariaMaterial_class').value.trim() === "" ? "" : fila.querySelector('.textarea_maquinariaMaterial_class').value).replace(/,/g, ""),
        descripcionMaterial: (fila.querySelector('.textarea_descripcionmaquinaria_class').value.trim() === "" ? "" : fila.querySelector('.textarea_descripcionmaquinaria_class').value).replace(/,/g, ""),
        proveedorMateriales: fila.querySelector('.select_proveedor_Maquinaria_class')?.classList.contains('hidden')
            ? (fila.querySelector('.input_proveedor_Maquinaria_class')?.value.trim()).replace(/,/g, "") || ""
            : (fila.querySelector('.select_proveedor_Maquinaria_class')?.value.trim()).replace(/,/g, "") || "",

        estadoButton: fila.querySelector('.input_proveedor_Maquinaria_class')?.classList.contains('hidden')
            ? 0
            : 1,
        notaMateriales: (fila.querySelector('.nota_maquinaria_uniqueclass').value.trim() === "" ? "" : fila.querySelector('.nota_maquinaria_uniqueclass').value).replace(/,/g, ""),
        tipoMoneda: fila.querySelector('.selet_trm_Maquinaria_class').value.trim() === "" ? "" : fila.querySelector('.selet_trm_Maquinaria_class').value,

        // Validación y asignación para campos numéricos
        preciolistaMateriales: quitarPuntosMiles(fila.querySelector('.precio_lista_input_class_Maquinaria').value),
        costoUnitarioKamatiMateriales: quitarPuntosMiles(fila.querySelector('.cost_kamati_input_class_Maquinaria').value),
        costoTotalKamatiMateriales: quitarPuntosMiles(fila.querySelector('.cost_kamati_total_class_Maquinaria').value),
        valorUtilidadMateriales: quitarPuntosMiles(fila.querySelector('.valor_utilidad_class_Maquinaria').value),
        valorTotalUtilidadMateriales: quitarPuntosMiles(fila.querySelector('.value_total_input_class_Maquinaria').value),
        descuentoMateriales: fila.querySelector('.descuento_input_Maquinaria_class').value.trim() === "" ? 0 : fila.querySelector('.descuento_input_Maquinaria_class').value,
        descuentoAdicional: fila.querySelector('.descuento_adicional_input_Maquinaria_class').value.trim() === "" ? 0 : fila.querySelector('.descuento_adicional_input_Maquinaria_class').value,

        // Validación y asignación para campos de fecha
        fechaTiempoEntregaMateriales: (fila.querySelector('.date_input_entrega_class_Maquinaria')?.value || "").toString(),

        // Validación para el check box (estado)
        factorAdicionalMateriales: fila.querySelector(".check_estado_class_Maquinaria")?.checked
            ? (fila.querySelector('.factor_adicional_class_Maquinaria').value.trim() === "" ? 0 : fila.querySelector('.factor_adicional_class_Maquinaria').value)
            : 0,

        checkEstadoMateriales: fila.querySelector(".check_estado_class_Maquinaria")?.checked ? 1 : 0,
        revisionMateriales: fila.querySelector('.select_rep_classMaquinaria').value.trim() === "" ? "" : fila.querySelector('.select_rep_classMaquinaria').value,
        tiempoEntregaMateriales: fila.querySelector('.valor_tiempo_entrega_class_Maquinaria').value.trim() === "" ? "" : fila.querySelector('.valor_tiempo_entrega_class_Maquinaria').value,
    };

    

    // Enviar datos al servidor
    try {
        const response = await fetch('../phpServer/updateFilasTablaMaquinaria.php', {
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
async function addListenersToRowFieldsMaquinaria() {
    // Función que añade los listeners a los inputs, selects y otros elementos
    function addListeners(fila) {
        const allElements = fila.querySelectorAll('input, textarea, select');

        allElements.forEach(element => {
            if (!element.hasAttribute('data-listener')) {
                if (element.matches('input[type="text"], textarea, input[type="hidden"], input[type="checkbox"]')) {
                    element.addEventListener('input', () => updateFilasMaquinariaDesdeInput(element));
                } else if (element.matches('.date_input_entrega_class_Maquinaria')) {
                    element.addEventListener('change', () => updateFilasMaquinariaDesdeInput(element));
                } else if (element.matches('select')) {
                    element.addEventListener('change', () => updateFilasMaquinariaDesdeInput(element));
                }
                element.setAttribute('data-listener', 'true');
            }
        });
    }

    // Añadir listeners a las filas existentes
    const filas = document.querySelectorAll('.trClassMquinaria');
    filas.forEach(fila => addListeners(fila));

    // Observar cambios en el DOM para nuevas filas
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.classList.contains('trClassMquinaria')) {
                            addListeners(node); // Añadir listeners a la nueva fila
                        } else if (node.closest('.trClassMquinaria')) {
                            addListeners(node.closest('.trClassMquinaria'));
                        }
                    }
                });
            }
        });
    });

    // Observa el contenedor que contiene las filas
    const contenedor = document.querySelector('.table_maquinariaClas');
    if (contenedor) {
        observer.observe(contenedor, { childList: true, subtree: true });
    } else {
        console.error('Contenedor principal no encontrado para MutationObserver.');
    }

    // Delegación de eventos global como respaldo
    document.body.addEventListener('input', (event) => {
        if (event.target.closest('.trClassMquinaria')) {
            updateFilasMaquinariaDesdeInput(event.target);
        }
    });

    document.body.addEventListener('change', (event) => {
        if (event.target.closest('.trClassMquinaria')) {
            updateFilasMaquinariaDesdeInput(event.target);
        }
    });
}

export { addListenersToRowFieldsMaquinaria };


