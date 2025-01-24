<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../configuracion/ConexionBD.php';

$conexion = new ConexionBD();
$conn = $conexion->conectarBD();

if ($conn) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error al conectar a la base de datos.";
}

$conexion->desconectarBD();
?>
