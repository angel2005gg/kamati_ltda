<?php
require_once '../modelo/Curso.php';

class ControladorCurso {
    private $curso;

    public function __construct() {
        $this->curso = new Curso();
    }

    public function crear($nombre_curso_fk) {
        return $this->curso->crear($nombre_curso_fk);
    }

    public function obtenerTodos() {
        return $this->curso->obtenerTodos();
    }

    public function eliminar($id_curso) {
        return $this->curso->eliminar($id_curso);
    }

    public function obtenerUltimoId() {
        return $this->curso->obtenerUltimoId();
    }
}
?>