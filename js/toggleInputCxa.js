export function setupToggleButton(container) {
    if (container) {
        container.querySelectorAll('.toggle-btn_new').forEach(button => {
            button.addEventListener('click', function() {
                const containers = this.closest('.select-input-container_new');
                const input = containers.querySelector('.option-input_Ac');
                const select = containers.querySelector('.select_hidden');
                const cambio = containers.querySelector('#hidden_input_cargosDesc');
                const textBloc = container.querySelector('#textAreaExternoCosto');

                // Alterna la visibilidad del input
                if (input.classList.contains('hidden')) {
                    textBloc.disabled = false;
                    textBloc.value = '';
                    cambio.value = 'true'; 
                    input.classList.remove('hidden');
                    input.focus();
                    select.classList.add('hidden'); // Opcional: Oculta el select si deseas mostrar solo el input
                } else {
                    textBloc.disabled = true;
                    textBloc.value = '';
                    cambio.value = 'false'; 
                    input.classList.add('hidden');
                    select.classList.remove('hidden'); // Opcional: Muestra el select nuevamente
                }
            });
        });
    } else {
        console.error('El contenedor proporcionado no es válido.');
    }
}