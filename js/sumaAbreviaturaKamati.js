// Función para sumar los valores de la fila 'filaValores-kamati' y actualizar el campo total
function actualizarTotalKamati() {
    const filaValores = document.querySelector('#filaValores-kamati');
    const inputs = filaValores.querySelectorAll('input');
    let total = 0;

    inputs.forEach(input => {
        const valor = convertirValorInput(input);
        total += valor; // Sumar el valor convertido
    });

    // Actualizar el campo total formateando el número
    const campoTotal = document.querySelector('#txtIdTotalKamatiLineasAb');
    campoTotal.value = formatearNumero(total); // Formatear y asignar al campo
}

// Modificar la función 'actualizarValores' para incluir la llamada a 'actualizarTotalKamati'
export function actualizarValores() {
    const table = document.querySelector('#abreviaturas-kamatiId #tableBodyAbreviaturas-Kamati');

    // Arreglo de campos
    const campos = [
        table.querySelector('#id_AutoKamatiValor'),
        table.querySelector('#id_ComKamatiValor'),
        table.querySelector('#id_CeKamatiValor'),
        table.querySelector('#id_VarKamatiValor'),
        table.querySelector('#id_SoftKamatiValor'),
        table.querySelector('#id_RepKamatiValor'),
        table.querySelector('#id_LpKamatiValor'),
        table.querySelector('#id_OKamatiValor'),
        table.querySelector('#id_PilzKamatiValor'),
        table.querySelector('#id_PcKamatiValor'),
        table.querySelector('#id_RKamatiValor'),
        table.querySelector('#id_VKamatiValor'),
        table.querySelector('#id_MoKamatiValor'),
        table.querySelector('#id_SupKamatiValor'),
        table.querySelector('#id_IngKamatiValor'),
        table.querySelector('#id_PmKamatiValor'),
        table.querySelector('#id_SisoKamatiValor')
    ];

    // Arreglo para los totales
    const valoresTotales = Array(17).fill(0);

    const divsClonados = document.querySelectorAll('.tablaIdentificadorCloned');

    divsClonados.forEach(div => {
        const filasClonadas = div.querySelectorAll('.clonedValoresKamatiFila');

        filasClonadas.forEach(fila => {
            const selectAbreviations = fila.querySelector('.clonedValoresKamatiAbreviations');
            const inputKamati = fila.querySelector('.clonedValoresKamati');

            const selectAb = parseInt(selectAbreviations.value);
            const inputKamatiTotal = convertirValorInput(inputKamati);

            // Solo si el selectAb está en el rango válido (1 a 17)
            if (selectAb >= 1 && selectAb <= 17) {
                valoresTotales[selectAb - 1] += inputKamatiTotal;
                campos[selectAb - 1].value = formatearNumero(valoresTotales[selectAb - 1]);
            }
        });
    });

    // Llamar a la función que actualiza el total de Kamati
    actualizarTotalKamati();
}

// Delegación de eventos para elementos dinámicos
export function iniciarListeners() {
    document.addEventListener('input', function (event) {
        if (event.target && event.target.classList.contains('clonedValoresKamati')) {
            actualizarValores();
        }
    });

    document.addEventListener('change', function (event) {
        if (event.target && event.target.classList.contains('clonedValoresKamatiAbreviations')) {
            actualizarValores();
        }
    });

    // Variables para almacenar los valores previos
    const previousValues = new Map();

    // Comprobar cambios en los textarea cada 500 ms
    setInterval(() => {
        const textAreas = document.querySelectorAll('.clonedValoresKamati');
        textAreas.forEach(textArea => {
            const currentValue = textArea.value;

            // Comparar con el valor anterior
            if (previousValues.get(textArea) !== currentValue) {
                previousValues.set(textArea, currentValue); // Actualizar el valor previo
                actualizarValores(); // Llamar a actualizarValores si hay un cambio
            }
        });
    }, 500);

    // Llamada inicial al cargar el DOM
    document.addEventListener('DOMContentLoaded', function () {
        actualizarValores();
    });
}