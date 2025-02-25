<?php
require_once '../modelo/Usuarios.php';
session_start();

if (!isset($_SESSION['user'])) {
    die("Error: No se ha iniciado sesión.");
}

$usuario = $_SESSION['user'];
$rolUsuario = $usuario->getId_Rol_Usuario();

if ($rolUsuario === null) {
    die("Error: No se pudo determinar el rol del usuario.");
}

// Preparar los datos para el modal
require_once "../modelo/Usuarios.php";
require_once "../modelo/dao/UsuariosDao.php";

$usersModal = new Usuarios();
$usuariosDAOmodal = new UsuariosDao();

if (isset($_SESSION['idUser'])) {
    $usersModal = $usuariosDAOmodal->consultarUserModal($_SESSION['idUser']);
}

// Incluir el archivo de navegación correcto según el rol del usuario
switch ($rolUsuario) {
    case 1: // Admin
        if ($usuario->getId_Usuarios() === 5) {
            include 'navBarJefeAdminLin.php';
        } else {
            include 'navBarAdmin.php';
        }
        break;
    case 2: // Jefe
        if ($usuario->getId_Usuarios() === 4) {
            include 'navBarJefeAdminL.php';
        } else if ($usuario->getId_Usuarios() === 9) {
            include 'navBarJefeAdmin.php';
        } else if ($usuario->getId_Usuarios() === 1) {
            include 'navBarJefeHelmer.php';
        } else if ($usuario->getId_Usuarios() === 11) {
            include 'navBarJefeCielo.php';
        } else if ($usuario->getNombre_area() === 'Comercial') {
            if ($usuario->getId_Usuarios() === 7 || $usuario->getId_Usuarios() === 10) {
                include 'navBarJefeComercial.php';
            } else {
                include 'navBarJefe.php';
            }
        } else {
            include 'navBarJefe.php';
        }
        break;
    case 3: // Trabajador
        if ($usuario->getNombre_area() === 'Comercial') {
            if ($usuario->getId_Usuarios() === 84) {
                include 'navBarJefeComercial.php';
            } else {
                include 'navBarTrabajadorComercial.php';
            }
        } else if ($usuario->getId_Usuarios() === 47) {
            include 'navBarTrabajadorLuz.php';
        } else {
            include 'navBarTrabajador.php';
        }
        break;
    default:
        include 'navBar.php';
        break;
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
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md">
                        <i class="fas fa-user icon-user"></i>
                        <label>Nombre: </label>
                        <label><?php echo $usersModal->getPrimer_nombre() . " " . $usersModal->getSegundo_nombre() . " " . $usersModal->getPrimer_apellido() . " " . $usersModal->getSegundo_apellido() ?></label>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col-md">
                        <i class="fas fa-id-card icon-user"></i>
                        <label>Número de identificación: </label>
                        <label><?php echo $usersModal->getNumero_identificacion(); ?></label>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col-md">
                        <i class="fa-solid fa-envelope icon-user"></i>
                        <label>Correo electrónico: </label>
                        <label><?php echo $usersModal->getCorreo_electronico(); ?></label>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col-md">
                        <i class="fas fa-phone icon-user"></i>
                        <label>Teléfono móvil: </label>
                        <label><?php echo $usersModal->getNumero_telefono_movil(); ?></label>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col-md">
                        <i class="fa-solid fa-house icon-user"></i>
                        <label>Dirección: </label>
                        <label><?php echo $usersModal->getDireccion_residencia(); ?></label>
                    </div>
                </div>
                <br>
                <div class="row g-3">
                    <div class="col-md">
                        <i class="fa-solid fa-building icon-user"></i>
                        <label>Sede laboral: </label>
                        <label><?php echo $usersModal->getSede_laboral(); ?></label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Script necesario para Bootstrap 5 -->
<script>
// Asegurarnos de tener jQuery y Bootstrap
// Reemplaza el script actual por este en tu primer documento
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todos los botones que deberían abrir el modal
    var modalButtons = document.querySelectorAll('[data-toggle="modal"][data-target="#exampleModal"], [data-bs-toggle="modal"][data-bs-target="#exampleModal"]');
    
    modalButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        });
    });
    
    // Configurar los botones para cerrar el modal en Bootstrap 5
    var closeButtons = document.querySelectorAll('[data-dismiss="modal"]');
    closeButtons.forEach(function(button) {
        button.setAttribute('data-bs-dismiss', 'modal');
    });
});
</script>