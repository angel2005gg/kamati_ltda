<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styleKamaInitrAp.css">
    <link rel="icon" type="image/png" href="../img/logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/JavaScriptSolicitudCol.js" defer></script>
    <script src="../js/selectDiaComp.js" defer></script>



    <title>SolicitudPermiso</title>
</head>

<body>
    <style>
        input[type="radio"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            width: 20px;
            height: 20px;
            border: 2px solid #000; /* Color y grosor del borde */
            border-radius: 50%;
            outline: none;
            cursor: pointer;
            position: relative;
        }

        input[type="radio"]:checked::before {
            content: "";
            display: block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #000; /* Color del punto interno */
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <?php include 'navBar.php'; ?>
    
    <br><br>
    <?php include '../configuracion/Fecha.php'; ?>
    
    <div id="alertContainer"></div>
    <div id="alertContainerPermiso"></div>
    <form method="POST" action="../controlador/ServletPermisos.php">
        <div class="container" id="container">
            <div class="formulario_de_registro_usuario_intranet">
                <h2>Solicitud de novedad</h2>
                <br><br>
                <div>
                    <h5>Datos Personales</h5>

                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="txt_date_eleboracion" id="txt_date_eleboracion" placeholder="" value="<?php echo $fechaActual; ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_date_eleboracion1" id="txt_date_eleboracion1" placeholder="" value="<?php echo $fechaActual; ?>">
                                <label for="floatingInput">Fecha Elaboración *</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_nombre" id="txt_nombre" value="<?php echo $_SESSION['nombre_completo'] ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_nombre1" id="txt_nombre1" value="<?php echo $_SESSION['nombre_completo'] ?>">
                                <label for="floatingInput">Nombre Completo</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_identificacion" id="txt_identificacion" value="<?php echo $_SESSION['identificacion'] ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_identificacion1" id="txt_identificacion1" value="<?php echo $_SESSION['identificacion'] ?>">
                                <label for="floatingInput">N° Identificación</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_cargo" id="txt_cargo" value="<?php echo $_SESSION['cargo'] ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_cargo1" id="txt_cargo1" value="<?php echo $_SESSION['cargo'] ?>">
                                <label for="floatingInput">Cargo</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_area" id="txt_area" value="<?php echo $_SESSION['area'] ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_area1" id="txt_area1" value="<?php echo $_SESSION['area'] ?>">
                                <label for="floatingInput">Area</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_jefe" id="txt_jefe" value="<?php echo $_SESSION['nombre_jefe'] ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_jefe1" id="txt_jefe1" value="<?php echo $_SESSION['nombre_jefe'] ?>">
                                <label for="floatingInput">Jefe Inmediato</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="txt_sede" id="txt_sede" value="<?php echo $_SESSION['sede'] ?>" disabled>
                                <input type="hidden" class="form-control" name="txt_sede1" id="txt_sede1" value="<?php echo $_SESSION['sede'] ?>">
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

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="permiso" name="radio1" value='Permiso'>
                                <label class="form-check-label" for="inlineCheckbox1">Permiso</label>
                            </div>

                        </div>
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="licencia" name="radio1" value='Licencia'>
                                <label class="form-check-label" for="inlineCheckbox1">Licencia</label>
                            </div>

                        </div>
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="diaFamilia" name="radio1" value='DiaFamilia'>
                                <label class="form-check-label" for="inlineCheckbox1">Día de la familia</label>
                            </div>

                        </div>
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="cumpleaños" name="radio1" value='Cumpleaños'>
                                <label class="form-check-label" for="inlineCheckbox1">Cumpleaños</label>
                            </div>

                        </div>
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="vacaciones" name="radio1" value='Vacaciones'>
                                <label class="form-check-label" for="inlineCheckbox1">Vacaciones</label>
                            </div>
                        </div>
                    </div>
                    <br><br>

                    <div class="row g-3">
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="radioHoras" name="radio2" value='horas'>
                                <label class="form-check-label" for="inlineCheckbox1">Por horas</label>
                            </div>
                        </div>
                        <div class="col-md">

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="radioDias" name="radio2" value='dias'>
                                <label class="form-check-label" for="inlineCheckbox1">Por días</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="text" class="form-control" name="inputHorasDias" id="inputHorasDias" placeholder="">
                                <input type="hidden" class="form-control" name="inputHorasDias1" id="inputHorasDias1" placeholder="">
                                <label for="floatingInput">Cantidad *</label>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="date_inicio" id="date_inicio" placeholder="">
                                <label for="floatingInput">Fecha inicio de novedad *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="date" class="form-control" name="date_fin" id="date_fin" placeholder="">
                                <label for="floatingInput">Fecha fin de novedad *</label>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select mi-clase-select" name="diascompensados" id="diascompensados" disabled>
                                    <option>Si</option>
                                    <option>No</option>
                                </select>
                                <label for="floatingSelectGrid">¿Días compensados? *</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <select class="form-select" name="cantidad_dias" id="cantidad_dias" disabled>
                                </select>
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
                    </div>
                    <br><br>
                    <div class="row g-3">
                        <div class="col-md">
                            <div class="form-floating">
                                <textarea class="form-control" name="textarea_motivo" id="textarea_motivo" style="height: 200px"></textarea>
                                <label for="floatingTextarea1">Motivo</label>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row g-3 centro">
                        <input type="hidden" name="menu" value="solicitar">

                        <div class="col-md">
                            <div class="form-floating">
                                <input type="submit" class="ingreso_submit" name="accion" value="Regresar">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-floating">
                                <input type="submit" class="ingreso_submit" name="accion" value="Enviar">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </form>

    
    <script src="../js/alertsKam.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   
</body>

</html>