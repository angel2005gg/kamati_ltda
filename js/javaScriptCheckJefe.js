document.addEventListener('DOMContentLoaded', function () {
    // Obtener referencia al select y los checkboxes
    var selectRol = document.querySelector('#select_TipoRolUsuarioID');
    var checkboxes = document.querySelectorAll('.form-check-input');

    // Variable para almacenar el valor anterior del select
    var previousValue = selectRol.value;

    // Función para activar o desactivar los checkboxes según la opción seleccionada en el select
    function toggleCheckboxes() {
        // Obtener valor seleccionado en el select
        var selectedValue = selectRol.value;

        // Desactivar todos los checkboxes que no tengan la clase 'no-checkBoxselect'
        checkboxes.forEach(function (checkbox) {
            if (!checkbox.classList.contains('no-checkBoxselect')) {
                // Solo desmarcar si el valor seleccionado no es '2'
                if (selectedValue !== '2') {
                    checkbox.checked = false; // Desmarcar checkbox si no es '2'
                }
                checkbox.disabled = (selectedValue !== '2'); // Deshabilitar si no es '2'
            }
        });

        // Si el valor seleccionado es '2', habilitar los checkboxes y mantener los marcados
        if (selectedValue === '2') {
            checkboxes.forEach(function (checkbox) {
                if (!checkbox.classList.contains('no-checkBoxselect')) {
                    checkbox.disabled = false; // Habilitar checkbox
                }
            });
        }
    }

    // Agregar un listener para el evento change del select
    selectRol.addEventListener('change', toggleCheckboxes);

    // Llamar a toggleCheckboxes inicialmente para establecer el estado inicial de los checkboxes
    toggleCheckboxes();

    // Configurar un intervalo para verificar cambios en el valor del select cada 500 ms
    setInterval(function() {
        // Si el valor actual es diferente al valor anterior, actualizar
        if (selectRol.value !== previousValue) {
            previousValue = selectRol.value; // Actualizar el valor anterior
            toggleCheckboxes(); // Llamar a la función para actualizar los checkboxes
        }
    }, 500); // 500 milisegundos
});