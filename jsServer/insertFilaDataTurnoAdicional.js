export async function saveSingleRowDataTurnos(newRow, insertId) {
    try {
        // Formato de número
       

        // Prepara los datos a enviar
        const rowData = {
            idIdentificador: insertId,
            horaInicioTurno: "",
            horaFinTurno: "",
            tipoTurno: "Dia_semana"
        };

        // Realiza la solicitud fetch
        const response = await fetch('../phpServer/insertDataFilaTurnoadicional.php', {
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
        console.log(responseText);  // Puedes ver la respuesta en consola para depuración

        // Ahora procesa la respuesta como JSON
        const result = JSON.parse(responseText);

        // Maneja la respuesta
        if (!result.success) {
            console.error("Error al guardar los datos:", result.message);
            alert(`Error al guardar los datos: ${result.message}`);
        } else {
            console.log("Datos guardados correctamente:", result.insertIds || result.insertedIds[0]);
            alert("Datos guardados correctamente");

            // Verifica si insertedIds es un arreglo
            const insertedIds = Array.isArray(result.insertedIds) ? result.insertedIds : [result.insertedIds];

            // Asigna los IDs insertados a las filas correspondientes
            insertedIds.forEach((id, index) => {
                newRow.setAttribute('data-turno-id', result.insertedIds);
                const clonedRow1 = newRow.querySelectorAll(".hidden_idIdentificadorActividadeUnique_CLASS")[index];
                if (clonedRow1) {
                    clonedRow1.value = insertId;
                     // Asigna el insertId a la fila correspondiente
                }
                const clonedRow = newRow.querySelectorAll(".hidden_idId_turno_ActividadeUnique_CLASS")[index];
                if (clonedRow) {
                    clonedRow.value = id; // Asigna el insertId a la fila correspondiente
                }
            });
        }
    } catch (error) {
        console.error("Error al procesar los datos:", error);
    }
}