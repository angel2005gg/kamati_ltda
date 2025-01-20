export function recolectarDatosActividad(filasDos, insertIds) {
    const limpiarValorNumerico = valor => valor.replace(/[^0-9]/g, '');
    const actividades = [];

    filasDos.forEach((filaActividad, index) => {
        if (!filaActividad) return;

        // Asignar el insertId de turno correspondiente
        const insertIdTurno = insertIds[Math.floor(index / insertIds.length)] || insertIds[0];

        const actividad = {
            id_TurnoActividaes_fk: insertIdTurno,
            cantidad: filaActividad.querySelector(".cantidad_actividades_unique")?.value || 0,
            unidad: filaActividad.querySelector(".selectUnidadesActividadesClass")?.value || "",
            abreviaturaLinea: filaActividad.querySelector(".abreviaturas_nomClass")?.value || 0,
            descripcionBreve: filaActividad.querySelector(".descripcion_breve_classUnique")?.value || "",
            descripcionPersonal: filaActividad.querySelector(".proveedor_input_classUnique")?.value || "",
            cantidadPersonas: filaActividad.querySelector(".cantidad_persona_class_unique")?.value || 0,
            nota: filaActividad.querySelector(".nota_actividades_unique_class")?.value || "",
            costoExternoUnitario: limpiarValorNumerico(filaActividad.querySelector(".costo-externio-unitario-input")?.value || "0"),
            costoAlimentacion: limpiarValorNumerico(filaActividad.querySelector(".costoAlimentacion_input_actividades_unique_class")?.value || "0"),
            costoTransporte: limpiarValorNumerico(filaActividad.querySelector(".class_transporteInput_unique")?.value || "0"),
            costoDiaKamati: limpiarValorNumerico(filaActividad.querySelector(".valor_Dia_kamati-class")?.value || "0"),
            costoTotalDiasKamati: limpiarValorNumerico(filaActividad.querySelector(".valorDiasKamatiClass")?.value || "0"),
            valorDiaUtilidad: limpiarValorNumerico(filaActividad.querySelector(".valor-dia-utilidadClass")?.value || "0"),
            valorTotalUtilidad: limpiarValorNumerico(filaActividad.querySelector(".valorDiasClienteUtilidadClass")?.value || "0"),
            rep: filaActividad.querySelector(".select_resp_unique_actividades")?.value || "",
            checkActividades: filaActividad.querySelector(".check_new_Factor-ClassActividades")?.checked ? 1 : 0,
            factorAdicional: filaActividad.querySelector(".input-new-factor-Actividades-class")?.value || "0",
            estadoButtonAlimentacion: filaActividad.querySelector(".costoAlimentacion_hidden_uniqueclass_estadoButton")?.value === 'false' ? 1 : 0,
            estadoButtonTransporte: filaActividad.querySelector(".class_transporteHidden_unique")?.value === 'false' ? 1 : 0
        };

        actividades.push(actividad);
    });

    return actividades;
}