import { recolectarDatosTurno } from './datosTurno.js';
import { recolectarDatosActividad } from './datosActividad.js';
import { enviarTurnos, enviarActividades } from './api.js';

export async function enviarDatosTurnoActividades(insertId,contenedor) {
    const filasTurnos = contenedor.querySelectorAll(".tr_new_tbody_turnounique_Class");
    const filasActividades = contenedor.querySelectorAll(".tr_clasUnique_camposActividades");

    if (filasTurnos.length === 0 || filasActividades.length === 0) {
        console.error("No se encontraron filas de turnos o actividades.");
        return;
    }

    // Recolectar los datos de los turnos
    const datosTurnos = recolectarDatosTurno(filasTurnos, 1); // Usamos un ID inicial para los turnos
    const resultTurnos = await enviarTurnos(datosTurnos);

    if (resultTurnos) {
        console.log("Turnos insertados correctamente.");

        // Asociar las actividades a los turnos
        let actividadIndex = 0;
        for (let i = 0; i < resultTurnos.length; i++) {
            // Obtener el ID del turno actual
            const turnoId = resultTurnos[i];

            // Dividir las actividades para ese turno
            const actividadesPorTurno = [];
            while (actividadIndex < filasActividades.length && actividadesPorTurno.length < filasActividades.length / resultTurnos.length) {
                actividadesPorTurno.push(filasActividades[actividadIndex]);
                actividadIndex++;
            }

            // Recolectar y enviar las actividades para este turno
            const datosActividad = recolectarDatosActividad(actividadesPorTurno, [turnoId]);
            await enviarActividades(datosActividad);

            console.log(`Actividades del turno ${turnoId} insertadas correctamente.`);
        }

        console.log("Todos los datos insertados correctamente.");
    } else {
        console.error("Error al insertar turnos:", resultTurnos.error);
    }
}