<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();

require_once '../modelo/dao/ViaticosDao.php';
require_once '../modelo/HorasJornada.php';
require_once '../modelo/dao/HorasJornadaDao.php';
require_once '../modelo/Recargos.php';
require_once '../modelo/dao/RecargosDao.php';

$viaticosDao = new ViaticosDao();
$viaticos = $viaticosDao->consultarViaticos();

$horasDao = new HorasJornadaDao();
$horas1 = new HorasJornada();
$horas2 = new HorasJornada();
$horas3 = new HorasJornada();
$horas4 = new HorasJornada();

$horas1 = $horasDao->consultaHorasJornadaSin(1);
$horas2 = $horasDao->consultaHorasJornadaSin(2);
$horas3 = $horasDao->consultaHorasJornadaSin(3);
$horas4 = $horasDao->consultaHorasJornadaSin(4);

$recargosDao = new RecargosDao();
$recargos1 = new Recargos();
$recargos2 = new Recargos();
$recargos3 = new Recargos();
$recargos4 = new Recargos();
$recargos5 = new Recargos();
$recargos6 = new Recargos();
$recargos7 = new Recargos();

$recargos1 = $recargosDao->consultarRecargosIdnew(1);
$recargos2 = $recargosDao->consultarRecargosIdnew(2);
$recargos3 = $recargosDao->consultarRecargosIdnew(3);
$recargos4 = $recargosDao->consultarRecargosIdnew(4);
$recargos5 = $recargosDao->consultarRecargosIdnew(5);
$recargos6 = $recargosDao->consultarRecargosIdnew(6);
$recargos7 = $recargosDao->consultarRecargosIdnew(7);




include '../modelo/dao/AbreviaturasDao.php';
include '../modelo/dao/ProveedorDao.php';
include '../modelo/dao/CargoCotizacionDao.php';
require_once '../modelo/dao/FactoresDao.php';
require_once '../modelo/Factores.php';

$abreviaturaDao = new AbreviaturasDao();
$nombreAb = $abreviaturaDao->consultarAbreviaturas();

$proveedor = new ProveedorDao();
$nombreProv = $proveedor->consultarProveedores();

$cargoCotizacion = new CargoCotizacionDao();
$cargoCotNombre = $cargoCotizacion->consultarCargoCotizacion();

$cargosValores = $cargoCotizacion->consultarValorDiaCargo();

$factoresDao = new FactoresDao();
$factor1 = new Factores();
$factor2 = new Factores();
$factor3 = new Factores();
$factor4 = new Factores();

$factor1 = $factoresDao->consultarFactores(1);
$factor2 = $factoresDao->consultarFactores(2);
$factor3 = $factoresDao->consultarFactores(3);
$factor4 = $factoresDao->consultarFactores(4);

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styleKamaIntrNosws.css">
    <link rel="stylesheet" href="../css/style_buttonX.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        // Este script pasará el ID de cotización de la sesión a sessionStorage
        <?php

        // Asegúrate de que la sesión tenga el id_cotizacion
        if (isset($_SESSION['id_cotizacion'])) {
            echo "sessionStorage.setItem('id_cotizacion', " . json_encode($_SESSION['id_cotizacion']) . ");";
        } else {
            echo "sessionStorage.setItem('id_cotizacion', null);";
        }
        ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js" crossorigin="anonymous">
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/JavaScriptSolicitudCol.js" defer></script>
    <script src="../js/seleccionTablasCotizacioness.js" defer></script>
    <script src="../js/modalConfirmacionTablass.js" defer></script>
    <script type="module" src="../jsServer/mainServer.js"></script>
    <script src="../jsServerExcel/generateExcel.js" defer></script>
    <!-- <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>                           -->


    <style>
        .tabla-horizontal {
            display: flex;
            flex-direction: column;
        }

        .fila-horizontal {
            display: flex;
            flex-direction: row;
        }

        .fila-horizontal .celda {
            padding: 10px;
            border: 1px solid #ccc;
        }

        .stacked-form {
            max-width: 350px;
            margin-left: 3%;
        }

        .tm {
            max-width: 150px;
            margin-left: 0%;
        }

        .fl {
            flex: 1;
        }

        .form-fields {
            max-width: 300px;

        }

        .select_per {
            max-width: 200px;

        }

        .div_nuevo {
            margin-left: 3%;
            margin-bottom: 20px;
        }

        .color_td {
            background-color: rgb(238, 238, 228);
        }

        .hidden {
            display: none;
        }

        .select-input-container {
            display: flex;
            align-items: center;
        }

        .select-input-container_new {
            display: flex;
            align-items: center;
        }

        .input-toggle-container {
            display: flex;
            align-items: center;
        }

        .option-input {
            margin-right: 5px;
        }

        .option-input_Ac {
            margin-right: 5px;
        }
    </style>
    <title>Cotizaciones Kamati</title>
</head>

<body>

    <script type="module" src="../js/editModal.js"> </script>

    <button id="backToTopButton" title="Vuelve arriba">
        <i class="fas fa-arrow-up"></i>
    </button>
    <?php

    ?>
    <div id="alertContainer"></div>
    <div id="alertContainerPermiso"></div>
    <br><br><br><br><br><br>

    <?php include '../configuracion/Fecha.php'; ?>
    <div class="h2_separado">
        <h2>Cotizaciones KAMATI</h2>
    </div>
    <br><br>
    <div class="div_genera_Excel">
    <button onclick="obtenerDatos()" class="excel-button">
        <i class="fas fa-file-excel"></i> Generar Excel
    </button>
