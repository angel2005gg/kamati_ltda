<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
require_once '../modelo/dao/CargoDao.php';
require_once '../modelo/dao/AreaDao.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cargos kamati</title>
    <link rel="icon" type="image/png" href="../img/logo.png">
    <link rel="stylesheet" href="../css/styleKamaInitrApis.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php
    include "navBar.php";
    $cargo = new CargoDao();
    $data = $cargo->consultarCargoTabla();

    $area = new AreaDao();
    $nombreArea = $area->consultaAreasTabla();
    ?>
    <div id="alertContainer"></div>
    <div class="container">
        <div class="container_table">
            <br><br><br><br>
            <h2>Listado de cargos</h2>
            <br>


            <div class="form-floating tma">
                <input type="text" class="form-control" name="txt_filtro_cargo" id="txt_filtro_cargo" placeholder="">
                <label for="floatingInput">Ej Aprendiz..</label>
            </div>
            <div class="tabla_solicitudes">
                <table class="table-responsive">
                    <thead>
                        <tr class="tr_class">
                            <th scope="col">Nombre cargo</th>
                            <th scope="col">Area a la que pertenece</th>
                            <th scope="col">Estado cargo</th>
                            <th scope="col">Seleccionar</th>
                        </tr>
                    </thead>
                    <tbody id="table_body_cargo">
                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['nombre_cargo']); ?></td>
                                <td><?php echo htmlspecialchars($row['nombre_area']); ?></td>
                                <td><?php echo htmlspecialchars($row['estado_cargo']); ?></td>
                                <td><button type="button" class="button_color seleccionar" data-id="<?php echo htmlspecialchars($row['id_Cargo']); ?>">Seleccionar</button></td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br><br>
        <form method="POST" action="../controlador/ServletCargosAdmin.php">
            <div class="container" id="container">
                <div class="formulario_de_registro_usuario_intranet">
                    <h2>Cargos</h2>
                    <br><br>
                    <div>
                        <h5>Datos del cargo</h5>
                        <br>
                        <div class="row g-3">
                            <div class="col-md">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="txt_nombre_cargo" id="txt_nombre_cargo" placeholder="">
                                    <input type="hidden" class="form-control" name="txt_hidden_id" id="txt_hidden_id">
                                    <label for="floatingInput">Nombre del cargo</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" name="txt_nombre_area" id="txt_nombre_area">
                                        <?php foreach ($nombreArea as $id => $nombre) : ?>
                                            <option value="<?php echo $id; ?>"><?php echo $nombre; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label for="floatingSelectGrid">Nombre Area</label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-floating">
                                    <select class="form-select" name="txt_estado_cargo" id="txt_estado_cargo">
                                        <option selected>selecciona el estado del cargo</option>
                                        <option value="Activo">Activo</option>
                                        <option value="Inactivo">Inactivo</option>
                                    </select>
                                    <label for="floatingSelectGrid">Estado cargo</label>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div class="row g-3">
                            <div class="col-md centro">
                                <div class="form-floating">
                                    <input type="hidden" name="menu" value="cargos">
                                    <input type="submit" class="ingreso_submit" name="accion" value="Registrar">
                                    <input type="submit" class="ingreso_submit" name="accion" value="Actualizar">
                                    <input type="submit" class="ingreso_submit" name="accion" value="Regresar">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="../js/JavaScriptSeleccionarCargoJefeAdminLin.js"></script>
    <script src="../js/alertsKam.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>