export async function enviarDatosTarifaCargosActividadesIndependientes(insertId, clonedTableContainer) {
    const filas = clonedTableContainer.querySelectorAll(".tbody_actividades-hiddenClass tr");
    const datosViaticos = [];

    filas.forEach((fila, index) => {
        // Obtener el valor del nombre del viático
        const nombreViaticoElement = fila.querySelector(".nombreUniqueCargoClass");
        const nombreViatico = nombreViaticoElement ? nombreViaticoElement.value.trim() : '';

        // Obtener el valor del viático
        const valorViaticoElement = fila.querySelector(".valorTarigaCargoUniqueClass");
        const valorViatico = valorViaticoElement
            ? valorViaticoElement.value.replace(/\./g, '')
            : '';

        // Verificar si ambos valores son válidos antes de añadirlos al array
        if (nombreViatico && valorViatico) {
            const id_IdentificadorActividadesViaticos = insertId;

            datosViaticos.push({
                identificadorViaticos: id_IdentificadorActividadesViaticos,
                nombreViatico,
                valorViatico: parseInt(valorViatico)
            });

            
        }
    });

    if (datosViaticos.length === 0) {
        console.error("No se han encontrado datos válidos para insertar.");
        return;
    }

    try {
        const respuesta = await fetch("../phpServer/insertTarifaCargoActividadesIndependientes.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ datos: datosViaticos })
        });

        const resultado = await respuesta.json();
        console.log("Resultado de la inserción:", resultado);

        // Verificar si el idInsertado existe en el resultado y asignarlo al campo correspondiente
        if (resultado.idInsertado) {
            filas.forEach((fila, index) => {
                const hiddenInputId = fila.querySelector(".hidden_input_unique_tarifaCargoId");
                if (hiddenInputId) {
                    hiddenInputId.value = resultado.idInsertado; // Asignar el idInsertado al campo correspondiente
                    hiddenInputId.id = `hidden_input_unique_tarifaCargoId_${index}`; // Asignar un id dinámico si lo deseas
                }
            });
        }
    } catch (error) {
        console.error("Error al enviar los datos:", error);
    }
}