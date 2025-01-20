export async function saveSingleRowDataActividades(newRow) {
    try {
        console.log(newRow);

        // Busca la fila con la clase 'tr_new_tbody_turnounique_Class' más cercana hacia arriba
        let parentTurnoRow = null;
        let currentRow = newRow.previousElementSibling;

        while (currentRow) {
            if (currentRow.classList.contains('tr_new_tbody_turnounique_Class')) {
                parentTurnoRow = currentRow;
                break; // Detenemos el bucle cuando encontramos la fila
            }
            currentRow = currentRow.previousElementSibling; // Continuamos buscando hacia arriba
        }

        if (!parentTurnoRow) {
            throw new Error('No se encontró ninguna fila con la clase tr_new_tbody_turnounique_Class hacia arriba.');
        }

        // Obtiene el valor del atributo 'data-turno-id' de la fila encontrada
        const turnoId = parentTurnoRow.getAttribute('data-turno-id');
        if (!turnoId) {
            throw new Error('La fila encontrada no tiene el atributo data-turno-id.');
        }

        // Prepara los datos a enviar
        const rowData = {
            id_TurnoActividaes_fk: turnoId, // Asigna el valor del atributo aquí
            cantidad: 0,
            unidad: "",
            abreviaturaLinea: "",
            descripcionBreve: "",
            descripcionPersonal: "",
            cantidadPersonas: 0,
            nota: "",
            costoExternoUnitario: 0,
            costoAlimentacion: 0,
            costoTransporte: 0,
            costoDiaKamati: 0,
            costoTotalDiasKamati: 0,
            valorDiaUtilidad: 0,
            valorTotalUtilidad: 0,
            rep: "",
            checkActividades: 0,
            factorAdicional: 0,
            estadoButtonAlimentacion: 1,
            estadoButtonTransporte: 1,
            estadoButtonPersonal: ""
        };

        // Realiza la solicitud fetch
        const response = await fetch('../phpServer/insertDataFilaActividadesAdicional.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(rowData)
        });

        // Verifica que la respuesta fue exitosa antes de intentar leerla
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        // Lee la respuesta solo una vez como texto
        const responseText = await response.text();
        console.log(responseText); // Puedes ver la respuesta en consola para depuración

        // Ahora procesa la respuesta como JSON
        const result = JSON.parse(responseText);

        // Maneja la respuesta
        if (!result.success) {
            console.error("Error al guardar los datos:", result.message);
            alert(`Error al guardar los datos: ${result.message}`);
        } else {
            console.log("Datos guardados correctamente:", result.insertId || result.insertedIds[0]);
            alert("Datos guardados correctamente");

            // Verifica si insertedIds es un arreglo
            const insertedIds = Array.isArray(result.insertedIds) ? result.insertedIds : [result.insertedIds];

            // Asigna los IDs insertados a las filas correspondientes
            insertedIds.forEach((id, index) => {
                const clonedRow1 = newRow.querySelectorAll(".class_hidden_identificador_uniqueAc")[index];
                const clonedRow2 = newRow.querySelectorAll(".costoAlimentacion_hidden_uniqueclass_estadoButton");
                if (clonedRow1) {
                    clonedRow2.value = 1;
                    clonedRow1.value = turnoId; // Asigna el insertId a la fila correspondiente
                }
                const clonedRow = newRow.querySelectorAll(".class_hidden_id_uniqueAc")[index];
                if (clonedRow) {
                    clonedRow.value = id; // Asigna el insertId a la fila correspondiente
                }
            });
        }
    } catch (error) {
        console.error("Error al procesar los datos:", error);
    }
}