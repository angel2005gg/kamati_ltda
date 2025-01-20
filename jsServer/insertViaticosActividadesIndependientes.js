export async function enviarDatosViaticosActividadesIndependientes(insertId, clonedTableContainer) {
    const filas = clonedTableContainer.querySelectorAll(".tableViaticosHidden-ClassActividades tr");

    const datosViaticos = [];

    filas.forEach((fila, index) => {
        // Obtener el valor del nombre del viático
        const nombreViaticoElement = fila.querySelector(".nombreViaticoActividadesUnique");
        const nombreViatico = nombreViaticoElement ? nombreViaticoElement.value.trim() : '';

        // Obtener el valor del viático
        const valorViaticoElement = fila.querySelector(".valorActividadesViaticosUnique");
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
        const respuesta = await fetch("../phpServer/insertViaticosActividadesIndependientes.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ datos: datosViaticos })
        });

        const resultado = await respuesta.json();
        console.log("Resultado de la inserción:", resultado);
    } catch (error) {
        console.error("Error al enviar los datos:", error);
    }
}