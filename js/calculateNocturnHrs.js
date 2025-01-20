export function calcularHorasNocturnas(tableBody) {
    const row = tableBody.querySelector('tr');
    const startTimeValue = row.querySelector('#startTime').value;
    const endTimeValue = row.querySelector('#endTime').value;
    // Convertimos las horas a minutos desde el inicio del día
    const [inicioHoras, inicioMinutos] = startTimeValue.split(':').map(Number);
    const [finHoras, finMinutos] = endTimeValue.split(':').map(Number);
    const minutosInicio = (inicioHoras * 60) + inicioMinutos;
    const minutosFin = (finHoras * 60) + finMinutos;
    // Total de minutos en un día
    const minutosEnUnDia = 24 * 60;
    let horasAntesDeMedianoche = 0;
    let minutosAntesDeMedianoche = 0;
    let horasDespuesDeMedianoche = 0;
    let minutosDespuesDeMedianoche = 0;

    // Calculamos si la jornada pasa la medianoche
    if (minutosFin < minutosInicio) {
        // Jornada pasa de la medianoche
        const minutosAntesDeMedianoche = minutosEnUnDia - minutosInicio; // Minutos antes de las 00:00
        const minutosDespuesDeMedianoche = minutosFin; // Minutos después de las 00:00
        horasAntesDeMedianoche = Math.floor(minutosAntesDeMedianoche / 60);
        horasDespuesDeMedianoche = Math.floor(minutosDespuesDeMedianoche / 60);
    } else {
        // Jornada no cruza la medianoche
        const minutosTotales = minutosFin - minutosInicio;
        horasAntesDeMedianoche = Math.floor(minutosTotales / 60);
        minutosAntesDeMedianoche = minutosTotales % 60;
        // No hay horas después de la medianoche
        horasDespuesDeMedianoche = 0;
        minutosDespuesDeMedianoche = 0;
    }
    // Mostrar resultados en la consola
    console.log(`Horas antes de la medianoche: ${horasAntesDeMedianoche}`);
    console.log(`Horas después de la medianoche: ${horasDespuesDeMedianoche}`);
    // Actualizar cuando cambien los inputs
    row.querySelector('#startTime').addEventListener('input', calcularHorasNocturnas);
    row.querySelector('#endTime').addEventListener('input', calcularHorasNocturnas);
    // Devolver los resultados como un objeto
    return {
        horasAntesDeMedianoche: horasAntesDeMedianoche,
        minutosAntesDeMedianoche: minutosAntesDeMedianoche,
        horasDespuesDeMedianoche: horasDespuesDeMedianoche,
        minutosDespuesDeMedianoche: minutosDespuesDeMedianoche
    };
}