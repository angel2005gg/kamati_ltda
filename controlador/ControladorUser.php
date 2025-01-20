<?php
require_once '../modelo/dao/UsuariosDao.php';
require_once '../modelo/dao/JefeAreaDao.php';

class ControladorUser
{

    private $usuariosDao;


    public function __construct()
    {

        $this->usuariosDao = new UsuariosDao();
    }

    //Control de registro de usuarios
    public function controlRegistrarUsuario($usuarios)
    {

        try {
            // Verificar si se ha proporcionado un objeto de usuario
            if ($usuarios === null) {
                //Registro fallido
                throw new Exception("El objeto de usuario es nulo");
            } else {
                $this->usuariosDao->registrarUsuario($usuarios);
                // Registro exitoso
                return true;
            }

            // Llamar al método de registro de usuario en el DAO de usuarios
        } catch (Exception $e) {
            // Manejar cualquier error y devolver falso
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }


    //Control de usuarios jefes para registro de usuarios jefes
    public function controlRegistrarJefe($usuario, $areas)
    {
        try {
            // Verificar si se ha proporcionado un objeto de usuario
            if ($usuario === null) {
                // Registro fallido
                throw new Exception("El objeto de usuario es nulo");
            } else {
                $registros = new JefeAreaDao();
                $registros->registroJefeArea($usuario, $areas);
                // Registro exitoso
                return true;
            }
        } catch (Exception $e) {
            // Manejar cualquier error y devolver falso
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }


    //Metodo de control para la actualizacion de los usuarios
    public function controlUpdateUser($usuarios)
    {
        try {
            if ($usuarios === null) {
                throw new Exception("El objeto es nulo");
            } else {
                $userDao = new UsuariosDao();
                $userDao->actualizarUsers($usuarios);
                return true;
            }
        } catch (Exception $e) {
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }

    //Controlador para actualizacion de contraseña del usuario seleccionado
    public function controlUpdateContraseña($salt, $contrasena, $identificacion)
    {
        try {
            if($salt != null && $contrasena != null && $identificacion != null){
                $userDao = new UsuariosDao();
                $userDao->updatePasswordUser($salt, $contrasena, $identificacion);
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}