</div>
    <h2></h2>
    <form method="POST" id="formularioCotizacion">
        <div class="stacked-form">
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-floating">
                        <input type="date" class="form-control limpiar  dom-elementS" name="FechaActualCotizacion" id="fechaActual"
                            placeholder="" value="<?php echo $fechaActual; ?>">
                        <label for="FechaActualCotizacion">Fecha actual (DD/MM/AAAA)</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control limpiar dom-elementS" name="nombreClienteCotizacion" id="nombreClienteCotizacionId"
                            placeholder="">
                        <label for="nombreClienteCotizacion">Nombre del Cliente</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control limpiar dom-elementS" name="nombreProyectoCotizacion" id="nombreProyectoCotizacionId"
                            placeholder="">
                        <label for="nombreProyectoCotizacion">Nombre del proyecto</label>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-floating">
                        <input type="text" class="form-control limpiar dom-elementS" name="codigoProyectoCotizacion" id="codigoProyectoCotizacionId"
                            placeholder="">
                        <label for="codigoProyectoCotizacion">Código del proyecto</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="tmr_personlizado">
            <div class="h2_nuevoproyect_unique">
                <h2>Nuevo proyecto</h2>

            </div>
            <div class="row g-2">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" value="" placeholder=""
                            readonly>
                        <label for="txt_nombre">TRM USD -> COP</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_identificacion" id="txt_identificacion" value=""
                            placeholder="" readonly>
                        <label for="txt_identificacion">TRM EUR -> COP</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control numberInput dom-elementS" name="txt_identificacion_usd1"
                            id="txt_identificacion_usd" value="" placeholder="">
                        <label for="floatingInput">Dolar a asignar al proyecto</label>
                    </div>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control numberInput dom-elementS" name="txt_identificacion_eur1"
                            id="txt_identificacion_eur" value="" placeholder="">
                        <label for="floatingInput">Euro a asignar al proyecto</label>
                    </div>
                </div>
            </div>
            <!-- EN ESTE LUGAR VA EL CÓDIGO DE LOS TRM  -->
             <?php
                // Clave API de Open Exchange Rates (reemplaza con tu propia clave)
                $app_id = 'd4c51c54592d4989b7a274f1d5fafcc2';

                // URL de la API de Open Exchange Rates para obtener las tasas de cambio
                $url = "https://openexchangerates.org/api/latest.json?app_id=$app_id";

                // Inicializar cURL
                $ch = curl_init();

                // Configurar la solicitud cURL
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Ejecutar la solicitud cURL
                $response = curl_exec($ch);

                // Verificar si hubo algún error
                if (curl_error($ch)) {
                    echo 'Error:' . curl_error($ch);
                }

                // Cerrar la sesión cURL
                curl_close($ch);

                // Decodificar la respuesta JSON
                $data = json_decode($response, true);

                // Verificar si se obtuvieron datos válidos
                if (isset($data['rates'])) {
                    $rates = $data['rates'];

                    // Obtener la tasa de conversión de USD a COP y formatearla
                    $usd_to_cop = number_format($rates['COP'] / $rates['USD'], 2);

                    // Obtener la tasa de conversión de EUR a COP y formatearla
                    $eur_to_cop = number_format($rates['COP'] / $rates['EUR'], 2);

                    // Mostrar los valores en los inputs
                    echo "<script>
                    document.getElementById('txt_nombre').value = '$usd_to_cop';
                    document.getElementById('txt_identificacion').value = '$eur_to_cop';
                  </script>";
                } else {
                    echo 'Error al obtener los datos de la API.';
                }
                ?>
            <div class="0"></div>
        </div>

    </form>
    <br><br>
    <div class="contenedor">
        <div class="div_nuevo">
            <label for="select_tipo_tabla">Seleccione el tipo de tabla</label>
            <select class="form-select select_per" name="select_tipo_tabla" id="select_tipo_tabla">
                <option value="1">Materiales</option>
                <option value="2">Actividades</option>
                <option value="3">Maquinaria</option>
            </select>
        </div>
        <div class="modal fade fixed_modal_kam" id="modalConfirmacion" tabindex="-1" role="dialog"
            aria-labelledby="modalConfirmacionLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalConfirmacionLabel">Confirmar cambio de tabla</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Tienes datos en tu tabla, ¿estás seguro de cambiar tu tabla actual?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            onclick="cancelarCambioTabla()">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="confirmarCambioTabla()">Confirmar
                            cambio</button>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="d-flex">


            <!-- Factor Mo -->
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_FactorMoGlobal" id="factorMoGlobal"
                    placeholder="Factor Mo">
                <input type="hidden" id="hidden_InputFactorGlobalNameMo" value="">
                <label for="factorMoGlobal">Factor Mo</label>
            </div>

            <!-- Factor O -->
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_FactorOGlobal" id="factorOGlobal"
                    placeholder="Factor O">
                <input type="hidden" id="hidden_InputFactorGlobalNameO" value="">
                <label for="factorOGlobal">Factor O</label>
            </div>

            <!-- Viáticos -->
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_ViaticosGlobal" id="viaticosGlobal"
                    placeholder="Viáticos">
                <input type="hidden" id="hidden_InputFactorGlobalNameV" value="">
                <label for="viaticosGlobal">Viáticos</label>
            </div>

            <!-- Póliza -->
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_PolizaGlobal" id="polizaGlobal"
                    placeholder="Póliza">
                <input type="hidden" id="hidden_InputFactorGlobalNameP" value="">
                <label for="polizaGlobal">Póliza</label>
            </div>

            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_siemensGlobal" id="siemensGlobal" placeholder="" value="">
                <input type="hidden" id="id_inputHidden_FactoresAdicionalesSiemens" value="">
                <label for="siemensGlobal">Siemens</label>
            </div>
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_pilzGlobal" id="pilzGlobal" placeholder="" value="">
                <input type="hidden" id="id_inputHidden_FactoresAdicionalesPilz" value="">
                <label for="pilzGlobal">Pilz</label>
            </div>
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_rittalGlobal" id="rittalGlobal" placeholder="" value="">
                <input type="hidden" id="id_inputHidden_FactoresAdicionalesRittal" value="">
                <label for="rittalGlobal">Rittal</label>
            </div>
            <div class="form-floating nows_class">
                <input type="text" class="form-control no-cambiar" style="width: 80px; margin: 0px 2.5px 0px 2.5px;"
                    name="txt_phoenixGlobal" id="phoenixGlobal" placeholder="" value="">
                <input type="hidden" id="id_inputHidden_FactoresAdicionalesPhxContact" value="">
                <label for="phoenixGlobal">PhxCnt</label>
            </div>
            <div class="new_conatinet" id="id_content_viaticosGlobal">
                <div class="new_tabla_solicitudes" id="id_new_tabla_viaticosGlobal">
                    <!-- Tabla de Viáticos -->
                    <table class="table-responsive" id="table_viaticos_acGlobal">
                        <thead>
                            <tr class="tr_class">
                                <th scope="col">Viáticos</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_viaticos_acGlobal">
                            <!-- Aquí se insertan dinámicamente las filas usando PHP -->
                            <?php foreach ($viaticos as $index => $row) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['nombre_viatico']); ?></td>
                                    <td>
                                        <div class="span_div">
                                            <span class="span_trm" style="width: 15px;">$</span>
                                            <input type="text" name="valor_diarioGlobal" class="valor-input dom-element claseEditModal"
                                                id="valorDiarioGlobalViaticos_<?php echo $index; ?>"
                                                style="width: 70px;"
                                                value="<?php echo number_format($row['valor_diario'], 0, '', '.'); ?>" readonly>
                                            <input type="hidden" name="" class="id_hidden_viaticos" id="id_hidden_viaticos_<?php echo $index; ?>" value="">
                                        </div>
                                    </td>
                                    <td>
                                        <button class="edit-btn" onclick="editRow(this)">
                                            <span class="fa fa-pencil-alt"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
           

            <div class="new_conatinet" id="table_cargos_acGlobal">
                <div class="new_tabla_solicitudes" id="cargos_table_acGlobal">
                    <table class="table-responsive" id="cargo_table_acsGlobal">
                        <thead>
                            <tr class="tr_class">
                                <th scope="col">Cargos</th>
                                <th scope="col">Valor día</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tbody_ac_tableGlobal">
                            <?php foreach ($cargosValores as $index => $row) : ?>
                                <tr>
                                    <td>
                                        <?php echo htmlspecialchars($row['nombre_cargo_cotizacion']); ?>

                                    </td>
                                    <td>
                                        <div class="span_div">
                                            <span class="span_trm" style="width: 15px;">$</span>
                                            <input type="text" name="tarifa_cargoGlobal" class="valor-input tarifa_CargoGlobalClass1 dom-element claseEditModal"
                                                id="valorDiarioGlobalCargo_<?php echo $index; ?>"
                                                style="width: 70px;"
                                                value="<?php echo number_format($row['tarifa_cargo'], 0, '', '.'); ?>"
                                                readonly>
                                            <input type="hidden" name="" class="hidden_input_cargosCotizacionGlobal" id="id_hidden_cargo_<?php echo $index; ?>" value="">
                                        </div>
                                    </td>
                                    <td>
                                        <button class="edit-btn" onclick="editRow(this)">
                                            <span class="fa fa-pencil-alt"></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABLA DE MATERIALES -->

        <div id="tablaMateriales" class="tabla-container table_materialesClas clone-container" style="display:block;">

            <div class="h2_separado">
                <h2>Materiales</h2>
                <input type="hidden" class="txt_id_identificador_Materiales" value="">
            </div>
            <div class="h2_separado hidden_table">
                <input type="checkbox" name="hidden_table_mat" id="id_hidden_table_mat" class="hidden_checkBoxMaterialesClass checkClienteClass" value="0">
                <input type="hidden" name="hiddenInputFactoresIndependientes" class="hiddenInputFactoresIndependientesClas" id="hiddenInputFactoresIndependientesId" value="noCheck">
                <input type="hidden" name="hiddenInputFactoresIndependientes" class="hiddenInputFactoresIndependientesClasess" id="hiddenInputFactoresIndependientesIdUno" value=" ">
                <label for="hidden_table_mat">Selecciona para factores independientes</label>
            </div>

            <div class="h2_separado">
                <h4>Nombre de la tabla</h4>
                <br>
                <input type="text" class="form-control nombre_table-materialesClass" name="nombre_materiales" id="nombre_materialesMats"
                    style="width: 30%;">
                <div id="divHiddenMateriales" class="divHiddenMaterialesClass" hidden>
                    <input type="hidden" name="inputHiddenTablaDivMateriales" id="inputHiddenTablaDivMaterialesId">
                    <div class="d-flex">
                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_MoClassMateriales dom-element" name="txt_factor_mo1"
                                id="txt_factor_moHidden" placeholder="" value="<?php echo $factor1['valorFactor']; ?>">
                            <input type="hidden" name="txtId" class="class_hidden_factorMo_materiales" value="">
                            <label for="txt_factor_mo">Factor Mo</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_OClassMateriales dom-element" name="txt_factor_o1" id="txt_factor_oHidden"
                                placeholder="" value=<?php echo $factor2['valorFactor']; ?>>
                            <input type="hidden" name="txtId" class="class_hidden_factorO_materiales" value="">
                            <label for="numeroIdentificacion">Factor O</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_VClassMateriales dom-element" name="txt_factor_V1" id="txt_factor_VHidden"
                                placeholder="" value=<?php echo $factor3['valorFactor']; ?>>
                            <input type="hidden" name="txtId" class="class_hidden_factorPo_materiales" value="">
                            <label for="numeroIdentificacion">Viáticos</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_polizaClassMateriales polizaCliente dom-element" name="txt_poliza1"
                                id="txt_polizaHidden" placeholder="" value=<?php echo $factor4['valorFactor']; ?>>
                            <input type="hidden" name="txtId" class="class_hidden_factorVi_materiales" value="">
                            <label for="txt_poliza">Póliza</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_siemensClassMateriales dom-element" name="txt_siemens1"
                                id="txt_siemensHidden" placeholder="" value="">
                            <input type="hidden" name="" class="class_hidden_Factor_Simateriales">
                            <label for="correoElectronico">Siemens</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_pilzClassMateriales dom-element" name="txt_pilz1" id="txt_pilzHidden" placeholder=""
                                value="">
                            <input type="hidden" name="" class="class_hidden_Factor_Pimateriales">
                            <label for="correoElectronico">Pilz</label>
                        </div>

                        <div class="form-floating">
                            <input type="text" class="form-control limpiar factor_rittalClassMateriales dom-element" name="txt_rittal1" id="txt_rittalHidden"
                                placeholder="" value="">
                            <input type="hidden" name="" class="class_hidden_Factor_Rimateriales">
                            <label for="correoElectronico">Rittal</label>
                        </div>


                        <div class="form-floating">
                            <input type="email" class="form-control limpiar factor_phoenixcontactClassMateriales dom-element" name="txt_phoenix1" id="txt_phoenixHidden"
                                placeholder="" value="">
                            <input type="hidden" name="" class="class_hidden_Factor_Pcmateriales">
                            <label for="correoElectronico">Phoenix Contact</label>
                        </div>
                    </div>




                </div>
            </div>

            <div class="tabla_cotizacion cloneable">
                <br>
                <table class="table-responsive table_materialesClass table_original_materiales_class_unique" id="miTablaMovible">
                    <thead>
                        <tr class="tr_class classNormal_uniqueMaterial">
                            <th scope="col">Cantidad</th>
                            <th scope="col">Unid</th>
                            <th scope="col">Abreviatura Línea</th>
                            <th scope="col">Referencia</th>
                            <th scope="col">Material</th>
                            <th scope="col">Descripción Material</th>
                            <th scope="col">Proveedor</th>
                            <th scope="col">Nota</th>
                            <th scope="col">Tipo Moneda</th>
                            <th scope="col">Precio lista</th>
                            <th scope="col">Costo Unitario Kamati</th>
                            <th scope="col">Costo total kamati</th>
                            <th scope="col">Valor utilidad</th>
                            <th scope="col">Valor total</th>
                            <th scope="col">T. Entrega</th>
                            <th scope="col">Descuento(%)</th>
                            <th scope="col">Descuento Adicional(%)</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Rep</th>
                            <th scope="col">Check</th>
                            <th scope="col">Fac ad</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="tbody_materialesClas">

                        <tr id="baseRow" class="cloneable trClassMateriales">
                            <td>
                                <textarea rows="1" cols="10" class="cantidad-material numberInput dom-element materialescantidadTable"></textarea>
                                <input type="hidden" name="" class="id_fila_materialesTable_class">
                                <input type="hidden" name="" class="id_fila_materialesTable_class_clonedUpdate">
                            </td>
                            <td>
                                <select name="select_unidades" class="select_reset select_unidades_materiales_table_class">
                                    <option value="Mts">Mts</option>
                                    <option value="Días">Días</option>
                                    <option value="Horas">Horas</option>
                                    <option value="Glb">Glb</option>
                                    <option value="Unidad">Unidad</option>
                                    <option value="Turn">Turn</option>
                                </select>
                            </td>
                            <td>
                                <select class="abreviatura-lista select_reset select_abreviatura_materiales_class" name="id_Abreviaturas_nom">
                                    <?php foreach ($nombreAb as $id => $nombre) : ?>
                                        <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </td>

                            <td><textarea rows="1" cols="30" class="textarea_referencia_materiales_class"></textarea></td>
                            <td><textarea rows="1" cols="30" class="textarea_material_class"></textarea></td>
                            <td><textarea rows="1" cols="40" class="textarea_descripcionMaterial_class"></textarea></td>
                            <td>
                                <div class="select-input-container contenedor_class_uniqueSelectMateriales">
                                    <select class="option-select select_reset select_proveedor_materiales_class" name="select_proveedor"
                                        id="select_proveedor">
                                        <?php foreach ($nombreProv as $idpv => $nombrepv) : ?>
                                            <option value="<?php echo htmlspecialchars($idpv); ?>">
                                                <?php echo htmlspecialchars($nombrepv); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" class="option-input input_proveedor_materiales_class hidden" placeholder="Escribe una opción">
                                    <input type="hidden" class="option-input input_proveedor_materiales_class_hiddenUnique" placeholder="Escribe una opción">
                                    <button type="button" class="toggle-btn lapiz_button_materiales">✎</button>
                                </div>
                            </td>
                            <td><textarea rows="1" class="textarea_nota_materiales_class"></textarea></td>
                            <td>
                                <select class="select_trm_nu selet_trm_materiales_class select_reset" name="select_trm" id="select_trm">
                                    <option value="COP">COP</option>
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                </select>
                            </td>
                            <td>
                                <div class="span_div">
                                    <span class="span_trm" id="span_trm">$</span>
                                    <textarea rows="1" class="precio-lista-input precio_lista_input_class_materiales numberInput1"
                                        name="precio_lista"></textarea>
                                </div>
                            </td>
                            <td>
                                <div class="span_div">
                                    <textarea rows="1" class="cost-kamati-input cost-kamati-input_class_materiales numberInput"
                                        name="costo_kamati_unitario" readonly></textarea>
                                    <span class="span_cop">COP</span>
                                </div>
                            </td>
                            <td>
                                <div class="span_div">
                                    <textarea rows="1" class="cost-kamati-total cost-kamati-total_class_materiales numberInput" readonly></textarea>
                                    <span class="span_cop">COP</span>
                                </div>
                            </td>
                            <td>
                                <div class="span_div">
                                    <textarea rows="1" class="valor-utilidad valor-utilidad_class_materiales numberInput" readonly></textarea>
                                    <span class="span_cop">COP</span>
                                </div>
                            </td>
                            <td>
                                <div class="span_div">
                                    <textarea rows="1" class="value-total-input value-total-input_class_materiales numberInput" readonly></textarea>
                                    <span class="span_cop">COP</span>
                                </div>
                            </td>
                            <td><textarea rows="1" cols="13" class="valor_tiempo_entrega_class_materialesa" id="valor_tiempo_entrega"></textarea></td>

                            <td><textarea rows="1" cols="10" class="descuento-input descuento-input_materiales_class"></textarea></td>
                            <td><textarea rows="1" cols="10" class="descuento-adicional-input descuento-adicional-input_materiales_class "></textarea></td>
                            <td><input type="date" class="date_input_entrega date_input_entrega_class_materiales" id="idfecha_tiempo_entrega"></td>
                            <td>
                                <select name="select_rep select_reset" class="select_rep_classMateriales" id="select_rep">
                                    <option value="Revisar">Revisar</option>
                                    <option value="Lista">Listo</option>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" name="checkBoxk_newFactor" id="checkBox_newFactorId" class="checkBoxk_newFactorClass check_estado_class_materiales">
                                <input type="hidden" class="hidden_estado_unique_checkBox">
                            </td>
                            <td>
                                <input type="text" name="inputFactorNewMateriales" id="inputIdNewFactor" class="inputNewFactorClas factor_adicional_class_materiales" disabled>
                                <input type="hidden" name="inputHiddenNewFactorName" class="inputHiddenNewFactorClas" id="inputHiddenNewFactorId" value="false">
                            </td>
                            <td><button class="delete-btn"><span class="icon">&times;</span></button></td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <form id="formGuardarTabla">
                <div class="div_nuevo d-flex">
                    <div class="separacion">
                        <div class="form-floating">
                            <button type="button" name="button_nueva_fila_materiales" class="nueva_fila"
                                title="Agrega una nueva fila" id="button_agregar_fila">Agregar Fila</button>
                        </div>
                    </div>
                    <div class="separacion">
                        <div class="form-floating">
                            <button type="button" name="button_guardar_tabla_materiales" class="nueva_fila" id="button_guardar_tabla_materialesId"
                                title="Guarda una nueva tabla" value="GuardarTable">Guardar Tabla</button>
                        </div>
                    </div>
                    <div class="separacion">
                        <label for="Total Kamati">Total Kamati</label>
                        <div class="form-floating">
                            <div class="span_div">
                                <input type="text" name="txt_total_kamati" class="txtTotalKamatiMateriales txtTotalKamatiMaterialesClass" id="txtTotalKamati_materiales" readonly>
                                <span class="span_cop">COP</span>
                            </div>
                        </div>
                    </div>
                    <div class="separacion">
                        <label for="Total Cliente">Total Cliente</label>
                        <div class="form-floating">
                            <div class="span_div">
                                <input type="text" name="txt_total_cliente" class="txtTotalClienteMateriales txtTotalClienteMaterialesClass" id="txtTotalCliente_materiales" readonly>
                                <span class="span_cop">COP</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal fade fixed_modal_kam" id="deleteModalMateriales" tabindex="-1" aria-labelledby="deleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Se borrará esta fila y tal vez tenga datos importantes, ¿Estás seguro de borrarla?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteModalMateriales">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de botónes para las tablas al editar los valores de las filas -->

        <div class="modal fade fixed_modal_kam" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Confirmar acción</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modalText">
                        <!-- Texto dinámico según la acción -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelEdit"
                            data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirmEdit1">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>



        <script type="module" src="../js/mainMaterialesElectricos.js"></script>

        <br><br><br>

        <!-- TABLA DE ACTIVIDADES -->
        <!-- 
        
        

        ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES * 
        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES * 
        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  
        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES * 
        
        
        
         -->


        <div>
            <input type="time" name="JornadaDiurnaInicio" id="JornadaDiurnaInicioId" value="<?php echo $horas1['horaJornada']; ?>" hidden>
            <input type="time" name="JornadaDiurnaFin" id="JornadaDiurnaFinId" value="<?php echo $horas2['horaJornada']; ?>" hidden>
            <input type="time" name="JornadaNocturnaInicio" id="JornadaNocturnaInicioId" value="<?php echo $horas3['horaJornada']; ?>" hidden>
            <input type="time" name="JornadaNocturnaFin" id="JornadaNocturnaFinId" value="<?php echo $horas4['horaJornada']; ?>" hidden>

            <input type="text" name="recargos1" id="recargos1Id" value="<?php echo $recargos1['valorRecargo'] ?>" hidden>
            <input type="text" name="recargos2" id="recargos2Id" value="<?php echo $recargos2['valorRecargo'] ?>" hidden>
            <input type="text" name="recargos3" id="recargos3Id" value="<?php echo $recargos3['valorRecargo'] ?>" hidden>
            <input type="text" name="recargos4" id="recargos4Id" value="<?php echo $recargos4['valorRecargo'] ?>" hidden>
            <input type="text" name="recargos5" id="recargos5Id" value="<?php echo $recargos5['valorRecargo'] ?>" hidden>
            <input type="text" name="recargos6" id="recargos6Id" value="<?php echo $recargos6['valorRecargo'] ?>" hidden>
            <input type="text" name="recargos7" id="recargos7Id" value="<?php echo $recargos7['valorRecargo'] ?>" hidden>
        </div>



        <div id="tablaActividades" class="tabla-container table-actividades-Class-container">
            <div class="h2_separado">
                <h2>Actividades</h2>
                <input type="hidden" class="txt_id_identificador_Actividades">
            </div>
            <br>
            <div class="h2_separado hidden_table">
                <input type="checkbox" name="hidden_table_mat checkClienteClass" id="toggleDiv" class="hiddenTable-actividades">
                <input type="hidden" name="hidden_input_div_hidden" class="hiddenTableInput-actividades" id="inputHiddenDivHiddenid" value="noCheck">
                <input type="hidden" class="hiddenTableInput_actividades_unqueVal" id="hiddenInputFactoresIndependientesActividadesUno" value="">
                <label for="hidden_table_mat">Selecciona para factores independientes</label>
            </div>
            <br>
            <div class="d-flex">
                <div class="h2_separado" style="width: 30%;">
                    <h4>Nombre de la tabla</h4>
                    <input type="text" class="form-control nombre_table-actividadesClass" name="nombre_actividades" id="nombreTablaActividades" style="width: 90%;">
                </div>

                <!-- DIV HIDDEN PARA LOS FACTORES Y TARIFAS -->
                <div class="hidden_ac_div" id="div_with_hidden" hidden>

                    <div class="form-floating nows_class">
                        <input type="text" class="form-control factorMoHiddenClass" style="width: 100px;" name="txt_FactorMoAc" id="factorMoAcHidden"
                            placeholder="" value=<?php echo $factor1['valorFactor']; ?>>
                        <input type="hidden" name="" class="class_hidden_factorMo_ActividadesUnique" id="">
                        <label for="segundoApellido">Factor Mo</label>
                    </div>


                    <div class="form-floating nows_class">
                        <input type="text" class="form-control factorOHiddenClass" style="width: 100px;" name="txt_FactorOAc" id="factorOAcHidden"
                            placeholder="" value=<?php echo $factor2['valorFactor']; ?>>
                        <input type="hidden" name="" class="class_hidden_factorO_ActividadesUnique" id="">
                        <label for="numeroIdentificacion">Factor O</label>
                    </div>


                    <div class="form-floating nows_class">
                        <input type="email" class="form-control viaticosHiddenClass" style="width: 100px;" name="txt_ViaticosAc" id="viaticosAcHidden"
                            placeholder="" value=<?php echo $factor3['valorFactor']; ?>>
                        <input type="hidden" name="" class="class_hidden_factorVi_ActividadesUnique" id="">
                        <label for="correoElectronico">Viaticos</label>
                    </div>
                    <div class="form-floating nows_class">
                        <input type="email" class="form-control polizaHiddenClaas polizaCliente" style="width: 100px;" name="txt_PolizaAc" id="polizaAcHidden"
                            placeholder="" value=<?php echo $factor4['valorFactor']; ?>>
                        <input type="hidden" name="" class="class_hidden_factorPo_ActividadesUnique" id="">
                        <label for="correoElectronico">Póliza</label>
                    </div>

                    <div class="new_conatinet" id="id_content_viaticos">
                        <div class="new_tabla_solicitudes" id="id_new_tabla_viaticos">
                            <table class="table-responsive tableViaticosHidden-ClassActividades" id="table_viaticos_acHidden">
                                <thead>
                                    <tr class="tr_class">
                                        <th scope="col">Viáticos</th>
                                        <th scope="col">Valor</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_viaticos_acHidden" class="class-viaticosHidden">
                                    <?php foreach ($viaticos as $index => $row) : ?>
                                        <tr class="filasViaticos_tr_class_unique_ac">
                                            <td>
                                                <?php echo htmlspecialchars($row['nombre_viatico']); ?>
                                                <input type="hidden" class="nombreViaticoActividadesUnique" value="<?php echo htmlspecialchars($row['nombre_viatico']); ?>">
                                            </td>
                                            <td>
                                                <div class="span_div">
                                                    <span class="span_trm" style="width: 15px;">$</span>
                                                    <input type="text" name="valor_diario" class="valor-input valorActividadesViaticosUnique claseEditModal" id="valor_diario_idHidden"
                                                        style="width: 70px;"
                                                        value="<?php echo number_format($row['valor_diario'], 0, '', '.'); ?>"
                                                        readonly>
                                                    <input type="hidden" class="hidden_identificador_tabla_unique_<?php echo $index; ?> identificadorUpdateacUnique" id="" value>
                                                    <input type="hidden" class="hidden_inputId_viaticos_unique_<?php echo $index; ?> iDUpdateacUnique" id="" value>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="edit-btn" onclick="editRow(this)">
                                                    <span class="fa fa-pencil-alt"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="new_conatinet" id="table_cargos_ac">
                        <div class="new_tabla_solicitudes" id="cargos_table_ac">
                            <table class="table-responsive hiddenTableInput-actividades hiddenInputTableCargoClassUnique" id="cargo_table_acsHidden">
                                <thead>
                                    <tr class="tr_class">
                                        <th scope="col">Cargos</th>
                                        <th scope="col">Valor día</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_ac_tableHidden" class="tbody_actividades-hiddenClass">
                                    <?php foreach ($cargosValores as $index => $row) : ?>
                                        <tr class="tr_cargo_class_ac_unique">
                                            <td>
                                                <?php echo htmlspecialchars($row['nombre_cargo_cotizacion']); ?>
                                                <input type="hidden" class="nombreUniqueCargoClass" value="<?php echo htmlspecialchars($row['nombre_cargo_cotizacion']); ?>">
                                            </td>
                                            <td>
                                                <div class="span_div">
                                                    <span class="span_trm" style="width: 15px;">$</span>
                                                    <input type="text" name="tarifa_cargoGlobal" class="valor-input tarifa_CargoGlobalClass1 valorTarigaCargoUniqueClass claseEditModal" id="id_tarifa_cargo"
                                                        style="width: 70px;"
                                                        value="<?php echo number_format($row['tarifa_cargo'], 0, '', '.'); ?>"
                                                        readonly>
                                                    <input type="hidden" class="hidden_input_unique_tarifaCargo_<?php echo $index; ?> class_inputHidden_unique_identificadorCa" id="" value>
                                                    <input type="hidden" class="hidden_input_unique_tarifaCargoId_<?php echo $index; ?> class_inputHidden_unique_iD_Ca" id="" value>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="edit-btn" onclick="editRow(this)">
                                                    <span class="fa fa-pencil-alt"></span>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tabla_cotizacion">
                <br>
                <table class="table-responsive" class="tableActividades_clas table_original_actividades_class_unique" id="miTablaMovible1">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Cant</th>
                            <th scope="col">Unid</th>
                            <th scope="col">Abrv Línea</th>
                            <th scope="col">Desc Breve</th>
                            <th scope="col">Desc Personal</th>
                            <th scope="col">Cant. Personas</th>
                            <th scope="col">Nota</th>
                            <th scope="col">Costo externo unitario</th>
                            <th scope="col">Costo Alimentación</th>
                            <th scope="col">Costo Transporte</th>
                            <th scope="col">Costo Día Kam</th>
                            <th scope="col">Costo total días Kam</th>
                            <th scope="col">Valor día utilidad</th>
                            <th scope="col">Valor total días Utilidad</th>
                            <th scope="col">Rep</th>
                            <th scope="col">Check</th>
                            <th scope="col">Fac Ad</th>
                            <th scope="col">Acción</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody" class="tbodyActividades_Clas">

                        <tr class="no-mover tr_new_tbody_turnounique_Class">

                            <td class="color_td">
                                <label for="hora_inicio_turno">Hora inicio</label>
                                <input type="time" name="hora_inicio_turno" id="startTime" class="starTimeClassActividades">
                                <input type="hidden" class="hidden_idIdentificadorActividadeUnique_CLASS">
                                <input type="hidden" class="hidden_idId_turno_ActividadeUnique_CLASS">
                            </td>
                            <td class="color_td">
                                <label for="hora_fin_turno">Hora final</label>
                                <input type="time" name="hora_fin_turno" id="endTime" class="endTimeClassActividades">
                            </td>
                            <td class="color_td">
                                <label for="tipo_dia">Tipo de turno</label>
                                <select name="tipo_dia" class="select_res tipoDia-classActividades" id="miSelectId">
                                    <option value="Dominical">Dominical</option>
                                    <option value="Dia_semana">Entre semana</option>
                                </select>
                            </td>
                            <td class="color_td"><button class="delete-btn color_td borrar_turno_Ac_class"><span class="icon">&times;</span></button></td>
                            <td colspan="15" class="color_td"></td>
                        </tr>

                        <tr id="fila-clonable" class="tr_clasUnique_camposActividades filaclonableunica_actividades_Class">
                            <td>
                                <textarea rows="1" class="valor-input cant-input now_juis_class cantidad_actividades_unique" id="now_juis"></textarea>
                                <input type="hidden" class="class_hidden_identificador_uniqueAc">
                                <input type="hidden" class="class_hidden_id_uniqueAc">
                            </td>
                            <td>
                                <select name="select_unidadesAc" id="select_Ac_unid" class="select_res selectUnidadesActividadesClass">
                                    <option value="Dias">Días</option>
                                    <option value="Horas">Horas</option>
                                    <option value="Turn">Turn</option>
                                </select>
                            </td>
                            <td>
                                <select class="select_res abreviaturas_nomClass" name="abreviaturas_nom" id="id_Abreviaturas_nom">
                                    <?php foreach ($nombreAb as $id => $nombre) : ?>
                                        <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                    <?php endforeach; ?>
                                </select>

                            </td>
                            <td><textarea rows="1" class="descripcion_breve_classUnique" id="descripcion_breve_id"></textarea></td>
                            <td>
                                <div class="select-input-container_new">
                                    <select class="select_res select_hidden select-nombreCotizacionesActividades-Class" name="select_nombreCotizaciones"
                                        id="select_nombreCotizaciones">
                                        <?php foreach ($cargoCotNombre as $id => $nombreC) : ?>
                                            <option value="<?php echo $nombreC; ?>"><?php echo $nombreC; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" class="option-input_Ac proveedor_input_classUnique hidden" name="input_hidden_externo" id="input_hidden_externoId" style="width: 170px;"
                                        placeholder="Escribe una opción">
                                    <button type="button" class="toggle-btn_new" id="button_externo">✎</button>
                                    <input type="hidden" class="mantener-valor inputValor-optionActividadesClass" id="hidden_input_cargosDesc" name="input_hidden_externo1" value="false" data-original-value="false">
                                    <input type="hidden" class="hidden_unique_valor_cloned_personal">
                                </div>
                            </td>

                            <td><textarea rows="1" class="valor-input cantidad_persona_class_unique"></textarea></td>
                            <td><textarea rows="1" cols="30" class="nota_actividades_unique_class"></textarea></td><!--NOTA-->
                            <td><textarea rows="1" class="costo-externio-unitario-input valor-input" name="textArea_externo" id="textAreaExternoCosto" disabled></textarea></td>
                            <td>
                                <div class="input-toggle-container">
                                    <textarea rows="1" class="costo-alimentacion valor-input costoAlimentacion_input_actividades_unique_class" id="textarea_costo_alt"></textarea>
                                    <input type="hidden" class="hidden_alimentacion costoAlimentacion_hidden_uniqueclass_estadoButton" id="hidden_alimentacionId" value="1">
                                    <input type="hidden" class="hidden_valor_total_alimentacion_class_uniqueAc" value="">
                                    <input type="hidden" class="hidden_valor_total_alimentacion_class_uniqueAc_utilidad" value="">
                                    <button type="button" class="toggle-readonly-btn" data-target="costo-alimentacion">NA</button>
                                </div>
                            </td>
                            <td>
                                <div class="input-toggle-container">
                                    <textarea rows="1" class="costo-transporte valor-input class_transporteInput_unique" id="textarea_costo_trs"></textarea>
                                    <input type="hidden" class="hidden_transporte class_transporteHidden_unique" id="hidden_transporteId" value="1">
                                    <input type="hidden" class="hidden_valor_total_transporte_class_uniqueAc" value="">
                                    <input type="hidden" class="hidden_valor_total_transporte_class_uniqueAc_utilidad" value="">
                                    <button type="button" class="toggle-readonly-btn" data-target="costo-transporte">NA</button>
                                </div>
                            </td>
                            <td><textarea rows="1" id="valor_diaKamatiId" class="valor_Dia_kamati-class"></textarea></td>
                            <td>
                                <textarea rows="1" id="valor_diasKamatiId" class="valorDiasKamatiClass"></textarea>
                                <input type="hidden" name="" class="valor_diasKamati_classHiddenValorAc_unique_for_clon">
                            </td>
                            <!-- Columnas de valor con utilidad para el cliente  -->
                            <td><textarea rows="1" id="valor-dia-utilidadId" class="valor-dia-utilidadClass"></textarea></td>
                            <td>
                                <textarea rows="1" id="valor-total-dias-utilidadId" class="valorDiasClienteUtilidadClass"></textarea>
                                <input type="hidden" name="" class="valor_diasCliente_classHiddenValorAc_unique_for_clon">
                            </td>
                            <!-- Select para alerta de revisión o listo -->
                            <td>
                                <select name="select_rep" id="select_rep" class="select_res select_resp_unique_actividades">
                                    <option value="Revisar">Revisar</option>
                                    <option value="Lista">Listo</option>
                                </select>
                            </td>
                            <!-- Check box para desbloquear y hacer que el nuevo factor lo tome para esa fila -->
                            <td>
                                <input type="checkbox" name="check-new-factor" id="chec_new-factor-id" class="check_new_Factor-ClassActividades">
                                <input type="hidden" class="input_hidden_check_unique_class_ac">
                            </td>
                            <!-- Input para poner el nuevo factor -->
                            <td>
                                <input type="text" name="input-new-factor" id="input-new-factor-id" class="input-new-factor-Actividades-class" style="width: 50px;" disabled>
                                <input type="hidden" class="mantener-valor inputHidden-new-factor-Actividades-class" name="input-new-factor-hidden" id="input-new-factor-hiddenId" value="false">
                            </td>
                            <!-- Botón para eliminar una fila -->
                            <td><button class="delete-btn"><span class="icon">&times;</span></button></td>
                        </tr>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                setTimeout(() => {
                                    // Obtener la fila con la clase `tr_new_tbody_turnounique_Class`
                                    const trNewElement = document.querySelector('.tr_new_tbody_turnounique_Class');

                                    if (trNewElement) {
                                        // Generar un número aleatorio para el atributo `data-idTurno`
                                        const randomId = Math.floor(Math.random() * 10000); // Genera un número aleatorio entre 0 y 9999

                                        // Asignar el atributo `data-idTurno` a la fila
                                        trNewElement.setAttribute('data-idTurno', randomId);


                                        // Obtener la fila con la clase `tr_clasUnique_camposActividades`
                                        const trElement = document.querySelector('.tr_clasUnique_camposActividades');

                                        if (trElement) {
                                            // Buscar la fila más cercana hacia arriba con el atributo `data-idTurno`
                                            let closestRow = document.querySelector('.tr_new_tbody_turnounique_Class');

                                            // Si se encuentra una fila con el atributo `data-idTurno`
                                            if (closestRow && closestRow.hasAttribute('data-idTurno')) {
                                                // Obtener el valor del atributo `data-idTurno`
                                                const idTurnoValue = closestRow.getAttribute('data-idTurno');



                                                // Asignar el atributo `data-idTurno` a la fila actual sin números
                                                trElement.setAttribute('data-idTurno', idTurnoValue);
                                                console.log("Atributo data-idTurno asignado a tr_clasUnique_camposActividades:", idTurnoValue);
                                            } else {
                                                console.error('No se encontró una fila con el atributo data-idTurno cerca de tr_clasUnique_camposActividades');
                                            }
                                        } else {
                                            console.error('No se encontró la fila con la clase tr_clasUnique_camposActividades');
                                        }
                                    } else {
                                        console.error('No se encontró la fila con la clase tr_new_tbody_turnounique_Class');
                                    }
                                }, 50);
                            });
                        </script>
            </div>
            </tbody>
            </table>
        </div>

        <br>
        <div class="div_nuevo d-flex">
            <div class="separacion">

                <div class="form-floating">
                    <button type="button" name="button_nueva_fila_actividades" class="nueva_fila">Agregar
                        Fila</button>
                </div>
            </div>
            <div class="separacion">

                <div class="form-floating">
                    <button type="button" name="button_nuevo_turno" class="nueva_fila">Nuevo Turno</button>
                </div>
            </div>
            <div class="separacion">

                <div class="form-floating">
                    <button type="button" name="button_guardar_tabla_actividades" class="nueva_fila">Guardar
                        Tabla</button>
                </div>
            </div>
            <!-- inputs -->
            <div class="separacion">
                <label for="Total Kamati">Total Kamati</label>
                <div class="form-floating">
                    <div class="span_div">
                        <input type="text" name="txt_total_kamatiActividades" class="txt_total_kamatiActividadesClass" id="txt_total_kamatiActividadesIdUnique" readonly>
                        <span class="span_cop">COP</span>
                    </div>
                </div>
            </div>
            <div class="separacion">
                <label for="Total Cliente">Total Cliente</label>
                <div class="form-floating">
                    <div class="span_div">
                        <input type="text" name="txt_total_clienteActividades" class="txt_total_clienteActividadesClass" id="txt_total_clienteActividadesIdUnique" readonly>
                        <span class="span_cop">COP</span>
                    </div>
                </div>
            </div>
        </div>
        <br><br>


    </div>
    <div class="modal fade fixed_modal_kam deletemodalActividadesUniquemodal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Se borrará esta fila y tal vez tenga datos importantes, ¿Estás seguro de borrarla?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger confirmar_button_deleteAc" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade fixed_modal_kam deletemodalActividadesUniquemodal_turnos" id="deleteModal_turno" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Se borrará esta fila de turnos y tal vez tenga datos importantes, ¿Estás seguro de borrarla?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger confirmar_button_deleteAc_turno" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de botónes para las tablas al editar los valores de las filas -->

    <div class="modal fade fixed_modal_kam editModalClassActividades" id="editModal1" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Confirmar acción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modalText1Class" id="modalText1">
                    <!-- Texto dinámico según la acción -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary cancelEditClass" id="cancelEdit1" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary confirmEditClass" id="confirmEdit1">Confirmar</button>
                </div>
            </div>
        </div>
    </div>

    </div>
    <br>
    <script type="module" src="../js/maindcDs.js"></script>

    <!-- 
        
        

        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES * 
        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES * 
        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  
        ACTIVIDADES *  ACTIVIDADES * ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES *  ACTIVIDADES * 
        
        

         -->

    <br><br><br>
    <!-- TABLA DE Maquinaria -->
    <!-- TABLA DE MATERIALES -->
    <div id="tablaMaquinaria" class="tabla-container table_maquinariaClas">
        <div class="h2_separado">
            <h2>Maquinaria</h2>
            <input type="hidden" class="txt_id_identificador_Maquinaria" value="">
        </div>
        <div class="h2_separado hidden_table">
            <input type="checkbox" name="hidden_table_maquinaria checkClienteClass" id="id_hidden_table_maquinaria" class="hidden_checkBoxmaquinariaClass">
            <input type="hidden" name="hiddenInputFactoresIndependientesMaquinaria" class="hiddenInputFactoresIndependientesClasMaquinaria" id="hiddenInputFactoresIndependientesIdMaquinaria" value="noCheck">
            <input type="hidden" class="hiddenInputFactoresIndependientesClasMaquinariaClassUnique" id="hiddenInputFactoresIndependientesMaquinariaIdUno" value="">
            <label for="hidden_table_mat">Selecciona para factores independientes</label>
        </div>

        <div class="h2_separado">
            <h4>Nombre de la tabla</h4>
            <br>
            <input type="text" class="form-control nombre_table-maquinariaClass" name="nombre_materiales" id="nombre_maquinariaIDUnique"
                style="width: 30%;">
            <div id="divHiddenMaquinaria" class="divHiddenMaquinariaClass" hidden>
                <input type="hidden" name="inputHiddenTablaDivMaquinaria" id="inputHiddenTablaDivMaquinariaId">
                <div class="d-flex">
                    <div class="form-floating">
                        <input type="text" class="form-control limpiar factor_MoClassMateriales factor_MoClassMaquinariaUnique" name="txt_factor_mo1"
                            id="txt_factor_moHiddenMaquinaria" placeholder="" value=<?php echo $factor1['valorFactor']; ?>>
                        <input type="hidden" class="class_hidden_factorMo_maquinaria">
                        <label for="txt_factor_mo">Factor Mo</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control limpiar factor_OClassMateriales factor_OClassMaquinariaUnique" name="txt_factor_o1" id="txt_factor_oHiddenMaquinaria"
                            placeholder="" value=<?php echo $factor2['valorFactor']; ?>>
                        <input type="hidden" class="class_hidden_factorO_maquinaria">
                        <label for="numeroIdentificacion">Factor O</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" class="form-control limpiar factor_VClassMateriales factor_VClassMaquinariaUnique" name="txt_factor_V1" id="txt_factor_VHiddenMaquinaria"
                            placeholder="" value=<?php echo $factor3['valorFactor']; ?>>
                        <input type="hidden" class="class_hidden_factorVi_maquinaria">
                        <label for="numeroIdentificacion">Viáticos</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" class="form-control limpiar factor_polizaClassMateriales factor_polizaClassMaquinariaUnique polizaCliente" name="txt_poliza1"
                            id="txt_polizaHiddenMaquinaria" placeholder="" value=<?php echo $factor4['valorFactor']; ?>>
                        <input type="hidden" class="class_hidden_factorPo_maquinaria">
                        <label for="txt_poliza">Póliza</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" class="form-control limpiar factor_siemensClassMateriales factor_siemensClassMaquinariaUnique" name="txt_siemens1"
                            id="txt_siemensHiddenMaquinaria" placeholder="" value="">
                        <input type="hidden" class="class_hidden_Factor_Simaquinaria">
                        <label for="correoElectronico">Siemens</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" class="form-control limpiar factor_pilzClassMateriales factor_pilzClassMaquinariaUnique" name="txt_pilz1" id="txt_pilzHiddenMaquinaria" placeholder=""
                            value="">
                        <input type="hidden" class="class_hidden_Factor_Pimaquinaria">
                        <label for="correoElectronico">Pilz</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" class="form-control limpiar factor_rittalClassMateriales factor_rittalClassMaquinariaUnique" name="txt_rittal1" id="txt_rittalHiddenMaquinaria"
                            placeholder="" value="">
                        <input type="hidden" class="class_hidden_Factor_Rimaquinaria">
                        <label for="correoElectronico">Rittal</label>
                    </div>


                    <div class="form-floating">
                        <input type="email" class="form-control limpiar factor_phoenixcontactClassMateriales factor_phoenixcontactClassMaquinariaUnique" name="txt_phoenix1" id="txt_phoenixHiddenMaquinaria"
                            placeholder="" value="">
                        <input type="hidden" class="class_hidden_Factor_Pcmaquinaria">
                        <label for="correoElectronico">Phoenix Contact</label>
                    </div>
                </div>




            </div>
        </div>

        <div class="tabla_cotizacion">
            <br>
            <table class="table-responsive table_maquinariaClass table_original_maquinaria_class_unique" id="miTablaMovible_matElectricos">
                <thead>
                    <tr class="tr_class">
                        <th scope="col">Cantidad</th>
                        <th scope="col">Unid</th>
                        <th scope="col">Abreviatura Línea</th>
                        <th scope="col">Referencia</th>
                        <th scope="col">Material</th>
                        <th scope="col">Descripción Material</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Nota</th>
                        <th scope="col">Tipo Moneda</th>
                        <th scope="col">Precio lista</th>
                        <th scope="col">Costo Unitario Kamati</th>
                        <th scope="col">Costo total kamati</th>
                        <th scope="col">Valor utilidad</th>
                        <th scope="col">Valor total</th>
                        <th scope="col">T. Entrega</th>
                        <th scope="col">Descuento(%)</th>
                        <th scope="col">Descuento Adicional(%)</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Rep</th>
                        <th scope="col">Check</th>
                        <th scope="col">Fac ad</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody id="tableBodyMaquinaria" class="tbody_maquinariaClas">
                    <tr id="baseRowMaquinaria" class="trClassMquinaria">
                        <td>
                            <textarea rows="1" cols="10" class="cantidad-material numberInput materialescantidadTableMaquinaria"></textarea>
                            <input type="hidden" class="id_fila_MaquinariaTable_class">
                            <input type="hidden" class="id_fila_MaquinariaTable_class_clonedUpdate">
                        </td>
                        <td>
                            <select name="select_unidades" class="select_reset select_unidades_maquinaria_table_class">
                                <option value="Mts">Mts</option>
                                <option value="Días">Días</option>
                                <option value="Horas">Horas</option>
                                <option value="Glb">Glb</option>
                                <option value="Unidad">Unidad</option>
                                <option value="Turn">Turn</option>
                            </select>
                        </td>
                        <td>
                            <select class="abreviatura-lista select_reset select_abreviatura_maquinaria_class" name="id_Abreviaturas_nom">
                                <?php foreach ($nombreAb as $id => $nombre) : ?>
                                    <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                <?php endforeach; ?>
                            </select>

                        </td>

                        <td><textarea rows="1" cols="30" class="textarea_referencia_maquinaria_class"></textarea></td>
                        <td><textarea rows="1" cols="30" class="textarea_maquinariaMaterial_class"></textarea></td>
                        <td><textarea rows="1" cols="40" class="textarea_descripcionmaquinaria_class"></textarea></td>
                        <td>
                            <div class="select-input-container_maquinaria">
                                <select class="option-select-maquinaria select_reset select_proveedor_Maquinaria_class" name="select_proveedor"
                                    id="select_proveedor">
                                    <?php foreach ($nombreProv as $idpv => $nombrepv) : ?>
                                        <option value="<?php echo htmlspecialchars($idpv); ?>">
                                            <?php echo htmlspecialchars($nombrepv); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" class="option-input-maquinaria input_proveedor_Maquinaria_class hidden" placeholder="Escribe una opción">
                                <input type="hidden" class="option-input-maquinaria input_proveedor_Maquinaria_uniqueHidden">
                                <button type="button" class="toggle-btn">✎</button>
                            </div>
                        </td>
                        <td><textarea rows="1" class="nota_maquinaria_uniqueclass"></textarea></td>
                        <td>
                            <select class="select_trm_nu select_reset selet_trm_Maquinaria_class" name="select_trm" id="select_trm">
                                <option value="COP">COP</option>
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </td>
                        <td>
                            <div class="span_div">
                                <span class="span_trm" id="span_trm">$</span>
                                <textarea rows="1" class="precio-lista-input precio_lista_input_class_Maquinaria numberInput1"
                                    name="precio_lista"></textarea>
                            </div>
                        </td>
                        <td>
                            <div class="span_div">
                                <textarea rows="1" class="cost-kamati-input numberInput cost_kamati_input_class_Maquinaria"
                                    name="costo_kamati_unitario" readonly></textarea>
                                <span class="span_cop">COP</span>
                            </div>
                        </td>
                        <td>
                            <div class="span_div">
                                <textarea rows="1" class="cost-kamati-total numberInput cost_kamati_total_class_Maquinaria" readonly></textarea>
                                <span class="span_cop">COP</span>
                            </div>
                        </td>
                        <td>
                            <div class="span_div">
                                <textarea rows="1" class="valor-utilidad numberInput valor_utilidad_class_Maquinaria" readonly></textarea>
                                <span class="span_cop">COP</span>
                            </div>
                        </td>
                        <td>
                            <div class="span_div">
                                <textarea rows="1" class="value-total-input numberInput value_total_input_class_Maquinaria" readonly></textarea>
                                <span class="span_cop">COP</span>
                            </div>
                        </td>
                        <td><textarea rows="1" cols="13" class="valor_tiempo_entrega_class_Maquinaria" id="valor_tiempo_entregaMaquinaria"></textarea></td>

                        <td><textarea rows="1" cols="10" class="descuento-input descuento_input_Maquinaria_class"></textarea></td>
                        <td><textarea rows="1" cols="10" class="descuento-adicional-input descuento_adicional_input_Maquinaria_class"></textarea></td>
                        <td><input type="date" class="date_input_entrega date_input_entrega_class_Maquinaria" id="id_fecha_tiempo_entregaMaquinaria"></td>
                        <td>
                            <select name="select_rep select_reset" class="select_rep_classMaquinaria" id="select_rep">
                                <option value="Revisar">Revisar</option>
                                <option value="Lista">Listo</option>
                            </select>
                        </td>
                        <td>
                            <input type="checkbox" name="checkBoxk_newFactorMaquinaria " id="checkBox_newFactorMaquinariaId" class="checkBoxk_newFactorMaquinariaClass check_estado_class_Maquinaria">
                            <input type="hidden" class="hidden_estado_unique_checkBox_maquinaria">

                        </td>
                        <td>
                            <input type="text" name="inputFactorNewMaquinaria" id="inputIdNewFactor" class="inputNewFactorMaquinariaClas factor_adicional_class_Maquinaria" disabled>
                            <input type="hidden" name="inputHiddenNewFactorMaquinariaName" class="inputHiddenNewFactorMaquinariaClas" id="inputHiddenNewFactorMaquinariaId" value="false">
                        </td>
                        <td><button class="delete-btn"><span class="icon">&times;</span></button></td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="div_nuevo d-flex">
            <div class="separacion">
                <div class="form-floating">
                    <button type="button" name="button_agregar_fila_Maquinaria" class="nueva_fila"
                        title="Agrega una nueva fila" id="button_agregar_fila_Maquinaria">Agregar Fila</button>
                </div>
            </div>
            <div class="separacion">
                <div class="form-floating">
                    <button type="button" name="button_guardar_tabla_maquinaria" class="nueva_fila"
                        title="Guarda una nueva tabla">Guardar Tabla</button>
                </div>
            </div>
            <div class="separacion">
                <label for="Total Kamati">Total Kamati</label>
                <div class="form-floating">
                    <div class="span_div">
                        <input type="text" name="txt_total_kamati" id="txtTotalKamati_maquinaria" class="txtTotalKamatiMateriales txtTotalKamatiMaquinaria" readonly>
                        <span class="span_cop">COP</span>
                    </div>
                </div>
            </div>
            <div class="separacion">
                <label for="Total Cliente">Total Cliente</label>
                <div class="form-floating">
                    <div class="span_div">
                        <input type="text" name="txt_total_cliente" id="txtTotalCliente_maquinaria" class="txtTotalClienteMateriales txtTotalClienteMaquinaria" readonly>
                        <span class="span_cop">COP</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade fixed_modal_kam" id="deleteModalMaquinaria" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Se borrará esta fila y tal vez tenga datos importantes, ¿Estás seguro de borrarla?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteModalMaquinaria">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de botónes para las tablas al editar los valores de las filas -->

    <div class="modal fade fixed_modal_kam" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Confirmar acción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalText">
                    <!-- Texto dinámico según la acción 1-->

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelEdit"
                        data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="confirmEdit">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
    <div class="tabla_cotizacion" id="idTablaAbreviaturasKamati-ID">
        <h2>Abreviaturas Kamati</h2>
        <table class="table" id="abreviaturas-kamatiId">
            <thead>
                <tr class="tr_class">
                    <th scope="col">ABREVIATURAS</th>
                    <th scope="col">AUTO</th>
                    <th scope="col">COM</th>
                    <th scope="col">CE</th>
                    <th scope="col">VAR</th>
                    <th scope="col">SOFT</th>
                    <th scope="col">REP</th>
                    <th scope="col">LP</th>
                    <th scope="col">O</th>
                    <th scope="col">PILZ</th>
                    <th scope="col">PC</th>
                    <th scope="col">R</th>
                    <th scope="col">V</th>
                    <th scope="col">MO</th>
                    <th scope="col">SUP</th>
                    <th scope="col">ING</th>
                    <th scope="col">PM</th>
                    <th scope="col">SISO</th>
                </tr>
            </thead>
            <tbody id="tableBodyAbreviaturas-Kamati">
                <tr>
                    <th scope="row">LÍNEA</th>
                    <td>AUTOMATIZACIÓN</td>
                    <td>COMUNICACIÓN</td>
                    <td>MANIOBRA CE</td>
                    <td>VARIADORES</td>
                    <td>SOFTWARE</td>
                    <td>REPUESTOS</td>
                    <td>MANIOBRA LP</td>
                    <td>OTROS</td>
                    <td>PILZ</td>
                    <td>PHOENIX C.</td>
                    <td>RITTAL</td>
                    <td>VIATICOS</td>
                    <td>MANO DE OBRA ELECTRICA</td>
                    <td>SUPERVISOR</td>
                    <td>INGENIERÍA</td>
                    <td>PROJECT MANAGAER</td>
                    <td>SISO</td>
                </tr>
                <tr id="filaValores-kamati" class="filaValores-kamatiClass">
                    <th scope="row">VALORES</th>
                    <td><input type="text" id="id_AutoKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_ComKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_CeKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_VarKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_SoftKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_RepKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_LpKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_OKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_PilzKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_PcKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_RKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_VKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_MoKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_SupKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_IngKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_PmKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_SisoKamatiValor" class="tamano_abreviaturasInput" readonly></td>
                </tr>
            </tbody>
        </table>

    </div>
    <div class="separacion">
        <label for="Total Kamati">Total líneas Kamati</label>
        <div class="form-floating">
            <div class="span_div">
                <input type="text" name="txt_total_kamatiClassAbreviatura" class="txtClassTotalKamatiLineasAb" id="txtIdTotalKamatiLineasAb" readonly>
                <span class="span_cop">COP</span>
            </div>
        </div>
    </div>
    <br><br>
    <div class="tabla_cotizacion">
        <h2>Abreviaturas cliente</h2>
        <table class="table" id="abreviaturas-ClienteId">
            <thead>
                <tr>
                    <th scope="col">ABREVIATURAS</th>
                    <th scope="col">AUTO</th>
                    <th scope="col">COM</th>
                    <th scope="col">CE</th>
                    <th scope="col">VAR</th>
                    <th scope="col">SOFT</th>
                    <th scope="col">REP</th>
                    <th scope="col">LP</th>
                    <th scope="col">O</th>
                    <th scope="col">PILZ</th>
                    <th scope="col">PC</th>
                    <th scope="col">R</th>
                    <th scope="col">V</th>
                    <th scope="col">MO</th>
                    <th scope="col">SUP</th>
                    <th scope="col">ING</th>
                    <th scope="col">PM</th>
                    <th scope="col">SISO</th>
                </tr>
            </thead>
            <tbody id="tableBodyAbreviaturas-Cliente">
                <tr>
                    <th scope="row">LÍNEA</th>
                    <td>AUTOMATIZACIÓN</td>
                    <td>COMUNICACIÓN</td>
                    <td>MANIOBRA CE</td>
                    <td>VARIADORES</td>
                    <td>SOFTWARE</td>
                    <td>REPUESTOS</td>
                    <td>MANIOBRA LP</td>
                    <td>OTROS</td>
                    <td>PILZ</td>
                    <td>PHOENIX C.</td>
                    <td>RITTAL</td>
                    <td>VIIATICOS</td>
                    <td>MANO DE OBRA ELECTRICA</td>
                    <td>SUPERVISOR</td>
                    <td>INGENIERÍA</td>
                    <td>PROJECT MANAGAER</td>
                    <td>SISO</td>
                </tr>
                <tr id="filaValores-cliente">
                    <th scope="row">VALORES</th>
                    <td><input type="text" id="id_AutoClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_ComClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_CeClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_VarClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_SoftClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_RepClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_LpClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_OClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_PilzClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_PcClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_RClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_VClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_MoClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_SupClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_IngClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_PmClienteValor" class="tamano_abreviaturasInput" readonly></td>
                    <td><input type="text" id="id_SisoClienteValor" class="tamano_abreviaturasInput" readonly></td>
                </tr>
                <tr id="porcentajeCliente-ID">
                    <th scope="row">PORCENTAJE</th>
                    <td><input type="text" id="id_AutoClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_ComClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_CeClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_VarClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_SoftClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_RepClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_LpClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_OClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_PilzClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_PcClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_RClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_VClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_MoClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_SupClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_IngClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_PmClientePorcentaje" class="tamano_abreviaturasInput"></td>
                    <td><input type="text" id="id_SisoClientePorcentaje" class="tamano_abreviaturasInput"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="separacion">
        <label for="Total Kamati">Total líneas Cliente</label>
        <div class="form-floating">
            <div class="span_div">
                <input type="text" name="txt_total_ClienteClassAbreviatura" class="txtClassTotalClienteLineasAb" id="txtIdTotalClienteLineasAb" readonly>
                <span class="span_cop">COP</span>
            </div>
        </div>
    </div>
    <script type="module" src="../js/mainMaquinaria.js"></script>


    <br><br><br>





    <script>
        // Inicializar Sortable en el tbody de la tabla
        const miTablaMov = document.getElementById('miTablaMovible').getElementsByTagName('tbody')[0];
        const sortable = Sortable.create(miTablaMov, {
            animation: 150,
            handle: 'tr' // Hacer que solo las filas puedan ser arrastradas
        });
    </script>
   
    <script>
        // Inicializar Sortable en el tbody de la tabla
        const miTablaMov4 = document.getElementById('miTablaMovible_matElectricos').getElementsByTagName('tbody')[0];
        const sortable4 = Sortable.create(miTablaMov4, {
            animation: 150,
            handle: 'tr' // Hacer que solo las filas puedan ser arrastradas
        });
    </script>
    <script src="../js/alertsKam.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/buttonUpNew.js"></script>
    <script src="../js/ajusteTextArea.js"></script>
    <script src="../js/formateoTablaViaticos.js"></script>


    <script src="../js/recuperarFactores.js"></script>
    <script>
      // Obtener referencia a la tabla
