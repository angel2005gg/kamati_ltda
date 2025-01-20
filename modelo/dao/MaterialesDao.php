<?php
require_once '../configuracion/ConexionBD.php';
class MaterialesDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }
     public function guardarMateriales($materiales)
{
    function limpiarTexto($texto) {
        $texto = str_replace(["\r", "\n"], " ", $texto); // Reemplazar saltos de línea con espacios
        $texto = preg_replace('/\s+/', ' ', $texto); // Reemplazar espacios múltiples con uno solo
        return trim($texto); // Eliminar espacios en los extremos
    }
    $conn = $this->conexion->conectarBD();
    $sql = "INSERT INTO tablamateriales (
                id_IdentificadorMateriales_fk_mat, 
                cantidadMateriales, 
                unidadesMateriales, 
                abreviaturaMateriales, 
                referenciaMateriales, 
                material, 
                descripcionMaterial, 
                proveedorMateriales, 
                estadoButton, 
                notaMateriales, 
                tipoMoneda, 
                preciolistaMateriales, 
                costoUnitarioKamatiMateriales, 
                costoTotalKamatiMateriales, 
                valorUtilidadMateriales, 
                valorTotalUtilidadMateriales, 
                tiempoEntregaMateriales, 
                descuentoMateriales, 
                descuentoAdicional, 
                fechaTiempoEntregaMateriales, 
                revisionMateriales, 
                checkEstadoMateriales, 
                factorAdicionalMateriales
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        error_log("Error en la preparación de la consulta: " . $conn->error);
        return false;
    }

    $insertedIds = [];

    foreach ($materiales as $fila) {
        // Limpieza de datos
        $fila['descripcionMaterial'] = $conn->real_escape_string(limpiarTexto($fila['descripcionMaterial']));
        $fila['material'] = $conn->real_escape_string(limpiarTexto($fila['material']));
        $fila['notaMateriales'] = $conn->real_escape_string(limpiarTexto($fila['notaMateriales']));
        $fila['referenciaMateriales'] = $conn->real_escape_string(limpiarTexto($fila['referenciaMateriales']));
        $fila['proveedorMateriales'] = $conn->real_escape_string(limpiarTexto($fila['proveedorMateriales']));

        $stmt->bind_param(
            "iisissssissdddddsddssid",
            $fila['id_IdentificadorMateriales_fk_mat'],
            $fila['cantidadMateriales'],
            $fila['unidadesMateriales'],
            $fila['abreviaturaMateriales'],
            $fila['referenciaMateriales'],
            $fila['material'],
            $fila['descripcionMaterial'],
            $fila['proveedorMateriales'],
            $fila['estadoButton'],
            $fila['notaMateriales'],
            $fila['tipoMoneda'],
            $fila['preciolistaMateriales'],
            $fila['costoUnitarioKamatiMateriales'],
            $fila['costoTotalKamatiMateriales'],
            $fila['valorUtilidadMateriales'],
            $fila['valorTotalUtilidadMateriales'],
            $fila['tiempoEntregaMateriales'],
            $fila['descuentoMateriales'],
            $fila['descuentoAdicional'],
            $fila['fechaTiempoEntregaMateriales'],
            $fila['revisionMateriales'],
            $fila['checkEstadoMateriales'],
            $fila['factorAdicionalMateriales']
        );

        if ($stmt->execute()) {
            $insertedIds[] = $conn->insert_id; // Guarda el ID de la fila insertada
        } else {
            error_log("Error en la ejecución de la consulta: " . $stmt->error);
            return false; // Si hay un error, retorna false
        }
    }

    $stmt->close();
    $conn->close();

    if (empty($insertedIds)) {
        error_log("No se insertaron filas. Verifica los datos de entrada.");
        return false;
    }

    return $insertedIds; // Devuelve los IDs insertados si todo es exitoso
    
}


    //Método para traer los ids de los identificadores de la cotización creada
    public function consultaidIdentificadorMateriales()
    {
        try {

            $conn = $this->conexion->conectarBD(); // Método que retorna la conexión
            $sql = "SELECT id_IdentificadorMateriales FROM identificadormateriales WHERE id_cotizacionesComercial_FK = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $_SESSION['id_cotizacion']);
            $statement->execute();
            $result = $statement->get_result();

            $ids = [];
            while ($row = $result->fetch_assoc()) {
                $ids[] = $row['id_IdentificadorMateriales'];
            }

            $statement->close();
            $conn->close();
            return $ids; // No se necesita codificar en JSON aquí
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }

    public function consultarDatosIdentificadorMateriales()
    {
        try {
            // Obtenemos los IDs
            $ids = $this->consultaidIdentificadorMateriales();

            if (empty($ids)) {
                return []; // Si no hay datos, devolvemos un array vacío
            }

            $idCotizacion = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();

            // Crear los placeholders dinámicos para la cláusula IN
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Crear la consulta con la cláusula IN
            $sql = "SELECT id_IdentificadorMateriales, nombreTablaMateriales, checkFactoresMateriales, totalKamati, totalCliente 
                    FROM identificadormateriales 
                    WHERE id_cotizacionesComercial_FK = ? AND id_IdentificadorMateriales IN ($placeholders)";

            $statement = $conn->prepare($sql);

            // Crear el tipo de datos para bind_param (i para cada ID + el idCotizacion)
            $types = str_repeat('i', count($ids) + 1); // 'i' por cada entero (idCotizacion y cada id en $ids)

            // Combina el idCotizacion con los ids que vinieron de la consulta
            $params = array_merge([$idCotizacion], $ids);

            // Usamos call_user_func_array para pasar los parámetros al bind_param
            // Esto es necesario para pasar los parámetros de forma dinámica
            $statement->bind_param($types, ...$params);

            $statement->execute();
            $result = $statement->get_result();

            $datos = [];
            while ($row = $result->fetch_assoc()) {
                // Cada conjunto de datos tendrá un 'id_IdentificadorMateriales' para asociarlo con el contenedor correcto
                $datos[] = $row;
            }

            $statement->close();
            $conn->close();

            return $datos; // Devolvemos los datos con el identificador único
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }

    public function getFactoresIndependientesMateriales()
    {
        try {
            $ids = $this->consultaidIdentificadorMateriales();

            if (empty($ids)) {
                return []; // Si no hay datos, devolvemos un array vacío
            }

            $conn = $this->conexion->conectarBD();
            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos.");
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Modificar la consulta para incluir también los IDs de los factores
            $sql = "SELECT 
                        id_IdentificadorMateriales_FK, 
                        GROUP_CONCAT(id_FactoresIndependientes ORDER BY id_FactoresIndependientes ASC) AS ids_factores,
                        GROUP_CONCAT(valor_FactorIndependiente ORDER BY id_FactoresIndependientes ASC) AS valores_factores
                    FROM factoresindependientes
                    WHERE id_IdentificadorMateriales_FK IN ($placeholders)
                    GROUP BY id_IdentificadorMateriales_FK";

            $statement = $conn->prepare($sql);
            if (!$statement) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }

            $types = str_repeat('i', count($ids));
            $statement->bind_param($types, ...$ids);
            $statement->execute();
            $result = $statement->get_result();

            $datos = [];
            while ($row = $result->fetch_assoc()) {
                // Convertir los strings devueltos por GROUP_CONCAT en arrays
                $row['ids_factores'] = array_map('intval', explode(',', $row['ids_factores']));
                $row['valores_factores'] = array_map('floatval', explode(',', $row['valores_factores']));
                $datos[] = $row;
            }

            $statement->close();
            $conn->close();

            return $datos;
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }
    public function getFactoresAdicionalesMateriales()
    {
        try {
            $ids = $this->consultaidIdentificadorMateriales();

            if (empty($ids)) {
                return []; // Si no hay datos, devolvemos un array vacío
            }

            $conn = $this->conexion->conectarBD();
            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos.");
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Modificar la consulta para incluir también los IDs de los factores
            $sql = "SELECT 
                        id_IdentificadorMateriales_fk, 
                        GROUP_CONCAT(id_FactoresAdicionalesComercial ORDER BY id_FactoresAdicionalesComercial ASC) AS ids_factores,
                        GROUP_CONCAT(valor_FactoresAdicionalesMateriales ORDER BY id_FactoresAdicionalesComercial ASC) AS valores_factores
                    FROM factoresadicionalesmateriales
                    WHERE id_IdentificadorMateriales_fk IN ($placeholders)
                    GROUP BY id_IdentificadorMateriales_fk";

            $statement = $conn->prepare($sql);
            if (!$statement) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }

            $types = str_repeat('i', count($ids));
            $statement->bind_param($types, ...$ids);
            $statement->execute();
            $result = $statement->get_result();

            $datos = [];
            while ($row = $result->fetch_assoc()) {
                // Convertir los strings devueltos por GROUP_CONCAT en arrays
                $row['ids_factores'] = array_map('intval', explode(',', $row['ids_factores']));
                $row['valores_factores'] = array_map('floatval', explode(',', $row['valores_factores']));
                $datos[] = $row;
            }

            $statement->close();
            $conn->close();

            return $datos;
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }
    //Método de consulta de los datos de toda la tabla de materiales
    // Función para obtener los materiales
    public function obtenerMateriales()
    {
        try {
            // Obtener los IDs a filtrar
            $ids = $this->consultaidIdentificadorMateriales();
            if (empty($ids)) {
                return []; // Retornar vacío si no hay IDs
            }

            // Crear placeholders dinámicos
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $conn = $this->conexion->conectarBD();
            $sql = "SELECT 
                    id_IdentificadorMateriales_fk_mat,
                    GROUP_CONCAT(id_TablaMateriales) AS ids_Tablas,
                    GROUP_CONCAT(cantidadMateriales) AS cantidades,
                    GROUP_CONCAT(unidadesMateriales) AS unidades,
                    GROUP_CONCAT(abreviaturaMateriales) AS abreviaturas,
                    GROUP_CONCAT(referenciaMateriales) AS referencias,
                    GROUP_CONCAT(material) AS materiales,
                    GROUP_CONCAT(descripcionMaterial) AS descripciones,
                    GROUP_CONCAT(proveedorMateriales) AS proveedores,
                    GROUP_CONCAT(estadoButton) AS estados_botones,
                    GROUP_CONCAT(notaMateriales) AS notas,
                    GROUP_CONCAT(tipoMoneda) AS monedas,
                    GROUP_CONCAT(preciolistaMateriales) AS precios_lista,
                    GROUP_CONCAT(costoUnitarioKamatiMateriales) AS costos_unitarios,
                    GROUP_CONCAT(costoTotalKamatiMateriales) AS costos_totales,
                    GROUP_CONCAT(valorUtilidadMateriales) AS valores_utilidad,
                    GROUP_CONCAT(valorTotalUtilidadMateriales) AS valores_totales_utilidad,
                    GROUP_CONCAT(tiempoEntregaMateriales) AS tiempos_entrega,
                    GROUP_CONCAT(descuentoMateriales) AS descuentos,
                    GROUP_CONCAT(descuentoAdicional) AS descuentos_adicionales,
                    GROUP_CONCAT(fechaTiempoEntregaMateriales) AS fechas_entrega,
                    GROUP_CONCAT(revisionMateriales) AS revisiones,
                    GROUP_CONCAT(checkEstadoMateriales) AS estados_check,
                    GROUP_CONCAT(factorAdicionalMateriales) AS factores_adicionales
                FROM tablamateriales
                WHERE id_IdentificadorMateriales_fk_mat IN ($placeholders)
                GROUP BY id_IdentificadorMateriales_fk_mat
                ORDER BY id_IdentificadorMateriales_fk_mat";

            $statement = $conn->prepare($sql);

            // Vincular parámetros dinámicamente
            $types = str_repeat('i', count($ids));
            $statement->bind_param($types, ...$ids);

            $statement->execute();
            $result = $statement->get_result();

            $materiales = [];
            while ($fila = $result->fetch_assoc()) {
                $materiales[$fila['id_IdentificadorMateriales_fk_mat']] = $fila;
            }

            $statement->close();
            $conn->close();
            return $materiales;
        } catch (Exception $e) {
            throw new Exception('Error en la consulta de los materiales: ' . $e->getMessage());
        }
    }




    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //SECCIÓN DE MÉTODOS DE ACTUALIZACION DE MATAERIALES

    //Método de update de identificador de materiales 
    public function updateIdentificadorMateriales($nombreTablaMateriales, $checkFactoresMateriales, $totalKamati, $totalCliente, $idIdentificadorMateriales)
    {
        try {
            session_start();
            $sessionId = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();

            // SQL para actualizar la tabla
            $sql = "UPDATE identificadormateriales SET 
                        nombreTablaMateriales = ?, 
                        checkFactoresMateriales = ?, 
                        totalKamati = ?, 
                        totalCliente = ? 
                    WHERE id_IdentificadorMateriales = ? AND id_cotizacionesComercial_FK = ?";

            $statement = $conn->prepare($sql);
            $statement->bind_param("siddii", $nombreTablaMateriales, $checkFactoresMateriales, $totalKamati, $totalCliente, $idIdentificadorMateriales, $sessionId);

            // Ejecutar la consulta
            if ($statement->execute()) {
                return true;
            } else {
                error_log('Error al ejecutar la consulta: ' . $statement->error);
                return false;
            }
        } catch (Exception $e) {
            error_log('Error en el update: ' . $e->getMessage());
            return false;
        }
    }

    //Método de update para factores independientes del identificador materiales
    public function updateFactoresIndependientesIdentificadorMateriales($identificador, $idFactorIndependiente, $valorFactor)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE factoresindependientes SET valor_FactorIndependiente = ?
            WHERE id_FactoresIndependientes = ? AND id_IdentificadorMateriales_FK = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valorFactor, $idFactorIndependiente, $identificador);
            if ($statement->execute()) {
                return true;
            } else {
                error_log('No se ha actualizado correctamente el factor ' . $statement->error);
                return false;
            }
        } catch (Exception $e) {
            error_log('Falla en la actualizacion desde el try ' . $e->getMessage());
            return false;
        }
    }
    public function updateFactoresAdicionalesIdentificadorMateriales($identificador, $idFactorAdicional, $valorFactor)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE factoresadicionalesmateriales SET valor_FactoresAdicionalesMateriales = ?
            WHERE id_FactoresAdicionalesComercial = ? AND id_IdentificadorMateriales_fk = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valorFactor, $idFactorAdicional, $identificador);
            if ($statement->execute()) {
                return true;
            } else {
                error_log('No se ha actualizado correctamente el factor ' . $statement->error);
                return false;
            }
        } catch (Exception $e) {
            error_log('Falla en la actualizacion desde el try ' . $e->getMessage());
            return false;
        }
    }

    //Método de update para las filas de la tabla materiales de la base de datos
    public function updateTablaMateriales($data) {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE tablamateriales SET 
                cantidadMateriales = ?, unidadesMateriales = ?, abreviaturaMateriales = ?, 
                referenciaMateriales = ?, material = ?, descripcionMaterial = ?, 
                proveedorMateriales = ?, estadoButton = ?, notaMateriales = ?, 
                tipoMoneda = ?, preciolistaMateriales = ?, costoUnitarioKamatiMateriales = ?, 
                costoTotalKamatiMateriales = ?, valorUtilidadMateriales = ?, valorTotalUtilidadMateriales = ?, 
                tiempoEntregaMateriales = ?, descuentoMateriales = ?, descuentoAdicional = ?, 
                fechaTiempoEntregaMateriales = ?, revisionMateriales = ?, checkEstadoMateriales = ?, 
                factorAdicionalMateriales = ? WHERE id_IdentificadorMateriales_fk_mat = ? AND id_TablaMateriales = ?";
            
            $statement = $conn->prepare($sql);
            $statement->execute([
                $data['cantidadMateriales'], $data['unidadesMateriales'], $data['abreviaturaMateriales'], 
                $data['referenciaMateriales'], $data['material'], $data['descripcionMaterial'], 
                $data['proveedorMateriales'], $data['estadoButton'], $data['notaMateriales'], 
                $data['tipoMoneda'], $data['preciolistaMateriales'], $data['costoUnitarioKamatiMateriales'], 
                $data['costoTotalKamatiMateriales'], $data['valorUtilidadMateriales'], $data['valorTotalUtilidadMateriales'], 
                $data['tiempoEntregaMateriales'], $data['descuentoMateriales'], $data['descuentoAdicional'], 
                $data['fechaTiempoEntregaMateriales'], $data['revisionMateriales'], $data['checkEstadoMateriales'], 
                $data['factorAdicionalMateriales'], $data['idIdentificador'], $data['id_TablaMateriales']
            ]);
    
            return true;
        } catch (Exception $e) {
            error_log('Error al actualizar: ' . $e->getMessage());
            return false;
        }
    }



    function guardarFilaMaterial($material) {
        $conn = $this->conexion->conectarBD();
        
        // Preparar la consulta SQL para insertar los materiales
        $sql = "INSERT INTO tablamateriales (
                    id_IdentificadorMateriales_fk_mat, cantidadMateriales, unidadesMateriales, abreviaturaMateriales, 
                    referenciaMateriales, material, descripcionMaterial, proveedorMateriales, estadoButton, notaMateriales, 
                    tipoMoneda, preciolistaMateriales, costoUnitarioKamatiMateriales, costoTotalKamatiMateriales, 
                    valorUtilidadMateriales, valorTotalUtilidadMateriales, tiempoEntregaMateriales, descuentoMateriales, 
                    descuentoAdicional, fechaTiempoEntregaMateriales, revisionMateriales, checkEstadoMateriales, factorAdicionalMateriales
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            // Si hay un error al preparar la consulta, devolver error
            echo json_encode([
                'success' => false, 
                'message' => 'Error preparando la consulta: ' . $conn->error
            ]);
            return false; // Asegurarse de que no se enviará más de una respuesta
        }
    
        // Eliminar comas de miles y convertir a números decimales
        $preciolista = floatval(str_replace(',', '', $material['preciolistaMateriales']));
        $costoUnitario = floatval(str_replace(',', '', $material['costoUnitarioKamatiMateriales']));
        $costoTotal = floatval(str_replace(',', '', $material['costoTotalKamatiMateriales']));
        $valorUtilidad = floatval(str_replace(',', '', $material['valorUtilidadMateriales']));
        $valorTotalUtilidad = floatval(str_replace(',', '', $material['valorTotalUtilidadMateriales']));
        $descuento = floatval(str_replace(',', '', $material['descuentoMateriales']));
        $descuentoAdicional = floatval(str_replace(',', '', $material['descuentoAdicional']));
        $factorAdicional = floatval(str_replace(',', '', $material['factorAdicionalMateriales']));
    
        // Vínculo de los parámetros a la consulta
        $stmt->bind_param(
            "iisissssissdddddsddssid",
            $material['id_IdentificadorMateriales_fk_mat'],
            $material['cantidadMateriales'],
            $material['unidadesMateriales'],
            $material['abreviaturaMateriales'],
            $material['referenciaMateriales'],
            $material['material'],
            $material['descripcionMaterial'],
            $material['proveedorMateriales'],
            $material['estadoButton'],
            $material['notaMateriales'],
            $material['tipoMoneda'],
            $preciolista,
            $costoUnitario,
            $costoTotal,
            $valorUtilidad,
            $valorTotalUtilidad,
            $material['tiempoEntregaMateriales'],
            $descuento,
            $descuentoAdicional,
            $material['fechaTiempoEntregaMateriales'],
            $material['revisionMateriales'],
            $material['checkEstadoMateriales'],
            $factorAdicional
        );
    
        // Ejecutar la consulta
        if ($stmt->execute()) {
            return $conn->insert_id; // Devolver el ID insertado
        } else {
            return false; // Si la ejecución falla, devolver false
        }
    
        $stmt->close();
        $conn->close();
    }
    public function delFilaTablaMateriales($datosDelete){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = "DELETE FROM tablamateriales WHERE id_TablaMateriales = ? AND id_IdentificadorMateriales_fk_mat = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('ii', $datosDelete['id_TablaMateriales'], $datosDelete['id_Identificador']);
            $statement->execute();
    
            if ($statement->affected_rows > 0) {
                return json_encode(['status' => 'success', 'message' => 'Fila eliminada con éxito']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'No se encontró la fila']);
            }
    
        } catch(Exception $e){
            error_log('Error al eliminar: '.$e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Error al eliminar: '.$e->getMessage()]);
        }
    }

}
