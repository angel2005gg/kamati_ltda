<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../modelo/dao/PermisosDao.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styleKamaInitrAp.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Consulta solicitudes</title>
</head>

<body>

    <?php include 'navBarJefeAdminLin.php';
    include '../modelo/dao/TipoNovedadDao.php';

    $tipoNovedad = new TipoNovedadDao();

    $tipo = $tipoNovedad->consultarTiposNovedad();

    $permisos = new PermisosDao();
    $data = $permisos->consultarPermisosPendientesNegado($_SESSION['idUser']);

    $datas = $permisos->consultarPermisosAprobados($_SESSION['idUser']);

    $completos = $permisos->consultarPermisosCompletos();
    ?>

    <br>

    <div id="alertContainer"></div>

    <div class="container">
        <div class="container_table">
            <br><br><br>
            <h2>Listado de solicitudes</h2>
            <br>

            <form id="filter-form-pendientes">
                <div class="form-floating tma">
                    <input type="date" class="form-control" name="fechaPendientes" id="fechaPendientes" placeholder="" required>
                    <label for="floatingInput">Elije la fecha</label>
                </div>
            </form>
            <div class="tabla_solicitudes">
                <table class="table-responsive">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Fecha solicitud</th>
                            <th scope="col">Tipo permiso</th>
                            <th scope="col">Tiempo</th>
                            <th scope="col">Cantidad tiempo</th>
                            <th scope="col">Fecha incio</th>
                            <th scope="col">Fecha fin</th>
                            <th scope="col">Dias compensados</th>
                            <th scope="col">Cantidad dias compensados</th>
                            <th scope="col">Total horas</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Remuneración</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="table_body_select_pendiente">
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['primer_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['primer_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_elaboracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_inicio_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_fin_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_horas_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['motivo_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['remuneracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado_permiso']); ?></td>
                                <td><button type="button" class="button_color seleccionar" data-id="<?php echo htmlspecialchars($row['id_Permisos']); ?>">Seleccionar</button></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>





        <form method="POST" action="../controlador/ServletPermisosJefeAdminLin.php">
            <div class="container" id="container">
                <div class="formulario_de_registro_usuario_intranet">
                    <h2>Solicitud</h2>
                    <br><br>
                    <div>
                        <h5>Datos Personales</h5>
                        <br>
                        <div class="row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="txt_date_eleboracion" id="txt_date_eleboracion" placeholder="" disabled>
                                    <input type="hidden" class="form-control" name="txt_date_eleboracion1" id="txt_date_eleboracion1" placeholder="">
                                    <label for="floatingInput">Fecha Elaboración *</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" disabled>
                                    <input type="hidden" class="form-control" name="txt_nombre1" id="txt_nombre1">
                                    <label for="floatingInput">Nombre Completo</label>
                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_identificacion" id="txt_identificacion" disabled>
                                    <input type="hidden" class="form-control" name="txt_identificacion1" id="txt_identificacion1">
                                    <label for="floatingInput">N° Identificación</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_cargo" id="txt_cargo" disabled>
                                    <input type="hidden" class="form-control" name="txt_cargo1" id="txt_cargo1">
                                    <label for="floatingInput">Cargo</label>
                                </div>
                            </div>

                        </div>
                        <br>
                        <div class="row g-2">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_area" id="txt_area" disabled>
                                    <input type="hidden" class="form-control" name="txt_area1" id="txt_area1">
                                    <label for="floatingInput">Area</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_sede" id="txt_sede" disabled>
                                    <input type="hidden" class="form-control" name="txt_sede1" id="txt_sede1">
                                    <label for="floatingInput">Sede</label>
                                </div>
                            </div>
                        </div>

                    </div>
                    <br><br>
                    <div>
                        <h5>Detalles de solicitud</h5>
                        <div class="row g-2">
                            <div class="col-md">

                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_tipo_permiso" id="txt_tipo_permiso" disabled>
                                    <input type="hidden" class="form-control" name="txt_tipo_permiso1" id="txt_tipo_permiso1">
                                    <label for="floatingInput">Tipo Permiso</label>
                                </div>

                            </div>
                            <div class="col-md">

                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_tipo_tiempo" id="txt_tipo_tiempo" disabled>
                                    <input type="hidden" class="form-control" name="txt_tipo_tiempo1" id="txt_tipo_tiempo1">
                                    <label for="floatingInput">Tipo Tiempo</label>
                                </div>

                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_cantidad_tiempo" id="txt_cantidad_tiempo" placeholder="" disabled>
                                    <input type="hidden" class="form-control" name="txt_cantidad_tiempo1" id="txt_cantidad_tiempo1">
                                    <label for="floatingInput">Cantidad</label>
                                </div>
                            </div>

                        </div>



                        <br><br>
                        <div class="row g-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="date_inicio" id="date_inicio" placeholder="" disabled>
                                    <input type="hidden" class="form-control" name="date_inicio1" id="date_inicio1">
                                    <label for="floatingInput">Fecha inicio de novedad *</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="date" class="form-control" name="date_fin" id="date_fin" placeholder="" disabled>
                                    <input type="hidden" class="form-control" name="date_fin1" id="date_fin1">
                                    <label for="floatingInput">Fecha fin de novedad *</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row g-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_dias_compensados" id="txt_dias_compensados" placeholder="" disabled>
                                    <input type="hidden" class="form-control" name="txt_dias_compensados1" id="txt_dias_compensados1">
                                    <label for="floatingSelectGrid">¿Días compensados? *</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_cantidad_dias_compensados" id="txt_cantidad_dias_compensados" placeholder="" disabled>
                                    <input type="hidden" class="form-control" name="txt_cantidad_dias_compensados1" id="txt_cantidad_dias_compensados1">
                                    <label for="floatingInput">Cantidad de días</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row g-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="number" class="form-control" name="txt_total_horas" id="txt_total_horas" disabled>
                                    <input type="hidden" class="form-control" name="txt_total_horas1" id="txt_total_horas1">
                                    <label for="floatingInput">Total de horas</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" name="txt_Remuneracion" id="txt_Remuneracion" disabled>
                                        <input type="hidden" class="form-control" name="txt_Remuneracion1" id="txt_Remuneracion1">
                                        <label for="floatingInput">Remuneración</label>
                                    </div>
                                </div>
                            </div>
                          

                        </div>
                        <br><br>
                        <div class="row g-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <textarea class="form-control" name="textarea_motivo" id="textarea_motivo" style="height: 200px" disabled></textarea>
                                    <input type="hidden" class="form-control" name="textarea_motivo1" id="textarea_motivo1">
                                    <input type="hidden" class="form-control" name="txt_hidden" id="txt_hidden">
                                    <input type="hidden" class="form-control" name="txt_hidden_estado" id="txt_hidden_estado">
                                    <input type="hidden" class="form-control" name="txt_hidden_user_id_jefe" id="txt_hidden_user_id_jefe">
                                    <label for="floatingTextarea1">Motivo</label>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row g-3 centro">
                            <input type="hidden" name="menu" value="permisosLin">

                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="submit" class="ingreso_submit" name="accion" value="Regresar">
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="submit" class="ingreso_submit" name="accion" value="Aprobar">

                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="submit" class="ingreso_submit" name="accion" value="No Aprobar">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
        </form>



        <div class="container_table">
            <br><br><br>
            <h2>Listado de solicitudes aprobadas</h2>
            <br>

            <form id="filter-form">
                <div class="form-floating tma">
                    <input type="date" class="form-control" name="fechaAprobadas" id="fechaAprobadas" placeholder="" required>
                    <label for="floatingInput">Elije la fecha</label>
                </div>
            </form>
            <div class="tabla_solicitudes">
                <table class="table-responsive">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Fecha solicitud</th>
                            <th scope="col">Tipo permiso</th>
                            <th scope="col">Tiempo</th>
                            <th scope="col">Cantidad tiempo</th>
                            <th scope="col">Fecha incio</th>
                            <th scope="col">Fecha fin</th>
                            <th scope="col">Dias compensados</th>
                            <th scope="col">Cantidad dias compensados</th>
                            <th scope="col">Total horas</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Remuneración</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="table_body_select_Aprobadas">
                        <?php foreach ($datas as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['primer_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['primer_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_elaboracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_inicio_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_fin_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_horas_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['motivo_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['remuneracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado_permiso']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br><br><br><br>


        <div class="container_table">
            <br><br><br>
            <h2>Listado de todas las solicitudes</h2>
            <br>
            <form id="filter-form">
                <div class="form-floating tma">
                    <input type="date" class="form-control" name="fecha" id="fecha" placeholder="" required>
                    <label for="floatingInput">Elije la fecha</label>
                </div>
            </form>
            <div class="tabla_solicitudes">
                <table class="table-responsive">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Nombre</th>
                            <th scope="col">Apellido</th>
                            <th scope="col">Fecha solicitud</th>
                            <th scope="col">Tipo permiso</th>
                            <th scope="col">Tiempo</th>
                            <th scope="col">Cantidad tiempo</th>
                            <th scope="col">Fecha incio</th>
                            <th scope="col">Fecha fin</th>
                            <th scope="col">Dias compensados</th>
                            <th scope="col">Cantidad dias compensados</th>
                            <th scope="col">Total horas</th>
                            <th scope="col">Motivo</th>
                            <th scope="col">Remuneración</th>
                            <th scope="col">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="table_permisos_completos">
                        <?php foreach ($completos as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['primer_nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['primer_apellido']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_elaboracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['tipo_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_tiempo']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_inicio_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_fin_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['cantidad_dias_compensados']); ?></td>
                                <td><?php echo htmlspecialchars($row['total_horas_permiso']); ?></td>
                                <td><?php echo htmlspecialchars($row['motivo_novedad']); ?></td>
                                <td><?php echo htmlspecialchars($row['remuneracion']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado_permiso']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <br><br>
    <br><br>
    <script src="../js/JavaScriptSeleccionarSolicitudIntranetLina.js"></script>
    <script src="../js/alertsKam.js"></script>
    <script src="../js/filtroFecha.js"></script>
    <script src="../js/filtroFechaAprobadas.js"></script>
    <script src="../js/filtroFechaPendientes.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>