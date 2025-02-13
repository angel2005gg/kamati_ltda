<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function verificarAutenticacion() {
    $deshabilitarAutenticacion = false;  // Cambiamos esto a FALSE
    
    if (!$deshabilitarAutenticacion) {
        if (!isset($_SESSION['user'])) {
            header('Location: ../index.php');
            exit();
        }
    }
}
?>