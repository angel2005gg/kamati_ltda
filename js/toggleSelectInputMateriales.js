export function toggleSelectInput(event) {
    const container = event.target.closest('.contenedor_class_uniqueSelectMateriales');
    console.log(container);
    const select = container.querySelector('.select_proveedor_materiales_class');
    const input = container.querySelector('.input_proveedor_materiales_class');
    const isSelectVisible = !select.classList.contains('hidden');

    if (isSelectVisible) {
        select.classList.add('hidden');
        input.classList.remove('hidden');
    } else {
        select.classList.remove('hidden');
        input.classList.add('hidden');
    }
}