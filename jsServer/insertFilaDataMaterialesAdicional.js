export async function saveSingleRowData(newRow, insertId) {
    try {
        // Formato de número
        const formatNumber = (numStr) => {
            if (!numStr) return "";
            const sanitizedStr = numStr.replace(/[^\d.,]/g, ''); // Remueve caracteres no numéricos
            return parseFloat(sanitizedStr.replace(/\./g, '').replace(/,/g, '.')) || 0;
        };
        console.log(insertId);

        // Prepara los datos a enviar
        const rowData = {
            id_IdentificadorMateriales_fk_mat: insertId,
            cantidadMateriales: "",
            unidadesMateriales: "",
            abreviaturaMateriales: "",
            referenciaMateriales: "",
            material: "",
            descripcionMaterial: "",
            proveedorMateriales: "",
            estadoButton: 0,
            notaMateriales: "",
            tipoMoneda: "",
            preciolistaMateriales: 0,
            costoUnitarioKamatiMateriales: 0,
            costoTotalKamatiMateriales: 0,
            valorUtilidadMateriales: 0,
            valorTotalUtilidadMateriales: 0,
            tiempoEntregaMateriales: "",
            descuentoMateriales: "",
            descuentoAdicional: "",
            fechaTiempoEntregaMateriales: "",
            revisionMateriales: "",
            checkEstadoMateriales: 0,
            factorAdicionalMateriales: ""
        };

        // Realiza la solicitud fetch
        const response = await fetch('../phpServer/insertDataFilaMaterialesAdicional.php', {
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
            console.log("Datos guardados correctamente:", result.insertedIds || result.insertedIds[0]);
            alert("Datos guardados correctamente");

            // Verifica si insertedIds es un arreglo
            const insertedIds = Array.isArray(result.insertedIds) ? result.insertedIds : [result.insertedIds];

            // Asigna los IDs insertados a las filas correspondientes
            insertedIds.forEach((id, index) => {
                const clonedRow1 = newRow.querySelectorAll(".id_fila_materialesTable_class")[index];
                if (clonedRow1) {
                    clonedRow1.value = insertId; // Asigna el insertId a la fila correspondiente
                }
                const clonedRow = newRow.querySelectorAll(".id_fila_materialesTable_class_clonedUpdate")[index];
                if (clonedRow) {
                    clonedRow.value = id; // Asigna el insertId a la fila correspondiente
                }
            });
        }
    } catch (error) {
        console.error("Error al procesar los datos:", error);
    }
}