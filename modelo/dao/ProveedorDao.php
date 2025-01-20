<?php
require_once '../configuracion/ConexionBD.php';
class ProveedorDao{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }


    //Consulta de proveedores de select

    public function consultarProveedores(){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = 'SELECT idProveedor, nombre_preveedor FROM proveedor ORDER BY nombre_preveedor';
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $proveedor = array();

                while ($row = $result->fetch_assoc()) {
                    $proveedor[$row['idProveedor']] = $row['nombre_preveedor'];
                }
                if ($proveedor != null) {
                    return $proveedor;
                } else {
                    return false;
                }
            }
            $result->close();
            $statement->close();

        }catch(Exception $e){
            return error_log("Error de consultade proveedores"). $e->getMessage();
        }finally{
            $conn = $this->conexion->desconectarBD();
        }
    }

}
?>