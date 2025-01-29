<?php
require_once '../controlador/ControladorContratista.php';

$controlador = new ControladorContratista();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $contratista = $controlador->obtenerPorId($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controlador->eliminar($id);
    header('Location: vistaContratista.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Contratista</title>
</head>
<body>
    <h1>Eliminar Contratista</h1>
    <p>¿Estás seguro de que deseas eliminar al contratista "<?php echo $contratista['nombre_contratista']; ?>"?</p>
    <form method="POST" action="">
        <button type="submit">Eliminar</button>
    </form>
    <a href="vistaContratista.php">Cancelar</a>
</body>
</html>