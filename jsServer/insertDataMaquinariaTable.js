
export async function saveTableDataMaquinaria(insertId, clonedTableContainer) {
    try {
        const tableRows = document.querySelectorAll(".table_original_maquinaria_class_unique .trClassMquinaria");
        const rowsData = [];

        tableRows.forEach((row, index) => {
            
            // Función para quitar puntos y convertir comas en puntos
            const formatNumber = (numStr) => {
                if (!numStr) return "";
                // Eliminar cualquier carácter que no sea un número o un separador decimal
                const sanitizedStr = numStr.replace(/[^\d.,]/g, '');
                // Reemplazar puntos y comas para dejar solo el número sin formato
                return parseFloat(sanitizedStr.replace(/\./g, '').replace(/,/g, '.')) || 0;
            };

            const inputProveedor = row.querySelector('.input_proveedor_Maquinaria_class');
            const selectProveedor = row.querySelector('.select_proveedor_Maquinaria_class');

            const rowData = {
                id_IdentificadorMaquinaria_fk_maq: insertId,
                cantidadMaquinaria: row.querySelector(".materialescantidadTableMaquinaria")?.value.replace(/\./g, '') || "",
                unidadesMaquinaria: row.querySelector(".select_unidades_maquinaria_table_class")?.value || "",
                abreviaturaMaquinaria: row.querySelector(".select_abreviatura_maquinaria_class")?.value || "",
                referenciaMaquinaria: (row.querySelector(".textarea_referencia_maquinaria_class")?.value || "").replace(/,/g, ""),
                materialMaquinaria: (row.querySelector(".textarea_maquinariaMaterial_class")?.value || "").replace(/,/g, ""),
                descripcionMaterial: (row.querySelector(".textarea_descripcionmaquinaria_class")?.value || "").replace(/,/g, ""),
                proveedorMaquinaria: (inputProveedor?.classList.contains('hidden') ? selectProveedor?.value : inputProveedor?.value || "").replace(/,/g, ""),
                estadoButton: row.querySelector(".select_proveedor_Maquinaria_class")?.classList.contains("hidden") ? 1 : 0,
                notaMaquinaria: (row.querySelector(".textarea_nota_Maquinaria_class")?.value || "").replace(/,/g, ""),
                tipoMoneda: row.querySelector(".selet_trm_Maquinaria_class")?.value || "",
                preciolistaMaquinaria: formatNumber(row.querySelector(".precio_lista_input_class_Maquinaria")?.value),
                costoUnitarioKamatiMaquinaria: formatNumber(row.querySelector(".cost_kamati_input_class_Maquinaria")?.value),
                costoTotalKamatiMaquinaria: formatNumber(row.querySelector(".cost_kamati_total_class_Maquinaria")?.value),
                valorUtilidadMaquinaria: formatNumber(row.querySelector(".valor_utilidad_class_Maquinaria")?.value),
                valorTotalUtilidadMaquinaria: formatNumber(row.querySelector(".value_total_input_class_Maquinaria")?.value),
                tiempoEntregaMaquinaria: row.querySelector(".valor_tiempo_entrega_class_Maquinaria")?.value || "",
                descuentoMaquinaria: row.querySelector(".descuento_input_Maquinaria_class")?.value || "",
                descuentoAdicional: row.querySelector(".descuento_adicional_input_Maquinaria_class")?.value || "",
                fechaTiempoEntregaMaquinaria: row.querySelector(".date_input_entrega_class_Maquinaria")?.value || "",
                revisionMaquinaria: row.querySelector(".select_rep_classMaquinaria")?.value || "",
                checkEstadoMaquinaria: row.querySelector(".check_estado_class_Maquinaria")?.checked ? 1 : 0,
                factorAdicionalMaquinaria: row.querySelector(".factor_adicional_class_Maquinaria")?.value
            };

            rowsData.push(rowData);
        });

        const response = await fetch('../phpServer/insertTableMaquinaria.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(rowsData)
        });

        const result = await response.json();

        if (!result.success) {
            console.error("Error al guardar los datos:", result.message, result.insertedIds);
            alert("Error al guardar los datos: " + result.message);
        } else {
            console.log("Datos guardados correctamente:", result.insertedIds);
            alert("Datos guardados correctamente");
            // Asigna los insertId devueltos a las filas clonadas
           
            result.insertedIds.forEach((id, index) => {
                
                const clonedRow1 = clonedTableContainer.querySelectorAll(".id_fila_MaquinariaTable_class")[index];
                if (clonedRow1) {
                    clonedRow1.value = insertId; // Asigna el insertId a la fila correspondiente
                }
                const clonedRow = clonedTableContainer.querySelectorAll(".id_fila_MaquinariaTable_class_clonedUpdate")[index];
                if (clonedRow) {
                    clonedRow.value = id; // Asigna el insertId a la fila correspondiente
                }
            });
        }

    } catch (error) {
        console.error("Error al procesar los datos:", error);
        alert("Error de red o de servidor.");
    }
}