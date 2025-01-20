<?php
require_once '../modelo/dao/CargoDao.php';
class ControladorCargos
{

    public function __construct()
    {
    }

    //Metodo de control para registros de nuevos cargos
    public function controlInsertCargo($cargo)
    {
        try {
            if ($cargo != null) {
                $cargosDao = new CargoDao();
                $cargosDao->insertCargos($cargo);
                return true;
            } else {
                throw new Exception("El objeto es nulo");
            }
        } catch (Exception $e) {
            error_log("Error al registrar un cargo: " . $e->getMessage());
            return false;
        }
    }
    //Metodo de control para actualizacion de cargos
    public function controlUpdateCargo($cargo)
    {
        try {
            if ($cargo != null) {
                $cargosDao = new CargoDao();
                $cargosDao->updateCargos($cargo);
                return true;
            } else {
                throw new Exception("El objeto es nulo");
            }
        } catch (Exception $e) {
            error_log("Error al registrar un cargo: " . $e->getMessage());
            return false;
        }
    }
}
