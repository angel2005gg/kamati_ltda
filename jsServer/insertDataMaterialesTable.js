export async function saveTableData(insertId, clonedTableContainer) {
    try {
        const tableRows = document.querySelectorAll(".table_original_materiales_class_unique .trClassMateriales");
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

            const inputProveedor = row.querySelector('.input_proveedor_materiales_class');
            const selectProveedor = row.querySelector('.select_proveedor_materiales_class');

            const rowData = {
                id_IdentificadorMateriales_fk_mat: insertId,
                cantidadMateriales: row.querySelector(".materialescantidadTable")?.value.replace(/\./g, '') || "",
                unidadesMateriales: row.querySelector(".select_unidades_materiales_table_class")?.value || "",
                abreviaturaMateriales: row.querySelector(".select_abreviatura_materiales_class")?.value || "",
                referenciaMateriales: (row.querySelector(".textarea_referencia_materiales_class")?.value || "").replace(/,/g, ""), // Eliminar comas
                material: (row.querySelector(".textarea_material_class")?.value || "").replace(/,/g, ""), // Eliminar comas
                descripcionMaterial: (row.querySelector(".textarea_descripcionMaterial_class")?.value || "").replace(/,/g, ""), // Eliminar comas
                proveedorMateriales: (inputProveedor?.classList.contains('hidden') ? selectProveedor?.value : inputProveedor?.value || "").replace(/,/g, ""), // Eliminar comas
                estadoButton: row.querySelector(".select_proveedor_materiales_class")?.classList.contains("hidden") ? 1 : 0,
                notaMateriales: (row.querySelector(".textarea_nota_materiales_class")?.value || "").replace(/,/g, ""), // Eliminar comas
                tipoMoneda: row.querySelector(".selet_trm_materiales_class")?.value || "",
                preciolistaMateriales: formatNumber(row.querySelector(".precio_lista_input_class_materiales")?.value),
                costoUnitarioKamatiMateriales: formatNumber(row.querySelector(".cost-kamati-input_class_materiales")?.value),
                costoTotalKamatiMateriales: formatNumber(row.querySelector(".cost-kamati-total_class_materiales")?.value),
                valorUtilidadMateriales: formatNumber(row.querySelector(".valor-utilidad_class_materiales")?.value),
                valorTotalUtilidadMateriales: formatNumber(row.querySelector(".value-total-input_class_materiales")?.value),
                tiempoEntregaMateriales: row.querySelector(".valor_tiempo_entrega_class_materialesa")?.value || "",
                descuentoMateriales: row.querySelector(".descuento-input_materiales_class")?.value || "",
                descuentoAdicional: row.querySelector(".descuento-adicional-input_materiales_class")?.value || "",
                fechaTiempoEntregaMateriales: row.querySelector(".date_input_entrega_class_materiales")?.value || "",
                revisionMateriales: row.querySelector(".select_rep_classMateriales")?.value || "",
                checkEstadoMateriales: row.querySelector(".check_estado_class_materiales")?.checked ? 1 : 0,
                factorAdicionalMateriales: row.querySelector(".factor_adicional_class_materiales")?.value
            };

            rowsData.push(rowData);
        });

        const response = await fetch('../phpServer/insertTableMateriales.php', {
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
                
                const clonedRow1 = clonedTableContainer.querySelectorAll(".id_fila_materialesTable_class")[index];
                if (clonedRow1) {
                    clonedRow1.value = insertId; // Asigna el insertId a la fila correspondiente
                }
                const clonedRow = clonedTableContainer.querySelectorAll(".id_fila_materialesTable_class_clonedUpdate")[index];
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