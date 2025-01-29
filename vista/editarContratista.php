<?php
require_once '../controlador/ControladorContratista.php';

$controlador = new ControladorContratista();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $contratista = $controlador->obtenerPorId($id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_contratista = $_POST['nombre_contratista'];
    $controlador->actualizar($id, $nombre_contratista);
    header('Location: vistaContratista.php');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Contratista</title>
</head>
<body>
    <h1>Editar Contratista</h1>
    <form method="POST" action="">
        <label for="nombre_contratista">Nombre:</label>
        <input type="text" id="nombre_contratista" name="nombre_contratista" value="<?php echo $contratista['nombre_contratista']; ?>" required>
        <button type="submit">Actualizar</button>
    </form>
    <a href="vistaContratista.php">Volver al listado</a>
</body>
</html>