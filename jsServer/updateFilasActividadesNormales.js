// Función para actualizar los materiales desde el input
async function updateFilasActividadesInput(input) {
    // Buscar la fila más cercana con la clase 'trClassMateriales' desde el input
    const fila = input.closest('.filaclonableunica_actividades_Class');
    if (!fila) {
        console.error('Fila de turnos no encontrada.');
        return;
    }
    const limpiarValorNumerico = valor => valor.replace(/[^0-9]/g, '');  // Solo números
    const selectPersonal = fila.querySelector(".select-nombreCotizacionesActividades-Class");
    const inputPersonal = fila.querySelector(".proveedor_input_classUnique");
    const estadoButtonAlimentacion = (fila) => {
        const alimentacionValue = fila.querySelector(".costoAlimentacion_hidden_uniqueclass_estadoButton")?.value || "0";
        return alimentacionValue === "0" ? "0" : "1";
    };
    
    const estadoButtonTransporte = (fila) => {
        const transporteValue = fila.querySelector(".class_transporteHidden_unique")?.value || "0";
        return transporteValue === "0" ? "0" : "1";
    };

    // Crear el objeto de datos con los valores de los campos dentro de la fila
    const data = {
        id_TurnoActividaes_fk: fila.querySelector(".class_hidden_identificador_uniqueAc")?.value || 0,
        id_TablaActividades: fila.querySelector(".class_hidden_id_uniqueAc")?.value || 0,
        cantidad: fila.querySelector(".cantidad_actividades_unique")?.value || 0,
        unidad: fila.querySelector(".selectUnidadesActividadesClass")?.value || "",
        abreviaturaLinea: fila.querySelector(".abreviaturas_nomClass")?.value || "",
        descripcionBreve: (fila.querySelector(".descripcion_breve_classUnique")?.value || "").replace(/,/g, ""),
        descripcionPersonal: inputPersonal?.classList.contains('hidden')
            ? (selectPersonal?.value).replace(/,/g, "") || ""
            : (inputPersonal?.value).replace(/,/g, "") || "",
        cantidadPersonas: fila.querySelector(".cantidad_persona_class_unique")?.value || 0,
        nota: (fila.querySelector(".nota_actividades_unique_class")?.value || "").replace(/,/g, ""),
        costoExternoUnitario: limpiarValorNumerico(fila.querySelector(".costo-externio-unitario-input")?.value || "0"),
        costoAlimentacion: limpiarValorNumerico(fila.querySelector(".costoAlimentacion_input_actividades_unique_class")?.value || "0"),
        costoTransporte: limpiarValorNumerico(fila.querySelector(".class_transporteInput_unique")?.value || "0"),
        costoDiaKamati: limpiarValorNumerico(fila.querySelector(".valor_Dia_kamati-class")?.value || "0"),
        costoTotalDiasKamati: limpiarValorNumerico(fila.querySelector(".valorDiasKamatiClass")?.value || "0"),
        valorDiaUtilidad: limpiarValorNumerico(fila.querySelector(".valor-dia-utilidadClass")?.value || "0"),
        valorTotalUtilidad: limpiarValorNumerico(fila.querySelector(".valorDiasClienteUtilidadClass")?.value || "0"),
        rep: fila.querySelector(".select_resp_unique_actividades")?.value || "",
        checkActividades: fila.querySelector(".check_new_Factor-ClassActividades")?.checked ? 1 : 0,
        factorAdicional: (fila.querySelector(".input-new-factor-Actividades-class")?.value || "0"),
        estadoButtonAlimentacion: estadoButtonAlimentacion(fila),
        estadoButtonTransporte: estadoButtonTransporte(fila),
        estadoButtonPersonal: fila.querySelector(".inputValor-optionActividadesClass")?.value || ""
    };



    // Enviar datos al servidor
    try {
        const response = await fetch('../phpServer/updateFilasTablaActividadesNormales.php', {
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
// Función para agregar listeners a los campos de las filas existentes y nuevas
async function addListenersToRowFieldsActividadesNormales() {
    function addListeners(fila) {
        // Listener para inputs de texto, tiempo y textarea
        const inputs = fila.querySelectorAll('input[type="text"], input[type="time"], textarea');
        inputs.forEach(input => {
            if (!input.hasAttribute('data-listener-input')) {
                input.addEventListener('input', () => updateFilasActividadesInput(input));
                input.setAttribute('data-listener-input', 'true');
            }
        });

        // Listener para selects
        const selects = fila.querySelectorAll('.selectUnidadesActividadesClass, .abreviaturas_nomClass, .select-nombreCotizacionesActividades-Class, .select_resp_unique_actividades');
        selects.forEach(select => {
            if (!select.hasAttribute('data-listener-change')) {
                select.addEventListener('change', () => updateFilasActividadesInput(select));
                select.setAttribute('data-listener-change', 'true');
            }
        });

        // Listener para botones que modifican campos hidden
        const toggleButtons = fila.querySelectorAll('.toggle-readonly-btn');
        toggleButtons.forEach(button => {
            if (!button.hasAttribute('data-listener-click')) {
                button.addEventListener('click', () => {
                    const hiddenFields = fila.querySelectorAll('.costoAlimentacion_hidden_uniqueclass_estadoButton, .class_transporteHidden_unique');
                    hiddenFields.forEach(hiddenField => {
                        updateFilasActividadesInput(hiddenField); // Actualizar tras cambios en los campos hidden
                    });
                });
                button.setAttribute('data-listener-click', 'true');
            }
        });

        // Observador para campos hidden
        const hiddenFields = fila.querySelectorAll('.costoAlimentacion_hidden_uniqueclass_estadoButton, .class_transporteHidden_unique');
        hiddenFields.forEach(hiddenField => {
            if (!hiddenField.hasAttribute('data-observer')) {
                const observer = new MutationObserver(() => {
                    updateFilasActividadesInput(hiddenField);
                });
                observer.observe(hiddenField, { attributes: true, attributeFilter: ['value'] });
                hiddenField.setAttribute('data-observer', 'true'); // Marcar como observado
            }
        });
    }

    // Añadir listeners a las filas existentes
    const filas = document.querySelectorAll('.filaclonableunica_actividades_Class');
    filas.forEach(fila => addListeners(fila));

    // Observar cambios en el DOM para nuevas filas
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        if (node.classList.contains('filaclonableunica_actividades_Class')) {
                            addListeners(node); // Añadir listeners a la nueva fila
                        } else if (node.closest('.filaclonableunica_actividades_Class')) {
                            addListeners(node.closest('.filaclonableunica_actividades_Class'));
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
        if (event.target.closest('.filaclonableunica_actividades_Class')) {
            updateFilasActividadesInput(event.target);
        }
    });

    document.body.addEventListener('change', (event) => {
        if (event.target.closest('.filaclonableunica_actividades_Class')) {
            updateFilasActividadesInput(event.target);
        }
    });
}

export { addListenersToRowFieldsActividadesNormales };