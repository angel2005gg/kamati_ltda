let currentRow = null;

function editRow(button) {
    currentRow = button.closest('tr');
    const input = currentRow.querySelector('.claseEditModal');
    const isEditable = !input.hasAttribute('readonly');

    document.getElementById('modalText1').innerText = isEditable ? '¿Estás seguro de que quieres guardar este cambio?' : '¿Estás seguro de que quieres editar este valor?';
    $('#editModal1').modal('show'); // Abre el modal
}

// Exponer `editRow` globalmente
window.editRow = editRow;

// Manejar la confirmación del modal
document.querySelector('.confirmEditClass').addEventListener('click', function() {
    const input = currentRow.querySelector('.claseEditModal');
    const button = currentRow.querySelector('button.edit-btn');
    const icon = button.querySelector('span.fa');


    if (input.hasAttribute('readonly')) {
        // Cambiar a modo de edición
        input.removeAttribute('readonly');
        icon.classList.remove('fa-pencil-alt');
        icon.classList.add('fa-save');
    } else {
        // Cambiar a modo de solo lectura
        input.setAttribute('readonly', true);
        icon.classList.remove('fa-save');
        icon.classList.add('fa-pencil-alt');    
    }

    $('#editModal1').modal('hide'); // Cierra el modal
});

// Manejar el botón de cancelar del modal
document.getElementById('cancelEdit1').addEventListener('click', function() {
    $('#editModal1').modal('hide'); // Cierra el modal sin hacer cambios
});