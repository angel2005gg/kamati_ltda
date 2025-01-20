<?php

require_once '../configuracion/ConexionBD.php';
class TipoNovedadDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Metodo de consula de tipos de novedades

    public function consultarTiposNovedad()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_TipoNovedad, nombre_novedad FROM tiponovedad ORDER BY id_TipoNovedad";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            $tipoNovedad = array();

            while ($row = $result->fetch_assoc()) {
                $tipoNovedad[$row['id_TipoNovedad']] = $row['nombre_novedad'];
            }

            if($tipoNovedad != null){
                return $tipoNovedad;
            }else{
                return false;
            }

            $statement->close();
            $result->close();

        } catch (Exception $e) {
            return error_log("Error con la consulta de los tipo de novedades") . $e->getMessage();
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }
}
