<?php

use PhpOffice\PhpSpreadsheet\Calculation\Information\ExcelError;

require_once '../configuracion/ConexionBD.php';
class ActividadesDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }



    public function insertTurnoActividades($data)
    {
        // Conexión a la base de datos
        $conn = $this->conexion->conectarBD();
        $conn->begin_transaction();

        // Sentencia SQL para insertar el turno
        $sql = "INSERT INTO turnoactividades (id_identificadorActividades_fk, horaInicioTurno, horaFinTurno, tipoTurno) 
            VALUES (?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            foreach ($data as $item) {
                $idIdentificador = $item['idIdentificador'];
                $horaInicioTurno = $item['horaInicioTurno'];
                $horaFinTurno = $item['horaFinTurno'];
                $tipoTurno = $item['tipoTurno'];

                // Vincular los parámetros
                $stmt->bind_param("isss", $idIdentificador, $horaInicioTurno, $horaFinTurno, $tipoTurno);

                if (!$stmt->execute()) {
                    $conn->rollback();
                    error_log("Error en la inserción: " . $stmt->error);
                    return false;
                }

                // Obtener el ID del turno insertado
                $insertId = $conn->insert_id;

                // Insertar las actividades con el ID del turno
                $actividadesIds = $this->guardarActividades($insertId, $item['actividades'], $conn);


                if ($actividadesIds === false) {
                    $conn->rollback();
                    return false;
                }
            }

            $conn->commit();
            $stmt->close();
            return true;
        } else {
            error_log("Error al preparar la consulta: " . $conn->error);
            return false;
        }
    }
    public function guardarActividades($turnoId, $materiales, $conn)
    {
        function limpiarTexto($texto) {
            $texto = str_replace(["\r", "\n"], " ", $texto); // Reemplazar saltos de línea con espacios
            $texto = preg_replace('/\s+/', ' ', $texto); // Reemplazar espacios múltiples con uno solo
            return trim($texto); // Eliminar espacios en los extremos
        }
        // Sentencia SQL para insertar las actividades
        $sql = "INSERT INTO tablaactividades (id_TurnoActividaes_fk, cantidad, unidad, abreviaturaLinea,descripcionBreve
        , descripcionPersonal, cantidadPersonas, nota, costoExternoUnitario, costoAlimentacion
        , costoTransporte, costoDiaKamati, costoTotalDiasKamati, valorDiaUtilidad, valorTotalUtilidad
        , rep, checkActividades, factorAdicional, estadoButtonAlimentacion, estadoButtonTransporte, estadoButtonPersonal) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            error_log("Error en la preparación de la consulta: " . $conn->error);
            return false;
        }
        $insertedIds = [];
        foreach ($materiales as $fila) {
            $fila['descripcionBreve'] = $conn->real_escape_string(limpiarTexto($fila['descripcionBreve']));
            $fila['descripcionPersonal'] = $conn->real_escape_string(limpiarTexto($fila['descripcionPersonal']));
            $fila['nota'] = $conn->real_escape_string(limpiarTexto($fila['nota']));
            error_log(print_r($fila, true));
            $stmt->bind_param(
                "iisissisiiiiiiisidiis",
                $turnoId,
                $fila['cantidad'],
                $fila['unidad'],
                $fila['abreviaturaLinea'],
                $fila['descripcionBreve'],
                $fila['descripcionPersonal'],
                $fila['cantidadPersonas'],
                $fila['nota'],
                $fila['costoExternoUnitario'],
                $fila['costoAlimentacion'],
                $fila['costoTransporte'],
                $fila['costoDiaKamati'],
                $fila['costoTotalDiasKamati'],
                $fila['valorDiaUtilidad'],
                $fila['valorTotalUtilidad'],
                $fila['rep'],
                $fila['checkActividades'],
                $fila['factorAdicional'],
                $fila['estadoButtonAlimentacion'],
                $fila['estadoButtonTransporte'],
                $fila['estadoButtonPersonal'],
            );
            if ($stmt->execute()) {

                $insertedIds[] = $conn->insert_id;
            } else {
                error_log("Error en la ejecución de la consulta: " . $stmt->error);
                $stmt->close();
                return false;
            }
        }
        $stmt->close();
        if (empty($insertedIds)) {
            error_log("No se insertaron actividades. Verifica los datos de entrada.");
            return false;
        }
        return $insertedIds;
    }

    public function insertFilaActividadesAdicional($fila)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Sentencia SQL para insertar las actividades
            $sql = "INSERT INTO tablaactividades (id_TurnoActividaes_fk, cantidad, unidad, abreviaturaLinea,descripcionBreve
        , descripcionPersonal, cantidadPersonas, nota, costoExternoUnitario, costoAlimentacion
        , costoTransporte, costoDiaKamati, costoTotalDiasKamati, valorDiaUtilidad, valorTotalUtilidad
        , rep, checkActividades, factorAdicional, estadoButtonAlimentacion, estadoButtonTransporte, estadoButtonPersonal) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "iisissisiiiiiiisidiis",
                    $fila['id_TurnoActividaes_fk'],
                    $fila['cantidad'],
                    $fila['unidad'],
                    $fila['abreviaturaLinea'],
                    $fila['descripcionBreve'],
                    $fila['descripcionPersonal'],
                    $fila['cantidadPersonas'],
                    $fila['nota'],
                    $fila['costoExternoUnitario'],
                    $fila['costoAlimentacion'],
                    $fila['costoTransporte'],
                    $fila['costoDiaKamati'],
                    $fila['costoTotalDiasKamati'],
                    $fila['valorDiaUtilidad'],
                    $fila['valorTotalUtilidad'],
                    $fila['rep'],
                    $fila['checkActividades'],
                    $fila['factorAdicional'],
                    $fila['estadoButtonAlimentacion'],
                    $fila['estadoButtonTransporte'],
                    $fila['estadoButtonPersonal'],
                );
                if ($stmt->execute()) {

                    $insertedIds[] = $conn->insert_id;
                } else {
                    error_log("Error en la ejecución de la consulta: " . $stmt->error);
                    $stmt->close();
                    return false;
                }
            
            $stmt->close();
            if (empty($insertedIds)) {
                error_log("No se insertaron actividades. Verifica los datos de entrada.");
                return false;
            }
            return $insertedIds;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

    public function insertTurnoFilaNuevo($turnos){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = "INSERT INTO turnoactividades (id_identificadorActividades_fk, horaInicioTurno, horaFinTurno, tipoTurno) 
            VALUES (?, ?, ?, ?)";
            $statement = $conn->prepare($sql);
            $statement->bind_param('isss', $turnos['idIdentificador'], $turnos['horaInicioTurno'], $turnos['horaFinTurno'], $turnos['tipoTurno']); 
            $statement->execute();

            return $conn->insert_id;

        }catch(Exception $e) {  
            error_log($e->getMessage());
        }
    }


    //Método para traer los ids de los identificadores de la cotización creada
    public function consultaidIdentificadorActividades()
    {
        try {

            $conn = $this->conexion->conectarBD(); // Método que retorna la conexión
            $sql = "SELECT id_IdentificadorActividades FROM identificadoractividades WHERE id_cotizacionesComercial_fk = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $_SESSION['id_cotizacion']);
            $statement->execute();
            $result = $statement->get_result();

            $ids = [];
            while ($row = $result->fetch_assoc()) {
                $ids[] = $row['id_IdentificadorActividades'];
            }

            $statement->close();
            $conn->close();
            return $ids; // No se necesita codificar en JSON aquí
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }
    public function consultaidIdentificadorTurno()
    {
        try {
            // Obtener los IDs de identificadores de actividades
            $identificadores = $this->consultaidIdentificadorActividades();

            if (empty($identificadores)) {
                return []; // Si no hay datos, devolvemos un array vacío
            }

            // Conexión a la base de datos
            $conn = $this->conexion->conectarBD();

            // Crear placeholders dinámicos según el número de identificadores
            $placeholders = implode(',', array_fill(0, count($identificadores), '?'));
            $sql = "SELECT id_TurnoActividades FROM turnoactividades WHERE id_identificadorActividades_fk IN ($placeholders)";
            $statement = $conn->prepare($sql);

            if ($statement === false) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Bind dinámico de los identificadores
            $types = str_repeat('i', count($identificadores)); // Asumimos que los IDs son enteros
            $statement->bind_param($types, ...$identificadores);

            // Ejecutar la consulta
            $statement->execute();
            $result = $statement->get_result();

            if ($result === false) {
                throw new Exception("Error al ejecutar la consulta: " . $statement->error);
            }

            // Recoger los resultados
            $turnoIds = [];
            while ($row = $result->fetch_assoc()) {
                $turnoIds[] = $row['id_TurnoActividades'];
            }

            // Cerrar la conexión y la consulta
            $statement->close();
            $conn->close();

            return $turnoIds; // Retornar el arreglo de IDs
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }

    public function consultarDatosIdentificadorActividades()
    {
        try {
            // Obtenemos los IDs
            $ids = $this->consultaidIdentificadorActividades();

            if (empty($ids)) {
                return []; // Si no hay datos, devolvemos un array vacío
            }

            $idCotizacion = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();

            // Crear los placeholders dinámicos para la cláusula IN
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Crear la consulta con la cláusula IN
            $sql = "SELECT id_IdentificadorActividades, nombreTablaActividades, checkFactoresIndependientes, totalKamatiActividades, totalClienteActividades 
                    FROM identificadoractividades 
                    WHERE id_cotizacionesComercial_fk = ? AND id_IdentificadorActividades IN ($placeholders)";

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

    public function getFactoresIndependientesActividades()
    {
        try {
            $ids = $this->consultaidIdentificadorActividades();

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
                        id_IdentificadorActividades_fk, 
                        GROUP_CONCAT(id_FactIndActividades ORDER BY id_FactIndActividades ASC) AS ids_factores,
                        GROUP_CONCAT(factorActividadesIndependienteValor ORDER BY id_FactIndActividades ASC) AS valores_factores
                    FROM factoresindependientesactividades
                    WHERE id_IdentificadorActividades_fk IN ($placeholders)
                    GROUP BY id_IdentificadorActividades_fk";

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

    // Método para obtener las filas de TurnoActividades
    public function getFilasTurnoActividades()
    {
        try {
            $conn = $this->conexion->conectarBD();

            $ids = $this->consultaidIdentificadorActividades();
            if (empty($ids)) {
                return []; // Retornar vacío si no hay IDs
            }

            // Crear placeholders dinámicos
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Preparar la consulta SQL
            $sql = "SELECT id_TurnoActividades, id_identificadorActividades_fk, horaInicioTurno, horaFinTurno, tipoTurno 
                    FROM turnoactividades WHERE id_identificadorActividades_fk IN ($placeholders)";

            // Preparar la declaración
            $stmt = $conn->prepare($sql);

            // Crear tipo para cada ID, 'i' para enteros
            $types = str_repeat('i', count($ids));
            // Vincular los parámetros a la consulta preparada
            $stmt->bind_param($types, ...$ids);

            if ($stmt->execute() === false) {
                die('Error en la ejecución de la consulta: ' . $stmt->error);
            }

            // Obtener el resultado
            $result = $stmt->get_result();

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                $filas = [];
                while ($row = $result->fetch_assoc()) {
                    $filas[] = $row;  // Guardar cada fila de resultados
                }

                return $filas;  // Devolver las filas obtenidas
            } else {
                return [];  // Si no hay resultados, retornamos un array vacío
            }
        } catch (Exception $e) {
            // Manejo de errores, retornamos el mensaje de la excepción
            return ['error' => $e->getMessage()];
        }
    }

    //Método de cosulta de datos de los datos de las filas de actividades
    public function getDataActividades()
    {
        try {
            // Conexión a la base de datos
            $conn = $this->conexion->conectarBD();

            // Obtener los IDs de identificadores de actividades
            $ids = $this->consultaidIdentificadorTurno();

            if (empty($ids)) {
                return json_encode([]); // Retornar un JSON vacío si no hay IDs
            }

            // Crear placeholders dinámicos para la consulta
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Query SQL con placeholders
            $sql = "
                SELECT 
                    id_TablaActividades,
                    id_TurnoActividaes_fk, 
                    cantidad, 
                    unidad, 
                    abreviaturaLinea, 
                    descripcionBreve, 
                    descripcionPersonal, 
                    cantidadPersonas, 
                    nota, 
                    costoExternoUnitario, 
                    costoAlimentacion, 
                    costoTransporte, 
                    costoDiaKamati, 
                    costoTotalDiasKamati, 
                    valorDiaUtilidad, 
                    valorTotalUtilidad, 
                    rep, 
                    checkActividades, 
                    factorAdicional, 
                    estadoButtonAlimentacion,
                    estadoButtonTransporte,
                    estadoButtonPersonal
                FROM tablaactividades 
                WHERE id_TurnoActividaes_fk IN ($placeholders)
            ";

            $statement = $conn->prepare($sql);

            if ($statement === false) {
                throw new Exception("Error al preparar la consulta: " . $conn->error);
            }

            // Bind dinámico de parámetros
            $types = str_repeat('i', count($ids)); // Asumimos que todos los IDs son enteros
            $statement->bind_param($types, ...$ids);

            // Ejecutar la consulta
            $statement->execute();
            $result = $statement->get_result();

            if ($result === false) {
                throw new Exception("Error al ejecutar la consulta: " . $statement->error);
            }

            // Recoger resultados
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Cerrar recursos
            $statement->close();
            $conn->close();

            return $data;
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////
    //SECCIÓN DE MÉTODOS DE ACTUALIZACION DE MATAERIALES

    //Método de update de identificador de materiales 
    public function updateIdentificadorActidades($nombreTablaActividades, $checkFactoresIndependientes, $totalKamatiActividades, $totalClienteActividades, $id_IdentificadorActividades)
    {
        try {
            session_start();
            $sessionId = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();

            // SQL para actualizar la tabla
            $sql = "UPDATE identificadoractividades SET 
                        nombreTablaActividades = ?, 
                        checkFactoresIndependientes = ?, 
                        totalKamatiActividades = ?, 
                        totalClienteActividades = ? 
                    WHERE id_IdentificadorActividades = ? AND id_cotizacionesComercial_fk = ?";

            $statement = $conn->prepare($sql);
            $statement->bind_param("siddii", $nombreTablaActividades, $checkFactoresIndependientes, $totalKamatiActividades, $totalClienteActividades, $id_IdentificadorActividades, $sessionId);

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
    public function updateFactoresIndependientesIdentificadorActividades($identificador, $idFactorIndependiente, $valorFactor)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE factoresindependientesactividades SET factorActividadesIndependienteValor = ?
            WHERE id_FactIndActividades = ? AND id_IdentificadorActividades_fk = ?";
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
    public function updateTablaActividades($data)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE tablaactividades SET 
                cantidad = ?,
                unidad = ?,
                abreviaturaLinea = ?,
                descripcionBreve = ?,
                descripcionPersonal = ?,
                cantidadPersonas = ?,
                nota = ?,
                costoExternoUnitario = ?,
                costoAlimentacion = ?,
                costoTransporte = ?,
                costoDiaKamati = ?,
                costoTotalDiasKamati = ?,
                valorDiaUtilidad = ?,
                valorTotalUtilidad = ?,
                rep = ?,
                checkActividades = ?,
                factorAdicional = ?,
                estadoButtonAlimentacion = ?,
                estadoButtonTransporte = ?,
                estadoButtonPersonal = ?
                WHERE id_TurnoActividaes_fk = ? AND id_TablaActividades = ?";

            $statement = $conn->prepare($sql);
            $statement->bind_param(
                'isissisiiiiiiisidiisii',
                $data['cantidad'],
                $data['unidad'],
                $data['abreviaturaLinea'],
                $data['descripcionBreve'],
                $data['descripcionPersonal'],
                $data['cantidadPersonas'],
                $data['nota'],
                $data['costoExternoUnitario'],
                $data['costoAlimentacion'],
                $data['costoTransporte'],
                $data['costoDiaKamati'],
                $data['costoTotalDiasKamati'],
                $data['valorDiaUtilidad'],
                $data['valorTotalUtilidad'],
                $data['rep'],
                $data['checkActividades'],
                $data['factorAdicional'],
                $data['estadoButtonAlimentacion'],
                $data['estadoButtonTransporte'],
                $data['estadoButtonPersonal'],
                $data['id_TurnoActividaes_fk'],
                $data['id_TablaActividades']
            );
            $statement->execute();

            return true;
        } catch (Exception $e) {
            error_log('Error al actualizar: ' . $e->getMessage());
            return false;
        }
    }



    function guardarFilaMaquinaria($material)
    {
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

    //Método de delete filas actividades
    public function delFilaTablaActividades($datosDelete)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "DELETE FROM tablaactividades WHERE id_TablaActividades = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $datosDelete['id_TablaActividades']);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                return json_encode(['status' => 'success', 'message' => 'Fila eliminada con éxito']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'No se encontró la fila']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar: ' . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }

    //mÉTODO de delete filas de turnos 
    public function delFilaTablaTurnosAc($datosDelete)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "DELETE FROM turnoactividades WHERE id_TurnoActividades = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $datosDelete['id_TablaTurno']);
            $statement->execute();

            if ($statement->affected_rows > 0) {
                return json_encode(['status' => 'success', 'message' => 'Fila eliminada con éxito']);
            } else {
                return json_encode(['status' => 'error', 'message' => 'No se encontró la fila']);
            }
        } catch (Exception $e) {
            error_log('Error al eliminar: ' . $e->getMessage());
            return json_encode(['status' => 'error', 'message' => 'Error al eliminar: ' . $e->getMessage()]);
        }
    }



    //Método para traer los datos de una tabla de viaticos 
    public function getDataViaticosActividadesIndependiente()
    {
        try {
            $ids = $this->consultaidIdentificadorActividades();

            if (empty($ids)) {
                return []; // Retorna un array vacío si no hay identificadores
            }

            $conn = $this->conexion->conectarBD();
            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos.");
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $sql = "SELECT 
                    id_ViaticosActividadesIndependiente, 
                    id_IdentificadorActividades_fk2, 
                    valorViaticoActividadesIndependiente
                FROM viaticosactividadesindependiente 
                WHERE id_IdentificadorActividades_fk2 IN ($placeholders)";

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
                $datos[] = $row;
            }

            $statement->close();
            $conn->close();

            return $datos;
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }

    //Método para obtener los datos de los cargos en la tabla del dom
    public function getDataCargosActividadesIndependiente()
    {
        try {
            $ids = $this->consultaidIdentificadorActividades();

            if (empty($ids)) {
                return []; // Retorna un array vacío si no hay identificadores
            }

            $conn = $this->conexion->conectarBD();
            if (!$conn) {
                throw new Exception("Error al conectar a la base de datos.");
            }

            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $sql = "SELECT 
                    id_CargosIndependientesActividades, 
                    id_IdentificadorActividades_fk3, 
                    valorCargoActividadesIndependiente
                FROM cargosindependientesactividades 
                WHERE id_IdentificadorActividades_fk3 IN ($placeholders)";

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
                $datos[] = $row;
            }

            $statement->close();
            $conn->close();

            return $datos;
        } catch (Exception $e) {
            throw new Exception("Error al consultar los datos: " . $e->getMessage());
        }
    }


    //Método de actualización de los viaticos independientes
    public function updateViaticosIndependientes($valor, $idViatico, $identificador)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE viaticosactividadesindependiente SET valorViaticoActividadesIndependiente = ? WHERE id_ViaticosActividadesIndependiente = ? AND id_IdentificadorActividades_fk2 = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valor, $idViatico, $identificador);
            if ($statement->execute()) {
                return true;
            } else {
                error_log('Error ejecutando la actualización: ' . $statement->error);
                return false;
            }
        } catch (Exception $e) {
            error_log('Error en la sentencia de actualización de los viaticos: ' . $e->getMessage());
            return false;
        } finally {
            $conn->close();
        }
    }

    public function updateCargosIndependientes($valor, $idCargo, $identificador)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE cargosindependientesactividades SET valorCargoActividadesIndependiente = ? WHERE id_CargosIndependientesActividades = ? AND id_IdentificadorActividades_fk3 = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valor, $idCargo, $identificador);
            if ($statement->execute()) {
                return true;
            } else {
                error_log('Error ejecutando la actualización: ' . $statement->error);
                return false;
            }
        } catch (Exception $e) {
            error_log('Error en la sentencia de actualización de los cargos: ' . $e->getMessage());
            return false;
        }
    }

    public function updateTurnosActividades($horaInicioTurno, $horaFinTurno, $tipoTurno, $idTurno, $identificadorTurno)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE turnoactividades SET horaInicioTurno = ?, horaFinTurno = ?, tipoTurno = ? WHERE id_TurnoActividades = ? AND id_identificadorActividades_fk = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('sssii', $horaInicioTurno, $horaFinTurno, $tipoTurno, $idTurno, $identificadorTurno);
            if ($statement->execute()) {
                return true;
            } else {
                error_log('Error ejecutando la actualización: ' . $statement->error);
                return false;
            }
        } catch (Exception $e) {
            error_log('Error en la sentencia de actualización de los turnos: ' . $e->getMessage());
            return false;
        } finally {
            $conn->close();
        }
    }
}
