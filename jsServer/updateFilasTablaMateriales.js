// Función para actualizar los materiales desde el input
async function updateFilasMaterialesDesdeInput(input) {
    // Buscar la fila más cercana con la clase 'trClassMateriales' desde el input
    const fila = input.closest('.trClassMateriales');
    if (!fila) {
        console.error('Fila de materiales no encontrada.');
        return;
    }

    // Obtener el id del contenedor, que es un contenedor superior
    const contenedor = input.closest('[id^="id_Mat"]');
    if (!contenedor) {
        console.error('Contenedor principal no encontrado.');
        return;
    }


    // Crear el objeto de datos con los valores de los campos dentro de la fila
    const data = {
        idIdentificador: fila.querySelector('.id_fila_materialesTable_class').value,
        cantidadMateriales: quitarPuntosMiles(fila.querySelector('.materialescantidadTable').value),
        id_TablaMateriales: fila.querySelector('.id_fila_materialesTable_class_clonedUpdate').value,

        // Validación y asignación de valores para campos de texto
        unidadesMateriales: fila.querySelector('.select_unidades_materiales_table_class').value.trim() === "" ? "" : fila.querySelector('.select_unidades_materiales_table_class').value,
        abreviaturaMateriales: fila.querySelector('.select_abreviatura_materiales_class').value.trim() === "" ? "" : fila.querySelector('.select_abreviatura_materiales_class').value,
        referenciaMateriales: (fila.querySelector('.textarea_referencia_materiales_class').value.trim() === "" ? "" : fila.querySelector('.textarea_referencia_materiales_class').value).replace(/,/g, ""),
        material: (fila.querySelector('.textarea_material_class').value.trim() === "" ? "" : fila.querySelector('.textarea_material_class').value).replace(/,/g, ""),
        descripcionMaterial: (fila.querySelector('.textarea_descripcionMaterial_class').value.trim() === "" ? "" : fila.querySelector('.textarea_descripcionMaterial_class').value).replace(/,/g, ""),
        proveedorMateriales: fila.querySelector('.select_proveedor_materiales_class')?.classList.contains('hidden')
            ? (fila.querySelector('.input_proveedor_materiales_class')?.value.trim()).replace(/,/g, "") || ""
            : (fila.querySelector('.select_proveedor_materiales_class')?.value.trim()).replace(/,/g, "") || "",

        estadoButton: fila.querySelector('.input_proveedor_materiales_class')?.classList.contains('hidden')
            ? 0
            : 1,
        notaMateriales: (fila.querySelector('.textarea_nota_materiales_class').value.trim() === "" ? "" : fila.querySelector('.textarea_nota_materiales_class').value).replace(/,/g, ""),
        tipoMoneda: fila.querySelector('.selet_trm_materiales_class').value.trim() === "" ? "" : fila.querySelector('.selet_trm_materiales_class').value,

        // Validación y asignación para campos numéricos
        preciolistaMateriales: quitarPuntosMiles(fila.querySelector('.precio_lista_input_class_materiales').value),
        costoUnitarioKamatiMateriales: quitarPuntosMiles(fila.querySelector('.cost-kamati-input_class_materiales').value),
        costoTotalKamatiMateriales: quitarPuntosMiles(fila.querySelector('.cost-kamati-total_class_materiales').value),
        valorUtilidadMateriales: quitarPuntosMiles(fila.querySelector('.valor-utilidad_class_materiales').value),
        valorTotalUtilidadMateriales: quitarPuntosMiles(fila.querySelector('.value-total-input_class_materiales').value),
        descuentoMateriales: fila.querySelector('.descuento-input_materiales_class').value.trim() === "" ? 0 : fila.querySelector('.descuento-input_materiales_class').value,
        descuentoAdicional: fila.querySelector('.descuento-adicional-input_materiales_class').value.trim() === "" ? 0 : fila.querySelector('.descuento-adicional-input_materiales_class').value,

        // Validación y asignación para campos de fecha
        fechaTiempoEntregaMateriales: (fila.querySelector('.date_input_entrega_class_materiales')?.value || "").toString(),

        // Validación para el check box (estado)
        factorAdicionalMateriales: fila.querySelector(".check_estado_class_materiales")?.checked
            ? (fila.querySelector('.factor_adicional_class_materiales').value.trim() === "" ? 0 : fila.querySelector('.factor_adicional_class_materiales').value)
            : 0,

        checkEstadoMateriales: fila.querySelector(".check_estado_class_materiales")?.checked ? 1 : 0,
        revisionMateriales: fila.querySelector('.select_rep_classMateriales').value.trim() === "" ? "" : fila.querySelector('.select_rep_classMateriales').value,
        tiempoEntregaMateriales: fila.querySelector('.valor_tiempo_entrega_class_materialesa').value.trim() === "" ? "" : fila.querySelector('.valor_tiempo_entrega_class_materialesa').value,
    };

    console.log(data.id_TablaMateriales);
    console.log(data.idIdentificador);
    

    // Enviar datos al servidor
    try {
        const response = await fetch('../phpServer/updateFilasTablaMateriales.php', {
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
async function addListenersToRowFields() {
    // Función que añade los listeners a los inputs, selects y otros elementos
    function addListeners(fila) {
        const allElements = fila.querySelectorAll('input, textarea, select');

        allElements.forEach(element => {
            if (!element.hasAttribute('data-listener')) {
                if (element.matches('input[type="text"], textarea, input[type="hidden"], input[type="checkbox"]')) {
                    element.addEventListener('input', () => updateFilasMaterialesDesdeInput(element));
                } else if (element.matches('.date_input_entrega_class_materiales')) {
                    element.addEventListener('change', () => updateFilasMaterialesDesdeInput(element));
                } else if (element.matches('select')) {
                    element.addEventListener('change', () => updateFilasMaterialesDesdeInput(element));
                }
                element.setAttribute('data-listener', 'true');
            }
        });
    }

    // Añadir listeners a las filas existentes
    const filas = document.querySelectorAll('.trClassMateriales');
    filas.forEach(fila => addListeners(fila));

    // Observar cambios en el DOM para nuevas filas
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.classList.contains('trClassMateriales')) {
                            addListeners(node); // Añadir listeners a la nueva fila
                        } else if (node.closest('.trClassMateriales')) {
                            addListeners(node.closest('.trClassMateriales'));
                        }
                    }
                });
            }
        });
    });

    // Observa el contenedor que contiene las filas
    const contenedor = document.querySelector('.table_materialesClas');
    if (contenedor) {
        observer.observe(contenedor, { childList: true, subtree: true });
    } else {
        console.error('Contenedor principal no encontrado para MutationObserver.');
    }

    // Delegación de eventos global como respaldo
    document.body.addEventListener('input', (event) => {
        if (event.target.closest('.trClassMateriales')) {
            updateFilasMaterialesDesdeInput(event.target);
        }
    });

    document.body.addEventListener('change', (event) => {
        if (event.target.closest('.trClassMateriales')) {
            updateFilasMaterialesDesdeInput(event.target);
        }
    });
}
export { addListenersToRowFields };


