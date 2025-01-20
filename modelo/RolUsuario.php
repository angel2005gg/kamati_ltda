<?php
class RolUsuario
{
    private int $id_Rol_Usuario;
    private String $nombre_rol;

    public function __construct(
        int $id_Rol_Usuario = null,
        String $nombre_rol = null
    ) {
        $this->id_Rol_Usuario = $id_Rol_Usuario;
        $this->nombre_rol = $nombre_rol;
    }

    public function getId_Rol_Usuario()
    {
        return $this->id_Rol_Usuario;
    }

    public function setId_Rol_Usuario($id_Rol_Usuario)
    {
        $this->id_Rol_Usuario = $id_Rol_Usuario;

        return $this;
    }

    public function getNombre_rol()
    {
        return $this->nombre_rol;
    }

    public function setNombre_rol($nombre_rol)
    {
        $this->nombre_rol = $nombre_rol;

        return $this;
    }
}
