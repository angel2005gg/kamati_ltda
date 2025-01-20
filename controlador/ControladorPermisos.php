<?php
require_once '../modelo/dao/PermisosDao.php';
class ControladorPermisos
{



    public function __construct()
    {
    }


    //Metodo controlador de registro de permisos 
    public function controladorPermisos($permiso)
    {
        try {
            // Verificar si se ha proporcionado un objeto de usuario
            if ($permiso === null) {
                //Registro fallido
                throw new Exception("El objeto es nulo");
            } else {
                $permisosDao = new PermisosDao();

                require '../modelo/email/envioEmail.php';
                $permisosDao->registroSolicitudPermiso($permiso);
                // Registro exitoso
                return true;
            }
        } catch (Exception $e) {
            // Manejar cualquier error y devolver falso
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }


    //Metodo de control para aprobar desde gestion huamana
    public function controlUpdatePermisos($permisos)
    {
        try {
            if ($permisos === null) {
                throw new Exception("El objeto no tiene ningun valor");
            } else {

                $permisosDao = new PermisosDao();
                require_once '../modelo/email/envioEmailGestionHumana.php';
                $permisosDao->actualizarPermisoPendiente($permisos);
                return true;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar el permiso" . $e->getMessage());
            return false;
        }
    }
    //Metodo de control para no aprobar desde gestion huamana
    public function controlUpdatePermisosNo($permisos)
    {
        try {
            if ($permisos === null) {
                throw new Exception("El objeto no tiene ningun valor");
            } else {

                $permisosDao = new PermisosDao();
                require_once '../modelo/email/envioEmailGestionHumanaNo.php';
                $permisosDao->actualizarPermisoPendiente($permisos);
                return true;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar el permiso" . $e->getMessage());
            return false;
        }
    }

    //Metodo de control para mandar a estado de pendiente por el jefe inmediato
    public function controlUpdatePermisosJefe($permisos)
    {
        try {
            if ($permisos === null) {
                throw new Exception("El objeto no tiene ningun valor");
            } else {

                $permisosDao = new PermisosDao();
                require_once '../modelo/email/envioEmailJefeInmediato.php';
                $permisosDao->actualizarPermisoPendiente($permisos);
                return true;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar el permiso" . $e->getMessage());
            return false;
        }
    }
    //Metodo de control para mandar a estado de pendiente por el jefe inmediato
    public function controlUpdatePermisosJefeNo($permisos)
    {
        try {
            if ($permisos === null) {
                throw new Exception("El objeto no tiene ningun valor");
            } else {

                $permisosDao = new PermisosDao();
                require_once '../modelo/email/envioEmailJefeInmediatoNo.php';
                $permisosDao->actualizarPermisoPendiente($permisos);
                return true;
            }
        } catch (Exception $e) {
            error_log("Error al actualizar el permiso" . $e->getMessage());
            return false;
        }
    }
}
