<?php
require_once '../modelo/CursoUsuario.php';
require_once '../modelo/Usuarios.php';

class ControladorCursoUsuario {
    private $cursoUsuario;
    private $usuario;

    public function __construct() {
        $this->cursoUsuario = new CursoUsuario();
        $this->usuario = new Usuarios();
    }

    public function crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin) {
        return $this->cursoUsuario->crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin);
    }

    public function obtenerPorId($id) {
        return $this->cursoUsuario->obtenerPorId($id);
    }

    public function obtenerTodos() {
        return $this->cursoUsuario->obtenerTodos();
    }

    public function obtenerTodosUsuarios() {
        return $this->usuario->obtenerTodos();
    }
    public function eliminar($id) {
        return $this->cursoUsuario->eliminar($id);
    }

    public function obtenerCursosPorUsuario($id_usuario) {
        return $this->cursoUsuario->obtenerCursosPorUsuario($id_usuario);
    }

    public function obtenerUsuariosPorCurso($id_curso_empresa) {
        return $this->cursoUsuario->obtenerUsuariosPorCurso($id_curso_empresa);
    }

    public function verificarInscripcion($id_usuario, $id_curso_empresa) {
        return $this->cursoUsuario->verificarInscripcion($id_usuario, $id_curso_empresa);
    }
    
}
?>