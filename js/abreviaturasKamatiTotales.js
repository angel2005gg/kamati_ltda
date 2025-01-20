// Función auxiliar para formatear los valores
function formatearNumero(valor) {
    return valor.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Función para convertir el valor de un input a número
function convertirValorInput(input) {
    return Math.floor(parseFloat(
        input.value
            .replace(/[$]/g, '') // Eliminar el símbolo de pesos
            .replace(/\./g, '')  // Eliminar los puntos (miles)
            .replace(',', '.')    // Cambiar la coma por un punto para que sea un número decimal
    ) || 0); // Si el valor es NaN, usar 0
}
// Función para actualizar el total en el input externo
function actualizarTotalKamati() {
    const filaValores = document.querySelector('#filaValores-kamati');
    const inputs = filaValores.querySelectorAll('input.tamano_abreviaturasInput');
    let total = 0;

    inputs.forEach(input => {
        const numero = convertirValorInput(input);
        total += numero; // Sumar los valores convertidos
    });

    // Actualizar el input externo con el total formateado
    const inputTotal = document.querySelector('#txtIdTotalKamatiLineasAb');
    inputTotal.value = formatearNumero(total);
}

// Función para actualizar el valor en la tabla 'abreviaturas-kamatiId'
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
    let totalTransporteSinUtilidad = 0;
    let totalAlimentacionSinUtilidad = 0;

    // Inicializa una variable para acumular el total global
    let totalGlobalFinal = 0;

    // Bandera para asegurarse de que la suma en el campo 11 se realice solo una vez
    let sumaRealizadaEnCampo11 = false;

    divsClonados.forEach(div => {
        console.log(campos[11]);

        const filasClonadas = div.querySelectorAll('.clonedValoresKamatiFila');

        // Inicializa las variables de suma para este div
        let totalAlimentacionSinUtilidad = 0;
        let totalTransporteSinUtilidad = 0;
        if (filasClonadas.length > 0) {
            filasClonadas.forEach(fila => {

                const selectAbreviations = fila.querySelector('.clonedValoresKamatiAbreviations');
                const inputKamati = fila.querySelector('.clonedValoresKamati');
                const clonedAlimentacionHidden = fila.querySelector('.clonedAlimentacionClassUniqueHiddenn');
                const clonedTransporteHidden = fila.querySelector('.clonedTransporteClassUniqueHiddenn');
                const clonedAlimentacion = fila.querySelector('.clonedAlimentacionClassUnique');
                const clonedTransporte = fila.querySelector('.clonedTransporteClassUnique');

                // Verifica y convierte valores
                const valorAlimentacion = clonedAlimentacion ? convertirValorInput(clonedAlimentacion) : 0;
                const valorTransporte = clonedTransporte ? convertirValorInput(clonedTransporte) : 0;

                // Logs para depuración
                console.log(`Fila procesada:`, fila);
                console.log(`Valor Alimentación: ${valorAlimentacion}`);
                console.log(`Valor Transporte: ${valorTransporte}`);

                // Procesar sumas de alimentación y transporte si las condiciones lo permiten
                if (clonedAlimentacionHidden?.value === '1') {
                    totalAlimentacionSinUtilidad += valorAlimentacion;
                }
                if (clonedTransporteHidden?.value === '1') {
                    totalTransporteSinUtilidad += valorTransporte;
                }

                // Sumar valores de Kamati si el select está en el rango válido
                const selectAb = parseInt(selectAbreviations.value, 10);
                if (selectAb >= 1 && selectAb <= 17) {
                    const inputKamatiTotal = convertirValorInput(inputKamati);
                    valoresTotales[selectAb - 1] += inputKamatiTotal;
                    campos[selectAb - 1].value = formatearNumero(valoresTotales[selectAb - 1]);
                }

               
                    valoresTotales[11] += valorAlimentacion + valorTransporte;
                    campos[11].value = formatearNumero(valoresTotales[11]); // Actualiza el campo 11 solo una vez
                    sumaRealizadaEnCampo11 = true; // Marca que ya se realizó la suma
                
            });
        }

        // Logs para verificar las sumas
        console.log(`Total Alimentación Sin Utilidad: ${totalAlimentacionSinUtilidad}`);
        console.log(`Total Transporte Sin Utilidad: ${totalTransporteSinUtilidad}`);

        // Calcula el total final para este div y acumúlalo en totalGlobalFinal
        const totalFinal = totalAlimentacionSinUtilidad + totalTransporteSinUtilidad;
        totalGlobalFinal += totalFinal;

        // Logs para verificar el total final
        console.log(`Total Final para este div: ${totalFinal}`);
    });

   
 
    // Calcular el total final

    // Llamar a la función para actualizar el total en el input externo
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