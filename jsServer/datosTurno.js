export function recolectarDatosTurno(filas, insertId) {
    const datosTurnos = [];
    for (let fila of filas) {
        let horaInicioTurno = fila.querySelector(".starTimeClassActividades").value || "00:00:00";
        let horaFinTurno = fila.querySelector(".endTimeClassActividades").value || "00:00:00";
        const tipoTurno = fila.querySelector(".tipoDia-classActividades").value || "";
        datosTurnos.push({ idIdentificador: insertId, horaInicioTurno, horaFinTurno, tipoTurno });
    }
    return datosTurnos;
}