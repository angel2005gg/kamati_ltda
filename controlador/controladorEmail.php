<?php
require_once '../modelo/dao/EmailSoftwareDao.php';
class ControladorEmail
{


    public function __construct()
    {
    }


    public function controlEmail()
    {
        try {
            $emailDao = new EmailSoftwareDao();

            return $emailDao->consultarEmail();
           

           
        } catch (Exception $e) {
            // Manejar adecuadamente la excepción (por ejemplo, registrarla)
            error_log("Error en controlEmail: " . $e->getMessage());
            return null;
        }
    }
}
