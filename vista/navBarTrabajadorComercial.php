<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
require_once "../modelo/Usuarios.php";
require_once "../modelo/dao/UsuariosDao.php";
$usersModal = new Usuarios();
$usuariosDAOmodal = new UsuariosDao();

$usersModal = $usuariosDAOmodal->consultarUserModal($_SESSION['idUser']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Links de boostrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Link de CSS personalizado -->
    <link rel="stylesheet" href="../css/styleKamaInitrApis.css">
    <!-- Link para logo icono Kamati -->
    <link rel="icon" type="image/png" href="../img/logo.png">
    <title>DashBoard</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-custom">
        <div class="container-fluid color_fluid">
            <a class="navbar-brand" href="DashboardTrabajadorComercial.php"><img src="../img/logo_transparent_kamati_cortada.png" alt="" class="img_logo_kamati"></a>
            <button class="navbar-toggler color-menu" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon color-menu"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text_custom" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Solicitudes
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="solicitarPermisoTrabajadorComercial.php">Solicitar novedad.</a></li>
                            <li><a class="dropdown-item" href="consultarMisSolicitudesTrabajadorComercial.php">Consultar mis solicitudes</a></li>

                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text_custom" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cotizaciones
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="CreacionCotizacion.php">Crear cotizaciones.</a></li>
                        </ul>
                    </li>
                    <!-- Incluir la sección de Gestión de Cursos -->
                    <?php include 'gestionCursos.php'; ?>

                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php


                            if ($_SESSION['nombre'] != null && $_SESSION['apellido'] != null) {


                                echo $_SESSION['nombre'] . ' ' . $_SESSION['apellido'];
                            } else {
                                echo "No user";
                            }

                            ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li>
                            <a class="dropdown-item btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <i class="fas fa-user icon-user"></i> Ver datos
</a> 
                            </li>
                            <li>
                                <a class="dropdown-item" href="../configuracion/logout.php">
                                    <i class="fas fa-sign-out-alt icon-user"></i> Salir
                                </a>
                            </li>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


   <div class="modal fade fixed_modal_kam" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Mis datos personales</h5>

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
</body>

</html>

<?php
