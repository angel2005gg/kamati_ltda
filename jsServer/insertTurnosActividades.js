export async function enviarTurnoYActividades(insert, contenedor) {
    const tablaTurnos = contenedor.querySelectorAll(".tbodyActividades_Clas .tr_new_tbody_turnounique_Class"); // Selector de filas de turnos
    const datos = [];

    // Aquí recorres las filas de los turnos
    tablaTurnos.forEach((turnoRow, index) => {
        // Obtener los datos del turno
        const idIdentificador = insert; // Aquí insert puede ser el id que se obtiene dinámicamente del turno
        const horaInicioTurno = turnoRow.querySelector(".starTimeClassActividades")?.value || "";
        const horaFinTurno = turnoRow.querySelector(".endTimeClassActividades")?.value || "";
        const tipoTurno = turnoRow.querySelector(".tipoDia-classActividades")?.value || "";
        const idTurnoValue = turnoRow.getAttribute('data-idTurno'); // Obtenemos el data-idTurno del turno actual

        let actividades = [];

        // Seleccionar las filas de actividades relacionadas con el turno
        const actividadesRows = contenedor.querySelectorAll(".tbodyActividades_Clas .tr_clasUnique_camposActividades");

        // Filtrar las actividades que tengan el mismo data-idTurno
        const actividadesRelacionadas = Array.from(actividadesRows).filter(actividadRow => {
            return actividadRow.getAttribute('data-idTurno') === idTurnoValue;
        });

        // Recorrer las filas de actividades relacionadas con este turno
        actividadesRelacionadas.forEach(actividadRow => {
            const selectPersonal = actividadRow.querySelector(".select-nombreCotizacionesActividades-Class");
            const inputPersonal = actividadRow.querySelector(".proveedor_input_classUnique");
            const buttonEstadoAlimentacion = actividadRow.querySelector(".costoAlimentacion_hidden_uniqueclass_estadoButton");
            const buttonEstadoTransporte = actividadRow.querySelector(".class_transporteHidden_unique");


            const limpiarValorNumerico = valor => valor.replace(/[^0-9]/g, '');  // Solo números

            // Ahora puedes agregar la actividad al arreglo de actividades
            const actividadesTurno = {
                cantidad: actividadRow.querySelector(".cantidad_actividades_unique")?.value || 0,
                unidad: actividadRow.querySelector(".selectUnidadesActividadesClass")?.value || "",
                abreviaturaLinea: actividadRow.querySelector(".abreviaturas_nomClass")?.value || "",
                descripcionBreve: (actividadRow.querySelector(".descripcion_breve_classUnique")?.value || "").replace(/,/g, ""),
                descripcionPersonal: inputPersonal?.classList.contains('hidden')
                    ? (selectPersonal?.value || "").replace(/,/g, "") // Eliminar comas si es el valor de select
                    : (inputPersonal?.value || "").replace(/,/g, ""),
                cantidadPersonas: actividadRow.querySelector(".cantidad_persona_class_unique")?.value || 0,
                nota: (actividadRow.querySelector(".nota_actividades_unique_class")?.value || "").replace(/,/g, ""),
                costoExternoUnitario: limpiarValorNumerico(actividadRow.querySelector(".costo-externio-unitario-input")?.value || "0"),
                costoAlimentacion: limpiarValorNumerico(actividadRow.querySelector(".costoAlimentacion_input_actividades_unique_class")?.value || "0"),
                costoTransporte: limpiarValorNumerico(actividadRow.querySelector(".class_transporteInput_unique")?.value || "0"),
                costoDiaKamati: limpiarValorNumerico(actividadRow.querySelector(".valor_Dia_kamati-class")?.value || "0"),
                costoTotalDiasKamati: limpiarValorNumerico(actividadRow.querySelector(".valorDiasKamatiClass")?.value || "0"),
                valorDiaUtilidad: limpiarValorNumerico(actividadRow.querySelector(".valor-dia-utilidadClass")?.value || "0"),
                valorTotalUtilidad: limpiarValorNumerico(actividadRow.querySelector(".valorDiasClienteUtilidadClass")?.value || "0"),
                rep: actividadRow.querySelector(".select_resp_unique_actividades")?.value || "",
                checkActividades: actividadRow.querySelector(".check_new_Factor-ClassActividades")?.checked ? 1 : 0,
                factorAdicional: (actividadRow.querySelector(".input-new-factor-Actividades-class")?.value || "0"),
                estadoButtonAlimentacion:
                    buttonEstadoAlimentacion?.value === '1' || buttonEstadoAlimentacion?.value === "true" ? 1 : 0,
                estadoButtonTransporte:
                    buttonEstadoTransporte?.value === '1' || buttonEstadoTransporte?.value === "true" ? 1 : 0,
                estadoButtonPersonal: actividadRow.querySelector(".inputValor-optionActividadesClass")?.value || ""
            };

            // Añadir la actividad al array de actividades
            actividades.push(actividadesTurno);
        });

        // Si es la última fila de turnos, agregar los datos con las actividades correspondientes
        if (index === tablaTurnos.length - 1) {
            datos.push({
                idIdentificador,
                horaInicioTurno,
                horaFinTurno,
                tipoTurno,
                actividades
            });
        } else {
            // Si no es la última fila, solo agregar los datos del turno con sus actividades
            datos.push({
                idIdentificador,
                horaInicioTurno,
                horaFinTurno,
                tipoTurno,
                actividades
            });
        }
    });

    // Ver los datos en consola (para depuración)
    console.log(datos);

    // Enviar los datos al servidor
    try {
        const response = await fetch('../phpServer/insertTurnoActividade.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });

        const resultado = await response.json();
        if (resultado.success) {
            alert("Datos guardados correctamente");
        } else {
            alert("Error al guardar los datos: " + resultado.message);
        }
    } catch (error) {
        console.error("Error en la solicitud:", error);
        alert("Error en la comunicación con el servidor");
    }
}