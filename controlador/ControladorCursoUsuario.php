<?php
require_once '../modelo/CursoUsuario.php';

class ControladorCursoUsuario {
    private $cursoUsuario;

    public function __construct() {
        $this->cursoUsuario = new CursoUsuario();
    }

    public function crear($id_usuario, $id_curso_empresa) {
        return $this->cursoUsuario->crear($id_usuario, $id_curso_empresa);
    }

    public function obtenerPorId($id) {
        return $this->cursoUsuario->obtenerPorId($id);
    }

    public function obtenerTodos() {
        return $this->cursoUsuario->obtenerTodos();
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