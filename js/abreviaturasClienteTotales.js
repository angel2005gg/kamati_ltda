// Función para calcular los porcentajes de la tabla
function calcularPorcentajesCliente() {

    // array con todos los IDs de los campos
    const ids = [
        "Auto", "Com", "Ce", "Var", "Soft", "Rep", "Lp", "O",
        "Pilz", "Pc", "R", "V", "Mo", "Sup", "Ing", "Pm", "Siso"
    ];

    // Iterar sobre los IDs para calcular los porcentajes
    ids.forEach(id => {
        const kamatiElement = document.querySelector(`#id_${id}KamatiValor`);
        const clienteElement = document.querySelector(`#id_${id}ClienteValor`);
        const porcentajeField = document.querySelector(`#id_${id}ClientePorcentaje`);

        // Verificar si ambos campos existen en el DOM antes de procesar
        if (kamatiElement && clienteElement) {
            // Limpiar valores de entrada: eliminar separadores de miles y convertir a números
            const kamatiValor = parseFloat(kamatiElement.value.replace(/\./g, '')) || 0;
            const clienteValor = parseFloat(clienteElement.value.replace(/\./g, '')) || 0;

            if (kamatiValor > 0) {
                // Calcular el porcentaje
                const resultado = ((clienteValor / kamatiValor) - 1) * 100;

                // Redondear el resultado
                let numeroConvertido = Math.round(resultado);

                // Si hay un campo para mostrar el porcentaje, actualizar su valor
                if (porcentajeField) {
                    porcentajeField.value = `${numeroConvertido} %`;
                }
            } else {
                console.warn(`El valor de Kamati para el ID ${id} no es válido.`);
            }
        } else {
            console.warn(`No se encontró un elemento para el ID ${id}`);
        }
    });
}
// Función auxiliar para formatear los valores
function formatearNumeroCliente(valor) {
    return valor.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

// Función para convertir el valor de un input a número
function convertirValorInputCliente(input) {
    // Convertir el valor del input en un número y redondear hacia abajo
    return Math.floor(parseFloat(
        input.value
            .replace(/[$]/g, '') // Eliminar el símbolo de pesos
            .replace(/\./g, '')  // Eliminar los puntos (miles)
            .replace(',', '.')    // Cambiar la coma por un punto para que sea un número decimal
    ) || 0); // Si el valor es NaN, usar 0
}
function eliminarDecimales(valor) {
    // Convertir el valor a string si no lo es
    let valorString = String(valor);

    // Reemplazar las comas por puntos para uniformidad en el procesamiento
    valorString = valorString.replace(',', '.');

    // Si hay un punto, eliminar todo lo que esté a la derecha del punto
    if (valorString.includes('.')) {
        valorString = valorString.split('.')[0];
    }

    // Devolver el valor convertido de nuevo a número
    return parseInt(valorString, 10); // Usar base 10 para seguridad
}
// Función para actualizar el total en el input externo
function actualizarTotalCliente() {
    const filaValores = document.querySelector('#filaValores-cliente');
    const inputs = filaValores.querySelectorAll('input.tamano_abreviaturasInput');
    let total = 0;

    inputs.forEach(input => {
        const numero = convertirValorInputCliente(input);
        console.log(numero);
        total += numero; // Sumar los valores convertidos
    });

    // Actualizar el input externo con el total formateado
    const inputTotal = document.querySelector('#txtIdTotalClienteLineasAb');
    inputTotal.value = formatearNumeroCliente(total);
}

// Función para actualizar el valor en la tabla 'abreviaturas-kamatiId'
export function actualizarValoresCliente() {
    const table = document.querySelector('#abreviaturas-ClienteId #tableBodyAbreviaturas-Cliente');

    // Arreglo de campos
    const campos = [
        table.querySelector('#id_AutoClienteValor'),
        table.querySelector('#id_ComClienteValor'),
        table.querySelector('#id_CeClienteValor'),
        table.querySelector('#id_VarClienteValor'),
        table.querySelector('#id_SoftClienteValor'),
        table.querySelector('#id_RepClienteValor'),
        table.querySelector('#id_LpClienteValor'),
        table.querySelector('#id_OClienteValor'),
        table.querySelector('#id_PilzClienteValor'),
        table.querySelector('#id_PcClienteValor'),
        table.querySelector('#id_RClienteValor'),
        table.querySelector('#id_VClienteValor'),
        table.querySelector('#id_MoClienteValor'),
        table.querySelector('#id_SupClienteValor'),
        table.querySelector('#id_IngClienteValor'),
        table.querySelector('#id_PmClienteValor'),
        table.querySelector('#id_SisoClienteValor')
    ];

    // Arreglo para los totales
    const valoresTotales = Array(17).fill(0);

    const divsClonados = document.querySelectorAll('.tablaIdentificadorCloned');

    divsClonados.forEach(div => {
        const filasClonadas = div.querySelectorAll('.clonedValoresKamatiFila');

        filasClonadas.forEach(fila => {
            const selectAbreviations = fila.querySelector('.clonedValoresKamatiAbreviations');
            const inputKamati = fila.querySelector('.clonedValoresCliente');
            const poliza = parseFloat(div.querySelector('.polizaCliente')?.value) || 1; // Convierte poliza a número, valor por defecto 1
            const polizaGlobal = parseFloat(document.querySelector('#polizaGlobal')?.value) || 1; // Valor por defecto 1

            // Validar que selectAbreviations y inputKamati existan
            if (!selectAbreviations || !inputKamati) {
                console.warn('No se encontró select o input en una fila.');
                return;
            }

            let finalValor = 0;
            let selectAb = parseInt(selectAbreviations.value) || 0; // Convertir a número, valor por defecto 0
            let inputKamatiTotal = convertirValorInputCliente(inputKamati) || 0;
            console.log(inputKamatiTotal); // Asegurarse de que la función está definida y regresa un valor

            const check = div.querySelector('.checkClienteClass');
            let finalValor1 = 0;
            // Calcular el valor inicialmente (sin esperar al cambio del checkbox)
            if (check && check.checked) {
                finalValor = inputKamatiTotal / poliza;
                finalValor1 = eliminarDecimales(String(finalValor));
                console.log(finalValor); // Si está marcado, usa poliza
                console.log(finalValor1); // Si está marcado, usa poliza
            } else {
                finalValor = inputKamatiTotal / polizaGlobal; // Si no está marcado, usa polizaGlobal
                finalValor1 = eliminarDecimales(String(finalValor));
            }

            // Solo si el selectAb está en el rango válido (1 a 17)
            if (selectAb >= 1 && selectAb <= 17) {
                valoresTotales[selectAb - 1] += finalValor1;
                campos[selectAb - 1].value = formatearNumeroCliente(valoresTotales[selectAb - 1]);
            }

            // Lógica para Alimentación y Transporte
            const clonedAlimentacion = fila.querySelector('.clonedAlimentacionClassUniqueUtilidad');
            const clonedTransporte = fila.querySelector('.clonedTransporteClassUniqueUtilidad');

            if (clonedAlimentacion && clonedTransporte) {
                const clonedAlimentacionHidden = fila.querySelector('.clonedAlimentacionClassUniqueHiddenn');
                const clonedTransporteHidden = fila.querySelector('.clonedTransporteClassUniqueHiddenn');

                const valorAlimentacion = convertirValorInputCliente(clonedAlimentacion) || 0;
                const valorTransporte = convertirValorInputCliente(clonedTransporte) || 0;

                // Acumula los valores de Alimentación y Transporte si las condiciones se cumplen
                if (clonedAlimentacionHidden && clonedAlimentacionHidden.value === '1') {
                    valoresTotales[11] += valorAlimentacion; // Acumula en el índice correspondiente
                }
                if (clonedTransporteHidden && clonedTransporteHidden.value === '1') {
                    valoresTotales[11] += valorTransporte; // Acumula en el índice correspondiente
                }

                // Formatear y actualizar el campo de Totales en Alimentación y Transporte
                campos[11].value = formatearNumeroCliente(valoresTotales[11]);
            }

            // Listener para cambios en el checkbox
            if (check) {
                check.addEventListener('change', (event) => {
                    inputKamatiTotal = convertirValorInputCliente(inputKamati);
                    console.log(inputKamatiTotal); // Volver a calcular el input
                    if (event.target.checked) {
                        finalValor = inputKamatiTotal / poliza;
                        finalValor1 = eliminarDecimales(String(finalValor));
                        console.log('Checkbox está marcado');
                    } else {
                        finalValor = inputKamatiTotal / polizaGlobal; // Si no está marcado, usa polizaGlobal
                        finalValor1 = eliminarDecimales(String(finalValor));
                        console.log('Checkbox no está marcado');
                    }
                    // Actualizar valores y campos solo si el selectAb es válido
                    if (selectAb >= 1 && selectAb <= 17) {
                        valoresTotales[selectAb - 1] += finalValor1;
                        campos[selectAb - 1].value = formatearNumeroCliente(valoresTotales[selectAb - 1]);
                    }
                });
            }
        });
    });

    calcularPorcentajesCliente();
    // Llamar a las funciones para actualizar el total en el input externo
    actualizarTotalCliente();
}
// Delegación de eventos para elementos dinámicos
export function iniciarListenersCliente() {
    document.addEventListener('input', function (event) {
        if (event.target && event.target.classList.contains('clonedValoresCliente')) {
            actualizarValoresCliente();
        }
    });
    document.addEventListener('change', function (event) {
        if (event.target && event.target.classList.contains('clonedValoresKamatiAbreviations')) {
            actualizarValoresCliente();
        }
    });
    // Variables para almacenar los valores previos
    const previousValues = new Map();

    // Comprobar cambios en los textarea cada 500 ms
    setInterval(() => {
        const textAreas = document.querySelectorAll('.clonedValoresCliente');
        textAreas.forEach(textArea => {
            const currentValue = textArea.value;
            // Comparar con el valor anterior
            if (previousValues.get(textArea) !== currentValue) {
                previousValues.set(textArea, currentValue); // Actualizar el valor previo
                actualizarValoresCliente(); // Llamar a actualizarValores si hay un cambio
            }
        });
    }, 500);

    // Llamada inicial al cargar el DOM
    document.addEventListener('DOMContentLoaded', function () {
        actualizarValoresCliente();
    });
}