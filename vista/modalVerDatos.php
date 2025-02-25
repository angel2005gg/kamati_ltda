<?php
// Verificar que las variables necesarias estén disponibles
if (!isset($usersModal) || !is_object($usersModal)) {
    require_once "../modelo/Usuarios.php";
    require_once "../modelo/dao/UsuariosDao.php";
    
    $usersModal = new Usuarios();
    $usuariosDAOmodal = new UsuariosDao();
    
    if (isset($_SESSION['idUser'])) {
        $usersModal = $usuariosDAOmodal->consultarUserModal($_SESSION['idUser']);
    }
}
?>

<!-- Modal para ver datos personales -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mis datos personales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Contenido del modal... -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Este script asegura que los botones que usan data-toggle="modal" también funcionen con Bootstrap 5
    var modalButtons = document.querySelectorAll('[data-toggle="modal"][data-target="#exampleModal"]');
    
    modalButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var modal = new bootstrap.Modal(document.getElementById('exampleModal'));
            modal.show();
        });
    });
});
</script>