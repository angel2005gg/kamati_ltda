<?php
require_once '../controlador/ControladorCursoUsuario.php';

if (isset($_GET['term'])) {
    $controlador = new ControladorCursoUsuario();
    $termino = $_GET['term'];
    
    $usuarios = $controlador->buscarUsuarios($termino);
    
    echo json_encode($usuarios);
} else {
    echo json_encode([]);
}
?>