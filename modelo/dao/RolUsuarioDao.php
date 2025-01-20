<?php
include '../modelo/RolUsuario.php';
require_once '../configuracion/ConexionBD.php';

class RolUsuarioDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Metodo de consulta de roles para el registro de usuarios
    public function consultaRoles()
    {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT id_Rol_Usuario, nombre_rol FROM rolusuario ORDER BY id_Rol_Usuario";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        $nombre_rol = array();

        while ($row = $result->fetch_assoc()) {
            $nombre_rol[$row['id_Rol_Usuario']] = $row['nombre_rol'];
        }

        if ($nombre_rol != null) {
            return $nombre_rol;
        } else {
            return null;
        }
    }
}