const miTablaMov1 = document.getElementById('miTablaMovible1').getElementsByTagName('tbody')[0];

// Inicializar Sortable
const sortable1 = Sortable.create(miTablaMov1, {
    animation: 150,
    handle: 'tr', // Permite mover filas con drag-and-drop
    onEnd: function(evt) {
        // Fila que fue movida
        const movedRow = evt.item;

        console.log('Fila movida:', movedRow);

        // Verificar si la fila movida pertenece a la clase 'filaclonableunica_actividades_Class'
        if (movedRow.classList.contains('filaclonableunica_actividades_Class')) {
            console.log('La fila pertenece a filaclonableunica_actividades_Class');

            // Buscar la fila más cercana hacia arriba con la clase 'tr_new_tbody_turnounique_Class'
            let closestTurnoRow = movedRow.previousElementSibling;
            let closestDataTurnoId = null;

            while (closestTurnoRow) {
                console.log('Revisando fila:', closestTurnoRow);
                if (closestTurnoRow.classList.contains('tr_new_tbody_turnounique_Class')) {
                    closestDataTurnoId = closestTurnoRow.getAttribute('data-turno-id');
                    console.log('Fila encontrada con data-turno-id:', closestDataTurnoId);
                    break;
                }
                closestTurnoRow = closestTurnoRow.previousElementSibling; // Continuar hacia arriba
            }

            // Si se encontró una fila válida con 'data-turno-id'
            if (closestDataTurnoId) {
                // Actualizar el atributo 'data-turno-id' de la fila movida
                movedRow.setAttribute('data-turno-id', closestDataTurnoId);
                console.log('Atributo data-turno-id actualizado en la fila movida:', closestDataTurnoId);

                // Actualizar el valor del campo con la clase 'class_hidden_identificador_uniqueAc'
                const hiddenField = movedRow.querySelector('.class_hidden_identificador_uniqueAc');
                if (hiddenField) {
                    hiddenField.value = closestDataTurnoId; // Asignar el nuevo valor
                    console.log('Campo oculto actualizado con el valor:', hiddenField.value);
                } else {
                    console.warn('No se encontró el campo class_hidden_identificador_uniqueAc en la fila movida');
                }
            } else {
                console.warn('No se encontró una fila válida con la clase tr_new_tbody_turnounique_Class hacia arriba');
            }
        } else {
            console.warn('La fila movida no pertenece a filaclonableunica_actividades_Class');
        }
    }
});
    </script>
<div class="div_genera_Excel">
    <button onclick="obtenerDatos()" class="excel-button">
        <i class="fas fa-file-excel"></i> Generar Excel
    </button>
</div>
</body>

</html>