export function checkJornada(tableBody, startTimeValue, endTimeValue) {
    const row = tableBody.querySelector('tr');
    const horaInputHiddenInicio = document.querySelector('#JornadaDiurnaInicioId').value;
    const horaInputHiddenFin = document.querySelector('#JornadaDiurnaFinId').value;
    const nocturnaStartHidden = document.querySelector('#JornadaNocturnaInicioId').value;
    const nocturnaEndHidden = document.querySelector('#JornadaNocturnaFinId').value;
    const [startHoursInicio, startMinutesInicio] = horaInputHiddenInicio.split(':').map(Number);
    const [startHours, startMinutes] = startTimeValue.split(':').map(Number);
    const [endHoursFin, endMinutesFin] = horaInputHiddenFin.split(':').map(Number);
    const [endHours, endMinutes] = endTimeValue.split(':').map(Number);
    const [nocturnaStartHours, nocturnaStartMinutes] = nocturnaStartHidden.split(':').map(Number);
    const [nocturnaEndHours, nocturnaEndMinutes] = nocturnaEndHidden.split(':').map(Number);
    const startTime = new Date();
    startTime.setHours(startHours, startMinutes, 0, 0);
    const endTime = new Date();
    endTime.setHours(endHours, endMinutes, 0, 0);
    if (endTime < startTime) {
        endTime.setDate(endTime.getDate() + 1); // Maneja horarios que cruzan la medianoche
    }
    const diurnaStart = new Date();
    diurnaStart.setHours(startHoursInicio, startMinutesInicio, 0, 0);
    const diurnaEnd = new Date();
    diurnaEnd.setHours(endHoursFin, endMinutesFin, 0, 0);
    if (diurnaEnd < diurnaStart) {
        diurnaEnd.setDate(diurnaEnd.getDate() + 1); // Maneja jornadas diurnas que cruzan la medianoche
    }
    const nocturnaStart = new Date();
    nocturnaStart.setHours(nocturnaStartHours, nocturnaStartMinutes, 0, 0);
    const nocturnaEnd = new Date();
    nocturnaEnd.setHours(nocturnaEndHours, nocturnaEndMinutes, 0, 0);
    if (nocturnaEnd < nocturnaStart) {
        nocturnaEnd.setDate(nocturnaEnd.getDate() + 1); // Maneja jornadas nocturnas que cruzan la medianoche
    }
    let jornada = '';
    let horasDiurnas = 0;
    let horasNocturnas = 0;
    // Cálculo de horas diurnas y nocturnas
    if (startTime < diurnaEnd && endTime <= diurnaEnd) {
        jornada = 'JornadaDiurna';
        horasDiurnas = (endTime - startTime) / (1000 * 60 * 60);
    } else if (startTime < diurnaEnd && endTime > diurnaEnd) {
        jornada = 'JornadaMixta';
        horasDiurnas = (diurnaEnd - startTime) / (1000 * 60 * 60);
        const startOfNocturna = Math.max(diurnaEnd, startTime);
        if (endTime > startOfNocturna) {
            horasNocturnas = (endTime - startOfNocturna) / (1000 * 60 * 60);
        }
    } else if (startTime >= nocturnaStart && endTime <= nocturnaEnd) {
        jornada = 'JornadaNocturna';
        horasNocturnas = (endTime - startTime) / (1000 * 60 * 60);
    } else if (startTime >= nocturnaStart && endTime > nocturnaEnd) {
        jornada = 'JornadaMixta';
        horasNocturnas = (nocturnaEnd - startTime) / (1000 * 60 * 60);
        const startOfDiurna = Math.max(nocturnaEnd, startTime);
        if (endTime > startOfDiurna) {
            horasDiurnas = (endTime - startOfDiurna) / (1000 * 60 * 60);
        }
    } else if (startTime < nocturnaStart && endTime > nocturnaStart && endTime <= nocturnaEnd) {
        jornada = 'JornadaMixta';
        horasNocturnas = (endTime - nocturnaStart) / (1000 * 60 * 60);
        if (nocturnaStart > startTime) {
            horasDiurnas = (nocturnaStart - startTime) / (1000 * 60 * 60);
        }
    } else if (startTime < nocturnaStart && endTime > nocturnaEnd) {
        jornada = 'JornadaMixta';
        horasNocturnas = (nocturnaEnd - nocturnaStart) / (1000 * 60 * 60);
        horasDiurnas = (endTime - nocturnaEnd) / (1000 * 60 * 60);
    }
    row.querySelector('#startTime').addEventListener('input', checkJornada);
    row.querySelector('#endTime').addEventListener('input', checkJornada);
    console.log(`Jornada: ${jornada}`);
    console.log(`Horas diurnas: ${horasDiurnas}`);
    console.log(`Horas nocturnas: ${horasNocturnas}`);
    return { jornada, horasDiurnas, horasNocturnas };
}