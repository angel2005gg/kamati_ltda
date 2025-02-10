<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
require_once '../modelo/dao/VariablesDao.php';
require_once '../modelo/Variables.php';
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

    include "navBarJefeAdminL.php";
    $variablesDao = new VariablesDao();
    $variabless1 = new Variables();
    $variabless2 = new Variables();
    $variabless3 = new Variables();

    $variabless1 = $variablesDao->consultaVariables(1);
    $variabless2 = $variablesDao->consultaVariables(2);
    $variabless3 = $variablesDao->consultaVariables(3);

    ?>
    <br><br><br><br><br>
    

    <div id="alertContainer"></div>
    <form action="../controlador/ServletVariablesL.php" method="POST">
        <div class="container">
            <div class="row g-3">
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_cargo0" id="txt_nombre_cargo0" placeholder=""  value="<?php echo $variabless1['valor_variable']; ?>">
                        <input type="hidden" name="txt_hidden_btn1" value="<?php echo $variabless1['id_Variables']; ?>">
                        <label for="floatingInput"><?php echo $variabless1['nombre_variables']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num1">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_cargo1" id="txt_nombre_cargo1" placeholder=""  value="<?php echo $variabless2['valor_variable']; ?>">
                        <input type="hidden" name="txt_hidden_btn2" value="<?php echo $variabless2['id_Variables']; ?>">
                        <label for="floatingInput"><?php echo $variabless2['nombre_variables']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num2">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <div class="col-md">
                    <div class="form-floating">
                        <input type="text" class="form-control" name="txt_nombre_cargo2" id="txt_nombre_cargo2" placeholder="" value="<?php echo $variabless3['valor_variable']; ?>">
                        <input type="hidden" name="txt_hidden_btn3" value="<?php echo $variabless3['id_Variables']; ?>">
                        <label for="floatingInput"><?php echo $variabless3['nombre_variables']; ?></label>
                    </div>
                </div>
                <div class="col-md">
                    <button type="submit" class="btn btn-primary" name="btn_update_num3">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                </div>
                <input type="hidden" name="menu" value="variables">
            </div>
        </div>

    </form>


    <script src="../js/alertsKam.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>