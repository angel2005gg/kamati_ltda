<?php
function verificarAutenticacion() {
    // Cambia esta variable a true para deshabilitar la autenticación temporalmente
    $deshabilitarAutenticacion = false;

    if (!$deshabilitarAutenticacion) {
        session_start();
        if (!isset($_SESSION['usuario'])) {
            header('Location: ../index.php');
            exit();
        }
    }
}
?>