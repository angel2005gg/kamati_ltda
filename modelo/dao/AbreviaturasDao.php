<?php
require_once '../configuracion/ConexionBD.php';
class AbreviaturasDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }


    public function consultarAbreviaturas()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Abreviatura, abreviaturas FROM abreviatura ORDER BY abreviaturas";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $abreviatura = array();

                while ($row = $result->fetch_assoc()) {
                    $abreviatura[$row['id_Abreviatura']] = $row['abreviaturas'];
                }

                if ($abreviatura != null) {
                    return $abreviatura;
                } else {
                    return false;
                }
            }
            $statement->close();
            $result->close();
        } catch (Exception $e) {
            return error_log("Error con la consulta de las abreviaturas" .$e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }
}
