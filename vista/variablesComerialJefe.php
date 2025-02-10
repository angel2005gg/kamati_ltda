<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
require_once '../modelo/dao/FactoresDao.php';
require_once '../modelo/dao/ViaticosDao.php';
require_once '../modelo/dao/CargoCotizacionDao.php';
require_once '../modelo/Factores.php';
require_once '../modelo/Viaticos.php';
require_once '../modelo/CargoCotizacion.php';
require_once '../modelo/Recargos.php';
require_once '../modelo/dao/RecargosDao.php';
require_once '../modelo/HorasJornada.php';
require_once '../modelo/dao/HorasJornadaDao.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../css/styleKamaInitrApis.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php

    include "navBarJefeComercial.php";
    $factoresDao = new FactoresDao();
    $factor1 = new Factores();
    $factor2 = new Factores();
    $factor3 = new Factores();
    $factor4 = new Factores();

    $factor1 = $factoresDao->consultarFactores(1);
    $factor2 = $factoresDao->consultarFactores(2);
    $factor3 = $factoresDao->consultarFactores(3);
    $factor4 = $factoresDao->consultarFactores(4);


    $vaiticosDao = new ViaticosDao();
    $viatico1 = new Viaticos();
    $viatico2 = new Viaticos();

    $viatico1 = $vaiticosDao->consultarViaticosId(1);
    $viatico2 = $vaiticosDao->consultarViaticosId(2);

    $cargoDao = new CargoCotizacionDao();
    $cargo1 = new CargoCotizacion();
    $cargo2 = new CargoCotizacion();
    $cargo3 = new CargoCotizacion();
    $cargo4 = new CargoCotizacion();
    $cargo5 = new CargoCotizacion();
    $cargo6 = new CargoCotizacion();
    $cargo7 = new CargoCotizacion();
    $cargo8 = new CargoCotizacion();
    $cargo9 = new CargoCotizacion();
    $cargo10 = new CargoCotizacion();

    $cargo1 = $cargoDao->consultarCargoId(1);
    $cargo2 = $cargoDao->consultarCargoId(2);
    $cargo3 = $cargoDao->consultarCargoId(3);
    $cargo4 = $cargoDao->consultarCargoId(4);
    $cargo5 = $cargoDao->consultarCargoId(5);
    $cargo6 = $cargoDao->consultarCargoId(6);
    $cargo7 = $cargoDao->consultarCargoId(7);
    $cargo8 = $cargoDao->consultarCargoId(8);
    $cargo9 = $cargoDao->consultarCargoId(9);
    $cargo10 = $cargoDao->consultarCargoId(10);

    $recargosDao = new RecargosDao();
    $recargos1 = new Recargos();
    $recargos2 = new Recargos();
    $recargos3 = new Recargos();
    $recargos4 = new Recargos();
    $recargos5 = new Recargos();
    $recargos6 = new Recargos();
    $recargos7 = new Recargos();

    $recargos1 = $recargosDao->consultarRecargosId(1);
    $recargos2 = $recargosDao->consultarRecargosId(2);
    $recargos3 = $recargosDao->consultarRecargosId(3);
    $recargos4 = $recargosDao->consultarRecargosId(4);
    $recargos5 = $recargosDao->consultarRecargosId(5);
    $recargos6 = $recargosDao->consultarRecargosId(6);
    $recargos7 = $recargosDao->consultarRecargosId(7);

    $horasDao = new HorasJornadaDao();
    $horas1 = new HorasJornada();
    $horas2 = new HorasJornada();
    $horas3 = new HorasJornada();
    $horas4 = new HorasJornada();

    $horas1 = $horasDao->consultaHorasJornada(1);
    $horas2 = $horasDao->consultaHorasJornada(2);
    $horas3 = $horasDao->consultaHorasJornada(3);
    $horas4 = $horasDao->consultaHorasJornada(4);



    ?>
    <br><br><br><br><br>


    <div id="alertContainer"></div>
    <form action="../controlador/ServletVariablesComercial.php" method="POST">
        <div class="container">
            <h2>Factores</h2>
            <div class="row g-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_FactorMo" id="txt_nombre_FactorMo" placeholder="" value="<?php echo $factor1['valorFactor']; ?>">
                        <input type="hidden" name="txt_hidden_btn1" value="<?php echo $factor1['id_Factores']; ?>">
                        <label for="floatingInput"><?php echo $factor1['factores']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num1">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_FactorO" id="txt_nombre_FactorO" placeholder="" value="<?php echo $factor2['valorFactor']; ?>">
                        <input type="hidden" name="txt_hidden_btn2" value="<?php echo $factor2['id_Factores']; ?>">
                        <label for="floatingInput"><?php echo $factor2['factores']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num2">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Viaticos" id="txt_nombre_Viaticos" placeholder="" value="<?php echo $factor3['valorFactor']; ?>">
                        <input type="hidden" name="txt_hidden_btn3" value="<?php echo $factor3['id_Factores']; ?>">
                        <label for="floatingInput"><?php echo $factor3['factores']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num3">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Poliza" id="txt_nombre_Poliza" placeholder="" value="<?php echo $factor4['valorFactor']; ?>">
                        <input type="hidden" name="txt_hidden_btn4" value="<?php echo $factor4['id_Factores']; ?>">
                        <label for="floatingInput"><?php echo $factor4['factores']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num4">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

            </div>
        </div>
        <br><br>

        <div class="container">
            <h2>Viáticos</h2>
            <div class="row g-4">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_viatico_unos" id="txt_viatico_unos_id" placeholder="" value="<?php echo $viatico1['valor_diario']; ?>">
                        <input type="hidden" name="txt_hidden_btn5" value="<?php echo $viatico1['id_Viaticos']; ?>">
                        <label for="floatingInput"><?php echo $viatico1['nombre_viatico']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num5">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_viatico_doss" id="txt_viatico_doss_id" placeholder="" value="<?php echo $viatico2['valor_diario']; ?>">
                        <input type="hidden" name="txt_hidden_btn6" value="<?php echo $viatico2['id_Viaticos']; ?>">
                        <label for="floatingInput"><?php echo $viatico2['nombre_viatico']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num6">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
        </div>

        <br><br>
        <div class="container">
            <h2>Tarifas</h2>
            <div class="row g-4">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_siete" id="txt_siete_id" placeholder="" value="<?php echo $cargo1['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn7" value="<?php echo $cargo1['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo1['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num7">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_ocho" id="txt_ocho_id" placeholder="" value="<?php echo $cargo2['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn8" value="<?php echo $cargo2['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo2['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num8">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nueve" id="txt_nueve_id" placeholder="" value="<?php echo $cargo3['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn9" value="<?php echo $cargo3['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo3['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num9">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_diez" id="txt_diez_id" placeholder="" value="<?php echo $cargo4['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn10" value="<?php echo $cargo4['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo4['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num10">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_once" id="txt_once_id" placeholder="" value="<?php echo $cargo5['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn11" value="<?php echo $cargo5['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo5['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num11">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
        </div>
        <br>

        <div class="container">
            <div class="row g-4">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_doce" id="txt_doce_id" placeholder="" value="<?php echo $cargo6['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn12" value="<?php echo $cargo6['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo6['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num12">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_trece" id="txt_trece_id" placeholder="" value="<?php echo $cargo7['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn13" value="<?php echo $cargo7['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo7['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num13">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_catorce" id="txt_catorce_id" placeholder="" value="<?php echo $cargo8['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn14" value="<?php echo $cargo8['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo8['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num14">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_quince" id="txt_quince_id" placeholder="" value="<?php echo $cargo9['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn15" value="<?php echo $cargo9['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo9['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num15">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_diez_y_seis" id="txt_diez_y_seis_id" placeholder="" value="<?php echo $cargo10['tarifa_cargo']; ?>">
                        <input type="hidden" name="txt_hidden_btn16" value="<?php echo $cargo10['id_CargoCotizacion']; ?>">
                        <label for="floatingInput"><?php echo $cargo10['nombre_cargo_cotizacion']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num16">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
        </div>
        <br><br>

        <div class="container">
            <h2>Recargos entre semana</h2>
            <div class="row g-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo1" id="txt_nombre_Recargo1" placeholder="" value="<?php echo $recargos1['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn1" value="<?php echo $recargos1['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos1['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos1">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo2" id="txt_nombre_Recargo2" placeholder="" value="<?php echo $recargos2['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn2" value="<?php echo $recargos2['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos2['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos2">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo3" id="txt_nombre_Recargo3" placeholder="" value="<?php echo $recargos3['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn3" value="<?php echo $recargos3['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos3['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos3">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
        </div>
        <br><br>
        <div class="container">
            <h2>Recargos Dominicales</h2>
            <div class="row g-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo4" id="txt_nombre_Recargo4" placeholder="" value="<?php echo $recargos4['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn4" value="<?php echo $recargos4['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos4['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos4">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo5" id="txt_nombre_Recargo5" placeholder="" value="<?php echo $recargos5['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn5" value="<?php echo $recargos5['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos5['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos5">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo6" id="txt_nombre_Recargo6" placeholder="" value="<?php echo $recargos6['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn6" value="<?php echo $recargos6['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos6['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos6">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_Recargo7" id="txt_nombre_Recargo7" placeholder="" value="<?php echo $recargos7['valorRecargo']; ?>">
                        <input type="hidden" name="txt_hidden_Recargosbtn7" value="<?php echo $recargos7['idRecargos']; ?>">
                        <label for="floatingInput"><?php echo $recargos7['nombreRecargo']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Recargos7">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

            </div>
        </div>
        <br><br>
        <br><br>
        <div class="container">
            <h2>Horas Diurnas</h2>
            <div class="row g-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="time" class="form-control" name="txt_nombre_Hora1" id="txt_nombre_Hora1" placeholder="" value="<?php echo $horas1['horaJornada']; ?>">
                        <input type="hidden" name="txt_hidden_Horabtn1" value="<?php echo $horas1['idHoras']; ?>">
                        <label for="floatingInput"><?php echo $horas1['nombreJornada']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Hora1">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="time" class="form-control" name="txt_nombre_Hora2" id="txt_nombre_Hora2" placeholder="" value="<?php echo $horas2['horaJornada']; ?>">
                        <input type="hidden" name="txt_hidden_Horabtn2" value="<?php echo $horas2['idHoras']; ?>">
                        <label for="floatingInput"><?php echo $horas2['nombreJornada']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Hora2">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
            </div>
        </div>
    <br><br>
        <div class="container">
            <h2>Horas Nocturas</h2>
            <div class="row g-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="time" class="form-control" name="txt_nombre_Hora3" id="txt_nombre_Hora3" placeholder="" value="<?php echo $horas3['horaJornada']; ?>">
                        <input type="hidden" name="txt_hidden_Horabtn3" value="<?php echo $horas3['idHoras']; ?>">
                        <label for="floatingInput"><?php echo $horas3['nombreJornada']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Hora3">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="time" class="form-control" name="txt_nombre_Hora4" id="txt_nombre_Hora4" placeholder="" value="<?php echo $horas4['horaJornada']; ?>">
                        <input type="hidden" name="txt_hidden_Horabtn4" value="<?php echo $horas4['idHoras']; ?>">
                        <label for="floatingInput"><?php echo $horas4['nombreJornada']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_Hora4">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>

            </div>
        </div>
        <input type="hidden" name="menu" value="variables">

    </form>
    <br><br><br>


    <script src="../js/alertsKam.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>