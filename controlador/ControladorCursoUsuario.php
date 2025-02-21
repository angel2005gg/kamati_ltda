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

    public function crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin, $dias_notificacion, $tipo) {
        return $this->cursoUsuario->crear($id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin, $dias_notificacion, $tipo);
    }

    public function obtenerUltimoIdCursoUsuario() {
        return $this->cursoUsuario->obtenerUltimoIdCursoUsuario();
    }
    // Métodos para manejar los días de notificación
    public function obtenerDiasNotificacion($id_curso_usuario) {
        return $this->cursoUsuario->obtenerDiasNotificacion($id_curso_usuario);
    }

    public function insertarDiasNotificacion($id_curso_usuario, $dias_notificacion) {
        return $this->cursoUsuario->insertarDiasNotificacion($id_curso_usuario, $dias_notificacion);
    }

    public function eliminarDiasNotificacion($id_curso_usuario) {
        return $this->cursoUsuario->eliminarDiasNotificacion($id_curso_usuario);
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
    public function actualizar($id, $id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin) {
        return $this->cursoUsuario->actualizar($id, $id_usuario, $id_curso_empresa, $fecha_inicio, $fecha_fin);
    }
    public function obtenerTodosFiltrados($filtros) {
        return $this->cursoUsuario->obtenerTodosFiltrados($filtros);
    }
    public function obtenerTodasLasAreas() {
        return $this->cursoUsuario->obtenerTodasLasAreas();
    }
    
    public function obtenerAñosDisponibles() {
        return $this->cursoUsuario->obtenerAñosDisponibles();
    }
    
    public function obtenerCursosDisponibles() {
        return $this->cursoUsuario->obtenerCursosDisponibles();
    }
    
    public function obtenerEmpresasDisponibles() {
        return $this->cursoUsuario->obtenerEmpresasDisponibles();
    }
    public function buscarUsuarios($termino) {
        return $this->cursoUsuario->buscarUsuarios($termino);
    }
    public function buscarContratistas($termino) {
        return $this->cursoUsuario->buscarContratistas($termino);
    }
}
?>