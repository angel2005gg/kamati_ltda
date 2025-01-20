export function toggleSelectInputMaquinaria(event) {
    const container = event.target.closest('.select-input-container_maquinaria');
    const select = container.querySelector('.select_proveedor_Maquinaria_class');
    const input = container.querySelector('.input_proveedor_Maquinaria_class');
    const isSelectVisible = !select.classList.contains('hidden');

    if (isSelectVisible) {
        select.classList.add('hidden');
        input.classList.remove('hidden');
    } else {
        select.classList.remove('hidden');
        input.classList.add('hidden');
    }
}