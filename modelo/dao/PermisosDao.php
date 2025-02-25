<?php

require_once '../configuracion/ConexionBD.php';
require_once '../modelo/Permisos.php';

class PermisosDao
{
   private $conexion;

   public function __construct()
   {
      $this->conexion = new ConexionBD();
   }

   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //                            SECCIÓN DE METODO DE REGISTRO DE SOLICITUD DE PERMISO
   // Método de registro de solicitud de permiso
   public function registroSolicitudPermiso($permisos)
   {
      try {
         $conn = $this->conexion->conectarBD();

         // Verificar si la conexión es exitosa
         if ($conn->connect_error) {
            throw new Exception("Error en la conexión: " . $conn->connect_error);
         }

         $fecha_elaboracion = $permisos->getFecha_elaboracion();
         $tipo_permiso = $permisos->getTipo_permiso();
         $tiempo = $permisos->getTiempo();
         $cantidad_tiempo = $permisos->getCantidad_tiempo();
         $fecha_inicio_novedad = $permisos->getFecha_inicio_novedad();
         $fecha_fin_novedad = $permisos->getFecha_fin_novedad();
         $dias_compensados = $permisos->getDias_compensados();
         $cantidad_dias_compensados = $permisos->getCantidad_dias_compensados();
         $total_horas_permiso = $permisos->getTotal_horas_permiso();
         $motivo_novedad = $permisos->getMotivo_novedad();
         $id_Usuarios_permiso = $permisos->getId_Usuarios_permiso();
         $estado_permiso = $permisos->getEstado_permiso();
         $id_jefe_usuario = $permisos->getId_jefe_usuario();
         $id_gestion1 = $permisos->getId_gestionHumana1();
         $id_gestion2 = $permisos->getId_gestionHumana2();

         $sql = "INSERT INTO permisos (
                     fecha_elaboracion, tipo_permiso, tiempo, cantidad_tiempo,
                     fecha_inicio_novedad, fecha_fin_novedad, dias_compensados,
                     cantidad_dias_compensados, total_horas_permiso, motivo_novedad, id_Usuarios_permiso, 
                     estado_permiso, id_Usuario_jefe, id_Usuario_Gestion_Humana, id_Usuario_Gestion_Humana2) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

         $statement = $conn->prepare($sql);

         // Verificar si la declaración preparada se creó correctamente
         if (!$statement) {
            throw new Exception("Error en la preparación de la declaración: " . $conn->error);
         }

         $statement->bind_param(
            'sssisssiisisiii',
            $fecha_elaboracion,
            $tipo_permiso,
            $tiempo,
            $cantidad_tiempo,
            $fecha_inicio_novedad,
            $fecha_fin_novedad,
            $dias_compensados,
            $cantidad_dias_compensados,
            $total_horas_permiso,
            $motivo_novedad,
            $id_Usuarios_permiso,
            $estado_permiso,
            $id_jefe_usuario,
            $id_gestion1,
            $id_gestion2
         );

         if ($statement->execute()) {
            return $tipo_permiso;
         } else {
            throw new Exception("Error en la ejecución de la declaración: " . $statement->error);
         }
      } catch (Exception $e) {
         error_log("Error al registrar permiso: " . $e->getMessage());
         return false;
      } finally {
         if (isset($statement)) {
            $statement->close();
            $this->conexion->desconectarBD();
         }
      }
   }
   //                               FIN DE SECCIÓN DE REGISTRO DE SOLICITUD
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   //                               SECCIÓN DE METODOS PARA CONSULTAS DE PERMISOS O SOLICITUDES
   //Metodo de consulta de permisos de un solo usuario
   public function consultarPermisos($id_usuarios)
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT fecha_elaboracion, tipo_permiso,tiempo,cantidad_tiempo,fecha_inicio_novedad,fecha_fin_novedad,
         dias_compensados,cantidad_dias_compensados,total_horas_permiso,motivo_novedad, remuneracion,estado_permiso FROM permisos WHERE id_Usuarios_permiso = ?";
         $statement = $conn->prepare($sql);
         $statement->bind_param('i', $id_usuarios);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }
         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
      } finally {
         $conn = $this->conexion->desconectarBD();
      }
   }


   //Metodo de consulta de permisos solicitados
   public function consultarPermisoSolicitado($id_user_jefe)
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.primer_apellido, u.segundo_nombre, u.segundo_apellido, 
    	u.numero_identificacion, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
    	p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, 
    	p.dias_compensados, p.cantidad_dias_compensados, p.total_horas_permiso, 
    	p.motivo_novedad, p.remuneracion, p.estado_permiso, p.id_Permisos, 
       c.nombre_cargo, a.nombre_area FROM permisos AS p 
      INNER JOIN usuarios as u ON p.id_Usuarios_permiso = u.id_Usuarios 
      INNER JOIN cargo as c ON u.id_Cargo_Usuario = c.id_Cargo 
      INNER JOIN area as a ON c.id_area_fk = a.id_Area 
      WHERE p.id_Usuario_jefe = ? 
      ORDER BY FIELD(p.estado_permiso, 'Enviado', 'Pendiente', 'Negado', 'Aprobado', 'No Aprobado'), 
         p.fecha_elaboracion DESC";
         $statement = $conn->prepare($sql);
         $statement->bind_param('i', $id_user_jefe);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
      } finally {
         $conn = $this->conexion->desconectarBD();
      }
   }
   //Metodo de consulta de permisos para exportar a excel
   public function consultarPermisoSolicitadoExcel()
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.primer_apellido, u.segundo_nombre,u.segundo_apellido, 
         u.numero_identificacion,  u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, p.tiempo, 
         p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
         p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
         p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area FROM permisos AS p 
         INNER JOIN usuarios as u ON p.id_Usuarios_permiso = u.id_Usuarios 
         INNER JOIN cargo as c ON u.id_Cargo_Usuario = c.id_Cargo 
         INNER JOIN area as a ON c.id_area_fk = a.id_Area ";
         $statement = $conn->prepare($sql);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
      } finally {
         $conn = $this->conexion->desconectarBD();
      }
   }

   //Método de consulta de permisos que ya han sido enviados como pendientes o negados por el jefe inmediato 
   public function consultarPermisosPendientesNegado($id_usuario_per)
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
         p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
         p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
         p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area FROM permisos AS p inner join usuarios as u on p.id_Usuarios_permiso = u.id_Usuarios 
         inner join cargo as c on u.id_Cargo_Usuario = c.id_Cargo inner join area as a on c.id_area_fk = a.id_Area 
         WHERE (p.estado_permiso = 'Pendiente' OR p.estado_permiso = 'Negado') AND p.id_Usuarios_permiso != ?";
         $statement = $conn->prepare($sql);
         $statement->bind_param('i', $id_usuario_per);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
      } finally {
         $conn = $this->conexion->desconectarBD();
      }
   }
   //Método de consulta de permisos que ya han sido enviados como pendientes o negados por el jefe inmediato por medio de un fiultro de fecha
   public function consultarPermisosPendientesNegadoFiltroFecha($fecha, $id_usuario_per)
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
         p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
         p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
         p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area FROM permisos AS p inner join usuarios as u on p.id_Usuarios_permiso = u.id_Usuarios 
         inner join cargo as c on u.id_Cargo_Usuario = c.id_Cargo inner join area as a on c.id_area_fk = a.id_Area 
         WHERE (p.estado_permiso = 'Pendiente' OR p.estado_permiso = 'Negado') AND p.fecha_inicio_novedad = ? AND p.id_Usuarios_permiso != ?";
         $statement = $conn->prepare($sql);
         $statement->bind_param('si', $fecha, $id_usuario_per);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
      } finally {
         $conn = $this->conexion->desconectarBD();
      }
   }
   //Metodo para ver cuales son los permisos aprobados
   public function consultarPermisosAprobados($id_usuario_per)
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
         p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
         p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
         p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area FROM permisos AS p inner join usuarios as u on p.id_Usuarios_permiso = u.id_Usuarios 
         inner join cargo as c on u.id_Cargo_Usuario = c.id_Cargo inner join area as a on c.id_area_fk = a.id_Area 
         WHERE p.estado_permiso = 'Aprobado' AND p.id_Usuarios_permiso != ?";
         $statement = $conn->prepare($sql);
         $statement->bind_param('i', $id_usuario_per);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
      } finally {
         $conn = $this->conexion->desconectarBD();
      }
   }
   //Metodo para ver cuales son los permisos aprobados pero con fiiltro de fechas
   public function consultarPermisosAprobadosFiltroFecha($fecha, $id_usuario_per)
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, 
              u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
              p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, 
              p.dias_compensados, p.cantidad_dias_compensados, p.total_horas_permiso, 
              p.motivo_novedad, p.remuneracion, p.estado_permiso, p.id_Permisos, 
              c.nombre_cargo, a.nombre_area 
              FROM permisos AS p 
              INNER JOIN usuarios as u ON p.id_Usuarios_permiso = u.id_Usuarios 
              INNER JOIN cargo as c ON u.id_Cargo_Usuario = c.id_Cargo 
              INNER JOIN area as a ON c.id_area_fk = a.id_Area 
              WHERE p.estado_permiso = 'Aprobado' AND p.fecha_inicio_novedad = ? AND p.id_Usuarios_permiso != ?";
         $statement = $conn->prepare($sql);
         if (!$statement) {
            throw new Exception('Error al preparar la declaración: ' . $conn->error);
         }
         $statement->bind_param('si', $fecha, $id_usuario_per);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $result->close();
         $statement->close();
         return $data;
      } catch (Exception $e) {
         // Manejar la excepción
         error_log('Error en consultarPermisosAprobadosFiltroFecha: ' . $e->getMessage());
         return [];
      } finally {
         $this->conexion->desconectarBD();
      }
   }

   //Metodo de consaulta de todos los permisos
   public function consultarPermisosCompletos()
{
   try {
      $conn = $this->conexion->conectarBD();
      $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
      p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
      p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
      p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area FROM permisos AS p inner join usuarios as u on p.id_Usuarios_permiso = u.id_Usuarios 
      inner join cargo as c on u.id_Cargo_Usuario = c.id_Cargo inner join area as a on c.id_area_fk = a.id_Area 
      ORDER BY p.fecha_elaboracion DESC, p.id_Permisos DESC";
      $statement = $conn->prepare($sql);
      $statement->execute();
      $result = $statement->get_result();

      $data = [];
      if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            $data[] = $row;
         }
      }

      $statement->close();
      $result->close();
      return $data;
   } catch (Exception $e) {
      // Capturar y manejar la excepción
      error_log("Error en consultarPermisosCompletos: " . $e->getMessage());
      return [];
   } finally {
      if ($conn) {
         $conn = $this->conexion->desconectarBD();
      }
   }
}
   //Método de consulta por filtro de permisos pendientes y negados 
   public function consultarPermisoSolicitadoFiltroPendiente($nombre, $id_permiso_user)
   {
      $conn = null;
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
        p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
        p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
        p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area 
        FROM permisos AS p 
        INNER JOIN usuarios AS u ON p.id_Usuarios_permiso = u.id_Usuarios 
        INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo 
        INNER JOIN area AS a ON c.id_area_fk = a.id_Area 
        WHERE (p.estado_permiso = 'Negado' OR p.estado_permiso = 'Pendiente') AND u.primer_nombre LIKE ? AND p.id_Usuarios_permiso != ?";

         $statement = $conn->prepare($sql);
         $param = "%$nombre%";
         $statement->bind_param('si', $param, $id_permiso_user);
         $statement->execute();

         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $statement->close();
         $result->close();
         return $data;
      } catch (Exception $e) {
         // Registrar o manejar la excepción según sea necesario
         error_log("Error en consultarPermisoSolicitadoFiltro: " . $e->getMessage());
         return [];
      } finally {
         if ($conn !== null) {
            $conn = $this->conexion->desconectarBD();
         }
      }
   }
   //Metodo de consulta de permisos solicitados con filtro
   public function consultarPermisoSolicitadoFiltro($id_user_jefe, $fecha)
   {
      $conn = null;
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT u.primer_nombre, u.primer_apellido, u.segundo_nombre, u.segundo_apellido, u.numero_identificacion, 
                       u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, p.tiempo, p.cantidad_tiempo, 
                       p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, p.cantidad_dias_compensados, 
                       p.total_horas_permiso, p.motivo_novedad, p.remuneracion, p.estado_permiso, p.id_Permisos, 
                       c.nombre_cargo, a.nombre_area 
                FROM permisos AS p 
                INNER JOIN usuarios AS u ON p.id_Usuarios_permiso = u.id_Usuarios 
                INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo 
                INNER JOIN area AS a ON c.id_area_fk = a.id_Area 
                WHERE p.id_Usuario_jefe = ? AND p.fecha_inicio_novedad = ? ORDER BY FIELD(estado_permiso, 'Enviado','Pendiente', 'Negado', 'Aprobado','No Aprobado')";

         $statement = $conn->prepare($sql);

         if (!$statement) {
            throw new Exception("Error al preparar la declaración: " . $conn->error);
         }

         $statement->bind_param('is', $id_user_jefe, $fecha);
         $statement->execute();
         $result = $statement->get_result();

         $data = [];
         if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
               $data[] = $row;
            }
         }

         $result->close();
         $statement->close();
         return $data;
      } catch (Exception $e) {
         // Registrar o manejar la excepción según sea necesario
         error_log("Error en consultarPermisoSolicitadoFiltro: " . $e->getMessage());
         return [];
      } finally {
         if ($conn !== null) {
            $this->conexion->desconectarBD();
         }
      }
   }

   //Metodo de seleccion de permiso por medio del botón
   public function seleccionarPermiso($idPermiso)
   {
      try {
         $conn = $this->conexion->conectarBD();
         if (!$conn) {
            throw new Exception('Failed to connect to the database.');
         }

         $sql = "SELECT u.id_jefe_area, u.correo_electronico, u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, 
                u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, p.tiempo, p.cantidad_tiempo, 
                p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, p.cantidad_dias_compensados,p.id_Usuario_jefe, 
                p.total_horas_permiso, p.motivo_novedad, p.remuneracion, p.estado_permiso, p.id_Permisos, c.nombre_cargo, 
                a.nombre_area 
                FROM permisos AS p 
                INNER JOIN usuarios as u on p.id_Usuarios_permiso = u.id_Usuarios 
                INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo 
                INNER JOIN area AS a ON c.id_area_fk = a.id_Area 
                WHERE p.id_Permisos = ?";

         $statement = $conn->prepare($sql);
         if (!$statement) {
            throw new Exception('Failed to prepare the statement: ' . $conn->error);
         }

         $statement->bind_param('i', $idPermiso);
         $statement->execute();
         $result = $statement->get_result();
         $permiso = $result->fetch_assoc();

         if ($permiso != null) {
            if (session_status() == PHP_SESSION_NONE) {
               session_start();
            }
            $_SESSION['id_user_jefe_permiso'] = $permiso['id_jefe_area'];
            $_SESSION['nombre_persona_completo'] = $permiso['primer_nombre'] . " " . $permiso['segundo_nombre'] . " " . $permiso['primer_apellido'] . " " . $permiso['segundo_apellido'];
            $_SESSION['correo_persona'] = $permiso['correo_electronico'];

            return $permiso;
         } else {
            return false;
         }
      } catch (Exception $e) {
         return $e->getMessage();
      } finally {
         if (isset($result) && $result instanceof mysqli_result) {
            $result->close();
         }
         if (isset($statement) && $statement instanceof mysqli_stmt) {
            $statement->close();
         }
         if ($conn) {
            $this->conexion->desconectarBD();
         }
      }
   }


   //Método de consulta de los tipos de permisos para mostrar indicadores de barras donde muestre la
   //cantidad de permisos, licencias, dias de la familia, cumpleaños y vacaciones se solicitan

   public function consultarTiposPermisos()
   {
      try {
         $conn = $this->conexion->conectarBD();
         $sql = "SELECT tipo_permiso, COUNT(*) as contador FROM permisos WHERE estado_permiso = 'Aprobado' GROUP BY tipo_permiso";
         $statement = $conn->prepare($sql);
         $statement->execute();
         $result = $statement->get_result();
         $tipos_permisos = [];

         while ($row = $result->fetch_assoc()) {
            $tipos_permisos[] = $row;
         }

         $statement->close();
         $result->close();

         return $tipos_permisos;
      } catch (Exception $e) {
         error_log("Falla en la consulta de los tipos de permisos: " . $e->getMessage());
         return false;
      } finally {
         $this->conexion->desconectarBD($conn);
      }
   }
   //Metodo de consaulta de todos los permisos pero por filtro de fechas
   public function consultarPermisosCompletosFiltroFecha($fecha)
{
   try {
      $conn = $this->conexion->conectarBD();
      $sql = "SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.numero_identificacion, u.segundo_apellido, u.sede_laboral, p.fecha_elaboracion, p.tipo_permiso, 
       p.tiempo, p.cantidad_tiempo, p.fecha_inicio_novedad, p.fecha_fin_novedad, p.dias_compensados, 
       p.cantidad_dias_compensados, p.total_horas_permiso, p.motivo_novedad, p.remuneracion, 
       p.estado_permiso, p.id_Permisos, c.nombre_cargo, a.nombre_area 
       FROM permisos AS p 
       INNER JOIN usuarios AS u ON p.id_Usuarios_permiso = u.id_Usuarios 
       INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo 
       INNER JOIN area AS a ON c.id_area_fk = a.id_Area 
       WHERE p.fecha_elaboracion = ?
       ORDER BY p.fecha_elaboracion DESC, p.id_Permisos DESC";
      $statement = $conn->prepare($sql);
      $statement->bind_param('s', $fecha);
      $statement->execute();
      $result = $statement->get_result();

      $data = [];
      if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            $data[] = $row;
         }
      }

      $statement->close();
      $result->close();
      return $data;
   } catch (Exception $e) {
      // Manejar la excepción
      error_log("Error en consultarPermisosCompletosFiltroFecha: " . $e->getMessage());
      return [];
   } finally {
      if ($conn) {
         $conn = $this->conexion->desconectarBD();
      }
   }
}


   // FIN DE SECCION DE CONSULTAS DE PERMISOS
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   // INICIO DE SECCIÓN DE ACTUALIZACIÓN DE PERMISOS

   //Metodo de actualización de permiso cuando pasa por el jefe inmediato

   public function actualizarPermisoPendiente($permisos)

   {
      try {

         $conn = $this->conexion->conectarBD();

         $estado_permiso = $permisos->getEstado_permiso();
         $remuneracion = $permisos->getRemuneracion();
         $id_Permisos = $permisos->getId_Permisos();

         $sql = "UPDATE permisos SET remuneracion = ?, estado_permiso = ? WHERE id_Permisos = ?";
         $statement = $conn->prepare($sql);
         $statement->bind_param('ssi', $remuneracion, $estado_permiso, $id_Permisos);
         $statement->execute();

         if ($statement->affected_rows > 0) {
            return true;
         } else {
            return false;
         }
      } catch (Exception $e) {
         return $e->getMessage();
      } finally {
         if (isset($result)) {
            $result->close();
         }
         if (isset($statement)) {
            $statement->close();
         }
         $conn = $this->conexion->desconectarBD();
      }
   }

   // FIN DE SECCIÓN DE ACTUALZACIÓN DE PERMISOS
   ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
