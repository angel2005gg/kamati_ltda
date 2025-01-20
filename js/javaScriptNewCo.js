document.addEventListener('DOMContentLoaded', function () {
    // Obtener referencia al select y los checkboxes
    var selectRol = document.querySelector('#txt_rolUsuario');
    var checkboxes = document.querySelectorAll('.form-check-input');

    // Función para activar o desactivar los checkboxes según la opción seleccionada en el select
    function toggleCheckboxes() {
        // Obtener valor seleccionado en el select
        var selectedValue = selectRol.value;

        // Desactivar todos los checkboxes
        checkboxes.forEach(function (checkbox) {
            checkbox.disabled = true;
            checkbox.checked = false;
        });

        // Activar los checkboxes si la opción seleccionada es 'Jefe inmediato' 
        if (selectedValue === '2'){
            checkboxes.forEach(function (checkbox) {
                checkbox.disabled = false;
            });
        }
    }

    // Agregar un listener para el evento change del select
    selectRol.addEventListener('change', toggleCheckboxes);

    // Llamar a toggleCheckboxes inicialmente para establecer el estado inicial de los checkboxes
    toggleCheckboxes();
});