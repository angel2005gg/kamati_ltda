<?php
require_once '../modelo/CursoEmpresa.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $controladorUsuario = new ControladorCursoUsuario();
    $usuario = $controladorUsuario->obtenerPorId($_POST['id']);

    if ($usuario) {
        echo json_encode(['area' => $usuario['area']]);
    } else {
        echo json_encode(['area' => '']);
    }
} else {
    echo json_encode(['error' => 'Solicitud inválida']);
}
class ControladorCursoEmpresa {
    private $cursoEmpresa;

    public function __construct() {
        $this->cursoEmpresa = new CursoEmpresa();
    }

    public function crear($id_empresa_cliente, $id_contratista, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado) {
        return $this->cursoEmpresa->crear($id_empresa_cliente, $id_contratista, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado);
    }

    public function obtenerPorId($id) {
        return $this->cursoEmpresa->obtenerPorId($id);
    }

    public function obtenerTodos() {
        return $this->cursoEmpresa->obtenerTodos();
    }

    public function actualizar($id, $id_empresa_cliente, $id_contratista, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado) {
        return $this->cursoEmpresa->actualizar($id, $id_empresa_cliente, $id_contratista, $nombre_curso, $fecha_realizacion, $fecha_vencimiento, $estado);
    }

    public function eliminar($id) {
        return $this->cursoEmpresa->eliminar($id);
    }

    public function obtenerPorEmpresa($id_empresa) {
        return $this->cursoEmpresa->obtenerPorEmpresa($id_empresa);
    }

    public function obtenerPorContratista($id_contratista) {
        return $this->cursoEmpresa->obtenerPorContratista($id_contratista);
    }
    
    
}
?>