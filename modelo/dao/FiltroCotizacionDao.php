<?php

require_once '../configuracion/ConexionBD.php';

class FiltroDao
{
    private $conexion;

    public function __construct()
    {   
        $this->conexion = new ConexionBD();
    }

    //Método de consulta a la bade de datos para filtro por medio de codigo de cotizacion
    public function consultaCodigoCotizacionFiltro($codigoCotizacion = null) {
        $resultados = [];
        try {
            $conn = $this->conexion->conectarBD();
    
            if ($codigoCotizacion) {
                $sql = "SELECT id_Cotizacion, nombre_cotizacion, codigo_cotizacion, fecha_creacion 
                        FROM cotizacioncomercial 
                        WHERE codigo_cotizacion LIKE ?";
                $stmt = $conn->prepare($sql);
                $param = '%' . $codigoCotizacion . '%';  // Agrega los comodines para búsqueda parcial
                $stmt->bind_param("s", $param);
            } else {
                $sql = "SELECT id_Cotizacion, nombre_cotizacion, codigo_cotizacion, fecha_creacion FROM cotizacioncomercial";
                $stmt = $conn->prepare($sql);
            }
    
            $stmt->execute();
            $resultado = $stmt->get_result();
    
            while ($fila = $resultado->fetch_assoc()) {
                $resultados[] = $fila;
            }
    
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            error_log("Error en consultaCodigoCotizacionFiltro: " . $e->getMessage());
            return [];
        }
    
        return $resultados;
    }
}