<?php
require_once __DIR__ . '/../../configuracion/ConexionBD.php';
require_once __DIR__ . '/../EmailSoftware.php';

class EmailSoftwareDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    public function consultarEmail()
    {
        try {
            $conn = $this->conexion->conectarBD();
            if (!$conn) {
                throw new Exception('Error al conectar a la base de datos.');
            }

            $sql = "SELECT correo, clave FROM email_software";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $correo = $result->fetch_assoc();

                $email = new EmailSoftware();
                $email->setCorreo($correo['correo']);
                $email->setClave($correo['clave']);
                return $email;
            } else {
                return null; // No se encontraron datos
            }
        } catch (Exception $e) {
            error_log('Error en consultarEmail: ' . $e->getMessage() . ' en ' . $e->getFile() . ' línea ' . $e->getLine());
            return null;
        } finally {
            if (isset($result) && $result instanceof mysqli_result) {
                $result->close();
            }
            if (isset($statement)) {
                $statement->close();
            }
            $this->conexion->desconectarBD();
        }
    }
    
}