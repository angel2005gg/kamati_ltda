// Función para mostrar u ocultar la tabla de materiales eléctricos
export function toggleTableDisplay() {
    const selectMateriales = document.getElementById('select_materiales_o_noId');
    const tableMatElectricos = document.querySelector('#table_matElectricos'); // Div que contiene la tabla

    selectMateriales.addEventListener('change', function() {
        const selectedValue = selectMateriales.value;

        if (selectedValue === '1') { // Adicionar
            tableMatElectricos.style.display = 'none'; // Mostrar la tabla
        } else if (selectedValue === '2') { // No Adicionar
            tableMatElectricos.style.display = 'block'; // Ocultar la tabla
        }
    });
}
