<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'ConexionBD.php'; // Asegúrate de que esta ruta sea correcta

$conexion = new ConexionBD();
$conn = $conexion->conectarBD();

if ($conn) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error al conectar a la base de datos.";
}

$conexion->desconectarBD();
?>
