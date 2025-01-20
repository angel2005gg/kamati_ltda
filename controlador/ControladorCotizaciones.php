<?php

require_once '../modelo/dao/ComecialProjectsDao.php';
require_once '../modelo/dao/ViaticosCotizacionesDao.php';
require_once '../modelo/dao/CargoCotizacionesDao.php';
require_once '../modelo/dao/FactoresCotizacionesDao.php';
require_once '../modelo/dao/FactoresAdicionalesDao.php';
require_once '../modelo/dao/FactorIndependienteDao.php';

class ControladorComercial
{

    //Atributo de la clase para usar como objeto de la clase ComercialProjeectsDao
    private $cotizaciones;
    private $viaticosCotizacion;
    private $cargoCotizaciones;
    private $factoresCotizaciones;
    private $factoresAdicionales;
    private $factoresIndependientes;
    //CONSTRUCTOR importante para usar el atributo como el objeto 
    public function __construct()
    {
        $this->cotizaciones = new ComercialProjectsDao();
        $this->viaticosCotizacion = new ViaticosCtizacionesDao();
        $this->cargoCotizaciones = new CargoCotizacionesDao();
        $this->factoresCotizaciones = new FactoresCotizacionesDao();
        $this->factoresAdicionales = new FactoresAdicionalesDao();
        $this->factoresIndependientes = new FactorIndependienteDao();
    }

    //Método para obtener los datos del formulario por medio de la imitacion de servlet y mandarla al DAO
    public function controladorCreateProject($comercial)
    {
        try {
            if ($comercial != null) {
                $cotizaciones = $this->cotizaciones->createCotizacion($comercial);
                return $cotizaciones;
            } else {
                throw new Exception("El objeto de usuario es nulo");
            }
        } catch (Exception $e) {
            error_log("Error obtiendo los datos del formulario " . $e->getMessage());
            return false;
        }
    }

    //Controlador
    public function controladorSelectProjects()
    {
        try {
            // Llama al método selectCotizaciones y guarda el resultado
            $cotizaciones = $this->cotizaciones->selectCotizaciones();
            return $cotizaciones; // Devuelve el resultado para que pueda ser usado en otras partes
        } catch (Exception $e) {
            error_log("Error obteniendo los datos del formulario: " . $e->getMessage());
            return []; // Devuelve un arreglo vacío en caso de error
        }
    }

    //Método controlador para obtener los datos del servlet 
    public function controladorCreateDataViaticos($viatios)
    {
        try {
            if ($viatios != null) {
                $idInsert = $this->viaticosCotizacion->createData($viatios);
                return $idInsert;
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log("Error obteniendo los datos del formulario: " . $e->getMessage());
            return false;
        }
    }

    //Método constrolado para las insersiones de los datos de la tabla cargo cotizaciones
    public function controladorCreateDataCargos()
    {
        try {
            $insertIdsCotCargos = $this->cargoCotizaciones->registrarCargosCotizacion();

            if($insertIdsCotCargos){
                return $insertIdsCotCargos;
            }
            return [];
        } catch (Exception $e) {
            error_log("Error registrando los datos del formulario: " . $e->getMessage());
            return false;
        }
    }

    //Método constrolado para las insersiones de los datos de la tabla cargo cotizaciones
    public function controladorCreateDataFactores()
    {
        try {
            // Registrar los factores y obtener los IDs generados
            $insertedIds = $this->factoresCotizaciones->registrarFactoresCotizacion();

            if ($insertedIds) {
                // Retornar los IDs generados
                return $insertedIds;
            }
            return []; // Retorna un array vacío si no se generaron IDs
        } catch (Exception $e) {
            error_log("Error registrando los datos del formulario: " . $e->getMessage());
            return false;
        }
    }

    // //Método de insersion de datos en la tabla de factores adicionales
    public function controladorCreateDataFacAd($factorAd)
    {
        try {
            if ($factorAd != null) {
                $insertId = $this->factoresAdicionales->createFactoresCotizaciones($factorAd);
                return $insertId;
            }
        } catch (Exception $e) {
            error_log("Error en la creación de datos: " . $e->getMessage());
            return null;
        }
    }
}
