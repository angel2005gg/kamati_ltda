<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function verificarAutenticacion() {
    $deshabilitarAutenticacion = TRUE;

    if (!$deshabilitarAutenticacion) {
        if (!isset($_SESSION['user'])) {
            header('Location: ../index.php');
            exit();
        }
    } else {
        if (!isset($_SESSION['user'])) {
            $_SESSION['user'] = [
               
                
               
                'idUser' => 86
                
            ];
            $_SESSION['idUser'];
        }
    }
}
?>