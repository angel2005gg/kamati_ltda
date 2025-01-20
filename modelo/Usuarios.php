<?php
//Clase de usuarios 

class Usuarios
{
    //Atributos
    private $id_Usuarios;
    private $primer_nombre, $segundo_nombre, $primer_apellido, $segundo_apellido;
    private $numero_identificacion, $correo_electronico, $numero_telefono_movil, $direccion_residencia;
    private $sede_laboral, $contrasena, $id_Cargo_Usuario, $id_Rol_Usuario, $salt, $id_jefe_area, $estado_usuario;
    private $nombre_cargo, $nombre_area;




    //Método constrtuctor con los this
    public function __construct(
        $id_Usuarios = null,
        $primer_nombre = null,
        $segundo_nombre = null,
        $primer_apellido = null,
        $segundo_apellido = null,
        $numero_identificacion = null,
        $correo_electronico = null,
        $numero_telefono_movil = null,
        $direccion_residencia = null,
        $sede_laboral = null,
        $contrasena = null,
        $id_Cargo_Usuario = null,
        $id_Rol_Usuario = null,
        $salt = null,
        $id_jefe_area = null,
        $estado_usuario = null,
        $nombre_cargo = null,
        $nombre_area = null
    ) {

        $this->id_Usuarios = $id_Usuarios;
        $this->primer_nombre = $primer_nombre;
        $this->segundo_nombre = $segundo_nombre;
        $this->primer_apellido = $primer_apellido;
        $this->segundo_apellido = $segundo_apellido;
        $this->numero_identificacion = $numero_identificacion;
        $this->correo_electronico = $correo_electronico;
        $this->numero_telefono_movil = $numero_telefono_movil;
        $this->direccion_residencia = $direccion_residencia;
        $this->sede_laboral = $sede_laboral;
        $this->contrasena = $contrasena;
        $this->id_Cargo_Usuario = $id_Cargo_Usuario;
        $this->id_Rol_Usuario = $id_Rol_Usuario;
        $this->salt = $salt;
        $this->id_jefe_area = $id_jefe_area;
        $this->estado_usuario = $estado_usuario;
        $this->nombre_cargo = $nombre_cargo;
        $this->nombre_area = $nombre_area;
    }

    //Métodos get y set de los atributos de la clase
    public function getId_Usuarios()
    {
        return $this->id_Usuarios;
    }
    public function setId_Usuarios($id_Usuarios)
    {
        return $this->id_Usuarios = $id_Usuarios;
    }
    public function getPrimer_nombre()
    {
        return $this->primer_nombre;
    }
    public function setPrimer_nombre($primer_nombre)
    {
        return $this->primer_nombre = $primer_nombre;
    }
    public function getSegundo_nombre()
    {
        return $this->segundo_nombre;
    }
    public function setSegundo_nombre($segundo_nombre)
    {
        return $this->segundo_nombre = $segundo_nombre;
    }
    public function getPrimer_apellido()
    {
        return $this->primer_apellido;
    }
    public function setPrimer_apellido($primer_apellido)
    {
        return $this->primer_apellido = $primer_apellido;
    }
    public function getSegundo_apellido()
    {
        return $this->segundo_apellido;
    }
    public function setSegundo_apellido($segundo_apellido)
    {
        return $this->segundo_apellido = $segundo_apellido;
    }
    public function getNumero_identificacion()
    {
        return $this->numero_identificacion;
    }
    public function setNumero_identificacion($numero_identificacion)
    {
        return $this->numero_identificacion = $numero_identificacion;
    }
    public function getCorreo_electronico()
    {
        return $this->correo_electronico;
    }
    public function setCorreo_electronico($correo_electronico)
    {
        return $this->correo_electronico = $correo_electronico;
    }
    public function getNumero_telefono_movil()
    {
        return $this->numero_telefono_movil;
    }
    public function setNumero_telefono_movil($numero_telefono_movil)
    {
        return $this->numero_telefono_movil = $numero_telefono_movil;
    }
    public function getDireccion_residencia()
    {
        return $this->direccion_residencia;
    }
    public function setDireccion_residencia($direccion_residencia)
    {
        return $this->direccion_residencia = $direccion_residencia;
    }
    public function getSede_laboral()
    {
        return $this->sede_laboral;
    }
    public function setSede_laboral($sede_laboral)
    {
        return $this->sede_laboral = $sede_laboral;
    }
    public function getContrasena()
    {
        return $this->contrasena;
    }
    public function setContrasena($contrasena)
    {
        return $this->contrasena = $contrasena;
    }
    public function getId_Cargo_Usuario()
    {
        return $this->id_Cargo_Usuario;
    }
    public function setId_Cargo_Usuario($id_Cargo_Usuario)
    {
        return $this->id_Cargo_Usuario = $id_Cargo_Usuario;
    }
    public function getId_Rol_Usuario()
    {
        return $this->id_Rol_Usuario;
    }
    public function setId_Rol_Usuario($id_Rol_Usuario)
    {
        return $this->id_Rol_Usuario = $id_Rol_Usuario;
    }
    public function getSalt()
    {
        return $this->salt;
    }
    public function setSalt($salt)
    {
        return $this->salt = $salt;
    }
    public function getId_jefe_area()
    {
        return $this->id_jefe_area;
    }
    public function setId_jefe_area($id_jefe_area)
    {
        $this->id_jefe_area = $id_jefe_area;

        return $this;
    }
    public function getEstado_usuario()
    {
        return $this->estado_usuario;
    }
    public function setEstado_usuario($estado_usuario)
    {
        $this->estado_usuario = $estado_usuario;

        return $this;
    }
    public function getNombre_cargo()
    {
        return $this->nombre_cargo;
    }

    public function setNombre_cargo($nombre_cargo)
    {
        $this->nombre_cargo = $nombre_cargo;

        return $this;
    }
    public function getNombre_area()
    {
        return $this->nombre_area;
    }
    public function setNombre_area($nombre_area)
    {
        $this->nombre_area = $nombre_area;

        return $this;
    }
}
