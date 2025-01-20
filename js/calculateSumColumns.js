// Función para eliminar los puntos de miles y convertir el valor a número
function cleanNumber(value) {
    if (!value) return 0;
    return parseFloat(value.replace(/\./g, '').replace(/,/g, '.')); // Quitar puntos y cambiar comas por puntos (si es necesario)
}

// Función para formatear un número con puntos de miles
function formatNumber(value) {
    return value.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'); // Agregar puntos cada 3 dígitos
}

// Función para calcular la suma de los valores en las columnas
function calculateSumColumns(table, divContainerActividades) {
    let totalKamati = 0;
    let totalCliente = 0;

    // Iterar sobre las filas de la tabla
    const rows = table.querySelectorAll('tbody tr');

    rows.forEach(row => {
        // Verificar si la fila tiene la clase 'no-mover' y omitirla en los cálculos
        if (row.classList.contains('no-mover')) {
            return; // Saltar esta iteración si la fila es 'no-mover'
        }

        const kamatiInput = row.querySelector('.valorDiasKamatiClass');
        const clienteInput = row.querySelector('.valorDiasClienteUtilidadClass');

        // Verificar si los inputs existen y limpiar los valores para sumarlos
        if (kamatiInput && !isNaN(cleanNumber(kamatiInput.value))) {
            totalKamati += cleanNumber(kamatiInput.value);
        }

        if (clienteInput && !isNaN(cleanNumber(clienteInput.value))) {
            totalCliente += cleanNumber(clienteInput.value);
        }
    });

    // Formatear los totales con puntos de miles y colocarlos en los inputs correspondientes fuera de la tabla
    divContainerActividades.querySelector('.txt_total_kamatiActividadesClass').value = formatNumber(totalKamati);
    divContainerActividades.querySelector('.txt_total_clienteActividadesClass').value = formatNumber(totalCliente);
}

// Función para agregar event listeners a los textarea
function addEventListenersToInputs(table, divContainerActividades) {

    const kamatiInputs = table.querySelectorAll('.valorDiasKamatiClass');
    const clienteInputs = table.querySelectorAll('.valorDiasClienteUtilidadClass');

    kamatiInputs.forEach(input => {
        input.addEventListener('input', () => calculateSumColumns(table, divContainerActividades));
        input.addEventListener('change', () => calculateSumColumns(table, divContainerActividades));
    });

    clienteInputs.forEach(input => {
        input.addEventListener('input', () => calculateSumColumns(table, divContainerActividades));
        input.addEventListener('change', () => calculateSumColumns(table, divContainerActividades));
    });

    // Llamar la función para calcular la suma de columnas inmediatamente después de que se añadan los event listeners
    calculateSumColumns(table, divContainerActividades);
}

// Llama a addEventListenersToInputs después de cargar o clonar filas
export function initializeTable(table, divContainerActividades) {
    addEventListenersToInputs(table, divContainerActividades);

    // Ejecutar la función inmediatamente para asegurar que los valores iniciales se sumen
    calculateSumColumns(table, divContainerActividades);
}