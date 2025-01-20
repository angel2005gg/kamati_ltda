<?php
require_once '../configuracion/ConexionBD.php';
class MaquinariaDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }
    public function guardarMaquinaria($materiales)
    {
        function limpiarTexto($texto)
        {
            $texto = str_replace(["\r", "\n"], " ", $texto); // Reemplazar saltos de línea con espacios
            $texto = preg_replace('/\s+/', ' ', $texto); // Reemplazar espacios múltiples con uno solo
            return trim($texto); // Eliminar espacios en los extremos
        }
        $conn = $this->conexion->conectarBD();
        $sql = "INSERT INTO tablamaquinaria (
                    id_identificadorMaquinaria_fk_maq, 
                    cantidadMaquinaria, 
                    unidaddesMaquinaria, 
                    abreviaturaMaquinaria, 
                    referenciaMaquinaria, 
                    materialMaquinaria, 
                    descripcionMaquinaria, 
                    proveedorMaquinaria, 
                    estadoButtonMaquinaria, 
                    notaMaquinaria, 
                    tipoMoneda, 
                    precioListaMaquinaria, 
                    costoUnitarioMaquinaria, 
                    costoTotalMaquinaria, 
                    valorUtilidadMaquinaria, 
                    valorTotalUtilidadMaquinaria, 
                    tiempoEntregaMaquinaria, 
                    descuentoMaquinaria, 
                    descuentoAdicionalMaquinaria, 
                    fechaTiempoEntregaMaquinaria, 
                    revisionMaquinaria, 
                    checkEstadoMaquinaria, 
                    factorAdicionalMaquinaria
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $conn->error);
            return false;
        }

        $insertedIds = [];

        foreach ($materiales as $fila) {
            $fila['descripcionMaterial'] = $conn->real_escape_string(limpiarTexto($fila['descripcionMaterial']));
            $fila['materialMaquinaria'] = $conn->real_escape_string(limpiarTexto($fila['materialMaquinaria']));
            $fila['notaMaquinaria'] = $conn->real_escape_string(limpiarTexto($fila['notaMaquinaria']));
            $fila['referenciaMaquinaria'] = $conn->real_escape_string(limpiarTexto($fila['referenciaMaquinaria']));
            $fila['proveedorMaquinaria'] = $conn->real_escape_string(limpiarTexto($fila['proveedorMaquinaria']));
            // Asignar valores predeterminados si son necesarios
            $stmt->bind_param(
                "iisissssissdddddsddssid",
                $fila['id_IdentificadorMaquinaria_fk_maq'],
                $fila['cantidadMaquinaria'],
                $fila['unidadesMaquinaria'],
                $fila['abreviaturaMaquinaria'],
                $fila['referenciaMaquinaria'],
                $fila['materialMaquinaria'],
                $fila['descripcionMaterial'],
                $fila['proveedorMaquinaria'],
                $fila['estadoButton'],
                $fila['notaMaquinaria'],
                $fila['tipoMoneda'],
                $fila['preciolistaMaquinaria'],
                $fila['costoUnitarioKamatiMaquinaria'],
                $fila['costoTotalKamatiMaquinaria'],
                $fila['valorUtilidadMaquinaria'],
                $fila['valorTotalUtilidadMaquinaria'],
                $fila['tiempoEntregaMaquinaria'],
                $fila['descuentoMaquinaria'],
                $fila['descuentoAdicional'],
                $fila['fechaTiempoEntregaMaquinaria'],
                $fila['revisionMaquinaria'],
                $fila['checkEstadoMaquinaria'],
                $fila['factorAdicionalMaquinaria']
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
    public function consultaidIdentificadorMaquinaria()
    {
        try {

            $conn = $this->conexion->conectarBD(); // Método que retorna la conexión
            $sql = "SELECT id_IdentificadorMaquinaria FROM identificadormaquinaria WHERE id_cotizacionComercial_FK_maquinaria = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $_SESSION['id_cotizacion']);
            $statement->execute();
            $result = $statement->get_result();

            $ids = [];
            while ($row = $result->fetch_assoc()) {
                $ids[] = $row['id_IdentificadorMaquinaria'];
            }

            $statement->close();
            $conn->close();
            return $ids; // No se necesita codificar en JSON aquí
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }

    public function consultarDatosIdentificadorMaquinaria()
    {
        try {
            // Obtenemos los IDs
            $ids = $this->consultaidIdentificadorMaquinaria();

            if (empty($ids)) {
                return []; // Si no hay datos, devolvemos un array vacío
            }

            $idCotizacion = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();

            // Crear los placeholders dinámicos para la cláusula IN
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Crear la consulta con la cláusula IN
            $sql = "SELECT id_IdentificadorMaquinaria, nombreTablaMaquinaria, checkFactoresMaquinaria, totalKamati, totalCliente 
                    FROM identificadormaquinaria 
                    WHERE id_cotizacionComercial_FK_maquinaria = ? AND id_IdentificadorMaquinaria IN ($placeholders)";

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

    public function getFactoresIndependientesMaquinaria()
    {
        try {
            $ids = $this->consultaidIdentificadorMaquinaria();

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
                        id_IdentificadorMaquinaria_FK, 
                        GROUP_CONCAT(id_FactoresIndependientesMaquinaria ORDER BY id_FactoresIndependientesMaquinaria ASC) AS ids_factores,
                        GROUP_CONCAT(valor_FactorIndependienteMaquinaria ORDER BY id_FactoresIndependientesMaquinaria ASC) AS valores_factores
                    FROM factoresindpendientesmaquinaria
                    WHERE id_IdentificadorMaquinaria_FK IN ($placeholders)
                    GROUP BY id_IdentificadorMaquinaria_FK";

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
    public function getFactoresAdicionalesMaquinaria()
    {
        try {
            $ids = $this->consultaidIdentificadorMaquinaria();

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
                        id_IdentificadorMaquinaria_fk, 
                        GROUP_CONCAT(id_FactoresAdicionalesMaquinaria ORDER BY id_FactoresAdicionalesMaquinaria ASC) AS ids_factores,
                        GROUP_CONCAT(valor_FactorAdicionalMaquinaria ORDER BY id_FactoresAdicionalesMaquinaria ASC) AS valores_factores
                    FROM factoresadicionalesmaquinaria
                    WHERE id_IdentificadorMaquinaria_fk IN ($placeholders)
                    GROUP BY id_IdentificadorMaquinaria_fk";

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
    public function obtenerMaquinaria()
    {
        try {
            // Obtener los IDs a filtrar
            $ids = $this->consultaidIdentificadorMaquinaria();
            if (empty($ids)) {
                return []; // Retornar vacío si no hay IDs
            }

            // Crear placeholders dinámicos
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $conn = $this->conexion->conectarBD();
            $sql = "SELECT 
                    id_identificadorMaquinaria_fk_maq,
                    GROUP_CONCAT(id_TablaMaquinaria) AS ids_Tablas,
                    GROUP_CONCAT(cantidadMaquinaria) AS cantidades,
                    GROUP_CONCAT(unidaddesMaquinaria) AS unidades,
                    GROUP_CONCAT(abreviaturaMaquinaria) AS abreviaturas,
                    GROUP_CONCAT(referenciaMaquinaria) AS referencias,
                    GROUP_CONCAT(materialMaquinaria) AS materiales,
                    GROUP_CONCAT(descripcionMaquinaria) AS descripciones,
                    GROUP_CONCAT(proveedorMaquinaria) AS proveedores,
                    GROUP_CONCAT(estadoButtonMaquinaria) AS estados_botones,
                    GROUP_CONCAT(notaMaquinaria) AS notas,
                    GROUP_CONCAT(tipoMoneda) AS monedas,
                    GROUP_CONCAT(precioListaMaquinaria) AS precios_lista,
                    GROUP_CONCAT(costoUnitarioMaquinaria) AS costos_unitarios,
                    GROUP_CONCAT(costoTotalMaquinaria) AS costos_totales,
                    GROUP_CONCAT(valorUtilidadMaquinaria) AS valores_utilidad,
                    GROUP_CONCAT(valorTotalUtilidadMaquinaria) AS valores_totales_utilidad,
                    GROUP_CONCAT(tiempoEntregaMaquinaria) AS tiempos_entrega,
                    GROUP_CONCAT(descuentoMaquinaria) AS descuentos,
                    GROUP_CONCAT(descuentoAdicionalMaquinaria) AS descuentos_adicionales,
                    GROUP_CONCAT(fechaTiempoEntregaMaquinaria) AS fechas_entrega,
                    GROUP_CONCAT(revisionMaquinaria) AS revisiones,
                    GROUP_CONCAT(checkEstadoMaquinaria) AS estados_check,
                    GROUP_CONCAT(factorAdicionalMaquinaria) AS factores_adicionales
                FROM tablamaquinaria
                WHERE id_identificadorMaquinaria_fk_maq IN ($placeholders)
                GROUP BY id_identificadorMaquinaria_fk_maq
                ORDER BY id_identificadorMaquinaria_fk_maq";

            $statement = $conn->prepare($sql);

            // Vincular parámetros dinámicamente
            $types = str_repeat('i', count($ids));
            $statement->bind_param($types, ...$ids);

            $statement->execute();
            $result = $statement->get_result();

            $materiales = [];
            while ($fila = $result->fetch_assoc()) {
                $materiales[$fila['id_identificadorMaquinaria_fk_maq']] = $fila;
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
    public function updateIdentificadorMaquinaria($nombreTablaMaquinaria, $checkFactoresMaquinaria, $totalKamatiMaquinaria, $totalClienteMaquinaria, $idIdentificadorMaquinaria)
    {
        try {
            session_start();
            $sessionId = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();

            // SQL para actualizar la tabla
            $sql = "UPDATE identificadormaquinaria SET 
                        nombreTablaMaquinaria = ?, 
                        checkFactoresMaquinaria = ?, 
                        totalKamati = ?, 
                        totalCliente = ? 
                    WHERE id_IdentificadorMaquinaria = ? AND id_cotizacionComercial_FK_maquinaria = ?";

            $statement = $conn->prepare($sql);
            $statement->bind_param("siddii", $nombreTablaMaquinaria, $checkFactoresMaquinaria, $totalKamatiMaquinaria, $totalClienteMaquinaria, $idIdentificadorMaquinaria, $sessionId);

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
    public function updateFactoresIndependientesIdentificadorMaquinaria($identificador, $idFactorIndependiente, $valorFactor)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE factoresindpendientesmaquinaria SET valor_FactorIndependienteMaquinaria = ?
            WHERE id_FactoresIndependientesMaquinaria = ? AND id_IdentificadorMaquinaria_FK = ?";
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
    public function updateFactoresAdicionalesIdentificadorMaquinaria($identificador, $idFactorAdicional, $valorFactor)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE factoresadicionalesmaquinaria SET valor_FactorAdicionalMaquinaria = ?
            WHERE id_FactoresAdicionalesMaquinaria = ? AND id_IdentificadorMaquinaria_fk = ?";
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
    public function updateTablaMaquinaria($data) {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE tablamaquinaria SET 
                cantidadMaquinaria = ?,
                unidaddesMaquinaria = ?,
                abreviaturaMaquinaria = ?,
                referenciaMaquinaria = ?,
                materialMaquinaria = ?,
                descripcionMaquinaria = ?,
                proveedorMaquinaria = ?,
                estadoButtonMaquinaria = ?,
                notaMaquinaria = ?,
                tipoMoneda = ?,
                precioListaMaquinaria = ?,
                costoUnitarioMaquinaria = ?,
                costoTotalMaquinaria = ?,
                valorUtilidadMaquinaria = ?,
                valorTotalUtilidadMaquinaria = ?,
                tiempoEntregaMaquinaria = ?,
                descuentoMaquinaria = ?,
                descuentoAdicionalMaquinaria = ?,
                fechaTiempoEntregaMaquinaria = ?,
                revisionMaquinaria = ?,
                checkEstadoMaquinaria = ?,
                factorAdicionalMaquinaria = ? 
                WHERE id_identificadorMaquinaria_fk_maq = ? AND id_TablaMaquinaria = ?";
            
            $statement = $conn->prepare($sql);
            $statement->execute([
                $data['cantidadMateriales'], 
                $data['unidadesMateriales'], 
                $data['abreviaturaMateriales'], 
                $data['referenciaMateriales'], 
                $data['material'], 
                $data['descripcionMaterial'], 
                $data['proveedorMateriales'], 
                $data['estadoButton'], 
                $data['notaMateriales'], 
                $data['tipoMoneda'], 
                $data['preciolistaMateriales'], 
                $data['costoUnitarioKamatiMateriales'], 
                $data['costoTotalKamatiMateriales'], 
                $data['valorUtilidadMateriales'], 
                $data['valorTotalUtilidadMateriales'], 
                $data['tiempoEntregaMateriales'], 
                $data['descuentoMateriales'], 
                $data['descuentoAdicional'], 
                $data['fechaTiempoEntregaMateriales'], 
                $data['revisionMateriales'], 
                $data['checkEstadoMateriales'], 
                $data['factorAdicionalMateriales'], 
                $data['idIdentificador'], 
                $data['id_TablaMateriales']
            ]);
    
            return true;
        } catch (Exception $e) {
            error_log('Error al actualizar: ' . $e->getMessage());
            return false;
        }
    }



    function guardarFilaMaquinaria($material) {
        $conn = $this->conexion->conectarBD();
        
        // Preparar la consulta SQL para insertar los materiales
        $sql = "INSERT INTO tablamaquinaria (
                    id_identificadorMaquinaria_fk_maq, 
                    cantidadMaquinaria, 
                    unidaddesMaquinaria, 
                    abreviaturaMaquinaria, 
                    referenciaMaquinaria, 
                    materialMaquinaria, 
                    descripcionMaquinaria, 
                    proveedorMaquinaria, 
                    estadoButtonMaquinaria, 
                    notaMaquinaria, 
                    tipoMoneda, 
                    precioListaMaquinaria, 
                    costoUnitarioMaquinaria, 
                    costoTotalMaquinaria, 
                    valorUtilidadMaquinaria, 
                    valorTotalUtilidadMaquinaria, 
                    tiempoEntregaMaquinaria, 
                    descuentoMaquinaria, 
                    descuentoAdicionalMaquinaria, 
                    fechaTiempoEntregaMaquinaria, 
                    revisionMaquinaria, 
                    checkEstadoMaquinaria, 
                    factorAdicionalMaquinaria
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
    public function delFilaTablaMaquinaria($datosDelete){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = "DELETE FROM tablamaquinaria WHERE id_TablaMaquinaria = ? AND id_identificadorMaquinaria_fk_maq = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('ii', $datosDelete['id_TablaMaquinaria'], $datosDelete['id_IdentificadorMaquinaria']);
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
