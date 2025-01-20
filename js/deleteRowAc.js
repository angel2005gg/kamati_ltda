
export function setupDeleteModal() {

    const deleteModal = document.getElementById('deleteModal');
    const confirmDeleteButton = document.getElementById('confirmDelete');
    let rowToDelete = null;

    // Confirmar eliminación
    confirmDeleteButton.addEventListener('click', function () {
        if (rowToDelete) {
            rowToDelete.remove();
            rowToDelete = null;
            $(deleteModal).modal('hide');
            
        }
    });

    return function (row) {
        rowToDelete = row;
        $('#deleteModal').modal('show');
    };
}