import { showDeleteModalMaquinaria } from './showDeleteMOdalMaquinaria.js';
import { updateCostoKamatiUnitarioMaquinaria } from './updateCostoKamatiUnitarioMaquinaria.js';
import { toggleSelectInputMaquinaria } from './toggleSelectInputMaquinaria.js';

export function assignDeleteEventListenersMaquinaria(deleteModal, divContainer) {
    // Asegúrate de que tableBody esté definido

    const tableBody = divContainer.querySelector('.table_maquinariaClass .tbody_maquinariaClas'); // Ajusta el selector según tu estructura de HTML

    if (!tableBody) {
        console.error('No se encontró tableBody.');
        return;
    }

    // Asignar event listeners a los botones de eliminar
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        const row = button.closest('tr');
        button.addEventListener('click', () => showDeleteModalMaquinaria(row, deleteModal, divContainer, tableBody));
    });

    const buttonPencil = document.querySelectorAll('.toggle-btn');
    buttonPencil.forEach(button => {
        button.addEventListener('click', toggleSelectInputMaquinaria);
    });

    // Asignar event listeners a los inputs relevantes dentro de la tabla
    tableBody.querySelectorAll('.cost-kamati-input, .value-total-input, .precio-lista-input, .descuento-input, .descuento-adicional-input, .cantidad-material, .abreviatura-lista, .select_trm_nu, .date_input_entrega, .inputNewFactorMaquinariaClas, .inputHiddenNewFactorMaquinariaClas, .hiddenInputFactoresIndependientesClasMaquinaria').forEach(input => {
        input.addEventListener('input', () => {
            const row = input.closest('tr');
            updateCostoKamatiUnitarioMaquinaria(row, divContainer, tableBody);
        });
    });

    // Asignar event listeners a los inputs fuera de la tabla
    const names = ['txt_FactorMoGlobal', 'txt_FactorOGlobal', 'txt_PolizaGlobal',
         'txt_siemensGlobal', 'txt_pilzGlobal', 'txt_rittalGlobal', 
         'txt_phoenixGlobal', 'txt_identificacion_usd1', 'txt_identificacion_eur1'];
    names.forEach(name => {
        const inputs = document.getElementsByName(name);
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                // Llamar a updateCostoKamatiUnitario para cada fila si es necesario actualizar todos los cálculos
                const rows = document.querySelectorAll('table tbody tr');
                rows.forEach(row => updateCostoKamatiUnitarioMaquinaria(row, divContainer, tableBody));
            });
        });
    });

    // Arreglo con los nombres de los inputs
    const namesIndependientes = [
        'txt_factor_mo1', 'txt_factor_o1',
        'txt_poliza1', 'txt_siemens1', 'txt_pilz1', 'txt_rittal1',
        'txt_phoenix1'
    ];

    // Iterar sobre cada nombre en el arreglo
    namesIndependientes.forEach(name => {
        // Buscar todos los inputs con el nombre especificado dentro del `divContainer`
        const inputs = divContainer.querySelectorAll(`input[name="${name}"]`);

        // Añadir un event listener a cada input encontrado
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                // Obtener todas las filas de la tabla dentro del contenedor
                const rows = divContainer.querySelectorAll('table tbody tr');

                // Ejecutar `updateCostoKamatiUnitario` para cada fila si es necesario actualizar todos los cálculos
                rows.forEach(row => updateCostoKamatiUnitarioMaquinaria(row, divContainer, tableBody));
            });
        });
    });
}