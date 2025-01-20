export function horasCalculadasDobleInput(noMoverRow) {
    const horaInicioNoMover = noMoverRow.querySelector('input[name="hora_inicio_turno"]').value;
    const horaFinNoMover = noMoverRow.querySelector('input[name="hora_fin_turno"]').value;
    const [horaInicioHoras, horaInicioMinutos] = horaInicioNoMover.split(':').map(Number);
    const [horaFinHoras, horaFinMinutos] = horaFinNoMover.split(':').map(Number);

    let totalHoras = horaFinHoras - horaInicioHoras;
    let totalMinutos = horaFinMinutos - horaInicioMinutos;

    // Si los minutos son negativos, ajustamos las horas y minutos
    if (totalMinutos < 0) {
        totalMinutos += 60;
        totalHoras -= 1;
    }

    // Si las horas son negativas, significa que cruzó la medianoche
    if (totalHoras < 0) {
        totalHoras += 24;
    }

    let totalTiempos = totalHoras + (totalMinutos / 60);

    return totalTiempos;
}