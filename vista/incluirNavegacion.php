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