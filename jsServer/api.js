export async function enviarTurnos(datosTurnos) {
    try {
        console.log("Enviando datos de turnos:", datosTurnos);

        const response = await fetch("../phpServer/insertTurnoActividade.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datosTurnos)
        });

        const result = await response.json();
        console.log("Respuesta del servidor (turnos):", result);

        if (result.success) {
             // Devuelve los IDs insertados
            return result.insertIds;
        } else {
            console.error("Error al insertar turnos:", result.error);
            throw new Error(result.error || "Error desconocido en la inserción de turnos");
        }
    } catch (error) {
        console.error("Error en enviarTurnos:", error);
        throw error; // Re-lanza el error para que se maneje más arriba si es necesario
    }
}

// Función para enviar los datos de las actividades
export async function enviarActividades(datosActividad) {
    try {
        console.log("Enviando datos de actividades:", datosActividad);

        const response = await fetch("../phpServer/insertTableActividades.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(datosActividad)
        });

        const result = await response.json();
        console.log("Respuesta del servidor (actividades):", result);

        if (!result.success) {
            console.error("Error al insertar actividades:", result.error);
            throw new Error(result.error || "Error desconocido en la inserción de actividades");
        }
    } catch (error) {
        console.error("Error en enviarActividades:", error);
        throw error; // Re-lanza el error si es necesario
    }
}