<?php
require_once '../modelo/CursoUsuario.php'; // Asegúrate de incluir el modelo CursoUsuario

$cursoUsuarioModel = new CursoUsuario();
$id_usuario = $_SESSION['idUser'];
$cursosAsignados = $cursoUsuarioModel->obtenerCursosPorUsuario($id_usuario);
$tieneCursosAsignados = !empty($cursosAsignados);
?>

<li class="nav-item dropdown">
    <?php
    if (isset($_SESSION['cargo'])) {
        // Para debug, muestra el cargo actual
        // echo "Cargo actual: " . $_SESSION['cargo'];
        if ($_SESSION['cargo'] == "Directora Sistema Integral SHEQ" || $_SESSION['cargo'] == "Coordinadora SST" || $_SESSION['cargo'] == "Practicante de SST" || $_SESSION['cargo'] == "Vigía SST") {  // Ajusta estos nombres según los cargos que deben tener acceso
            ?>
            <a class="nav-link dropdown-toggle text_custom" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Gestión de Cursos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="cursoAsociarEmpresa.php">Asociar Empresa</a></li>
                <li><a class="dropdown-item" href="ListaCursos.php">Listado Completo</a></li>
                <li><a class="dropdown-item" href="CursosGestion.php">Gestión de Datos</a></li>
                <?php if ($tieneCursosAsignados) { ?>
                    <li><a class="dropdown-item" href="ListaCursosEditar.php">Mis Cursos</a></li>
                <?php } ?>
            </ul>
    <?php
        } elseif ($tieneCursosAsignados) { // Verificar si el usuario tiene cursos asignados
    ?>
            <a class="nav-link dropdown-toggle text_custom" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Gestión de Cursos
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="ListaCursosEditar.php">Mis Cursos</a></li>
            </ul>
    <?php
        }
    }
    ?>
</li>