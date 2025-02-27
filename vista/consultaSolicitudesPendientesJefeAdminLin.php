<?php
require_once '../configuracion/auth.php';
verificarAutenticacion();
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
    <style>
        
        /* Estilos para el menú de navegación */
        .menu-navegacion {
            background-color:white;
            padding: 15px 0;
            text-align: center;
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 20px;
        }
         h2{
            text-align: center;
            
        }
        .menu-navegacion ul {
            display: flex;
            justify-content: center;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        
        .menu-navegacion li {
            margin: 0 15px;
        }
        
        .menu-navegacion a {
            display: block;
            padding: 10px 20px;
            background-color:rgb(3, 59, 94);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        
        .menu-navegacion a:hover {
            background-color: #002d4b;
        }
        
        /* Estilos para las secciones */
        .seccion {
            padding: 20px;
            margin-bottom: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        /* Para ocultar inicialmente las secciones excepto la primera */
        #seccion-aprobadas, #seccion-todas {
            display: none;
        }
        
.filtro-container {
    position: absolute;
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    padding: 10px;
    display: none; /* Oculto inicialmente */
}

.filtro-container select {
    width: 100%;
}
    </style>
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
    <br><br><br>
 <!-- Menú de navegación horizontal -->
 <div class="menu-navegacion">
 <ul>
    <li><a href="#" id="menu-pendientes" class="menu-activo">Solicitudes Pendientes</a></li>
    <li><a href="#" id="menu-aprobadas">Solicitudes Aprobadas</a></li>
    <li><a href="#" id="menu-todas">Todas las Solicitudes</a></li>
</ul>
    </div>

    <div id="seccion-pendientes" class="container">
    <div class="container_table">
        <br><br>
        <h2>Listado de solicitudes Pendientes</h2>
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
        </div>
    </form>
</div>

<div id="seccion-aprobadas" class="container">
    <div class="container_table">
        <br><br>
        <h2>Listado de solicitudes aprobadas</h2>
        <br>
        
        <form id="filter-form">
            <div class="form-floating tma">
                <input type="date" class="form-control" name="fechaAprobadas" id="fechaAprobadas" placeholder="" required>
                <label for="floatingInput">Elije la fecha de inicio</label>
            </div>
        </form>
        <br>
        <div class="form-floating tma">
            <input type="text" class="form-control" id="buscarAprobadas" placeholder="Buscar...">
            <label for="buscarAprobadas">Buscar...</label>
        </div>
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
</div>

<div id="seccion-todas" class="container">
    <br><br>
    <h2>Listado de todas las solicitudes</h2>
    <br>
    <form id="filter-form">
        <div class="form-floating tma">
            <input type="date" class="form-control" name="fecha" id="fecha" placeholder="" required>
            <label for="floatingInput">Elije la fecha de inicio</label>
        </div>
    </form>
    <br>
    <div class="form-floating tma">
        <input type="text" class="form-control" id="buscarTodas" placeholder="Buscar...">
        <label for="buscarTodas">Buscar...</label>
    </div>
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
                    <th scope="col">Fecha inicio</th>
                    <th scope="col">Fecha fin</th>
                    <th scope="col">Dias compensados</th>
                    <th scope="col">Cantidad dias compensados</th>
                    <th scope="col">Total horas</th>
                    <th scope="col">Motivo</th>
                    <th scope="col">Remuneración</th>
                    <th scope="col">
    Estado
    <i class="fas fa-chevron-down" id="toggleFiltroEstado" style="cursor: pointer;"></i>
    <div id="filtroEstadoContainer" class="filtro-container">
        <select id="filtroEstado" class="form-select">
            <option value="">Todos</option>
            <option value="Enviado">Enviado</option>
            <option value="No Aprobado">No Aprobado</option>
            <!-- Añade más estados según los que uses en tu sistema -->
        </select>
    </div>
</th>
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
       
        
    

    <br><br>
    <br><br>
    <script src="../js/JavaScriptSeleccionarSolicitudIntranetLina.js"></script>
    <script src="../js/alertsKam.js"></script>
    <script src="../js/filtroFecha.js"></script>
    <script src="../js/filtroFechaAprobadas.js"></script>
    <script src="../js/filtroFechaPendientes.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    // Manejador para el ícono que muestra/oculta el filtro sin requerir segundo clic
document.getElementById('toggleFiltroEstado').addEventListener('click', function(event) {
    event.stopPropagation(); // Evita que el clic se propague y active otros eventos
    var filtroContainer = document.getElementById('filtroEstadoContainer');
    if (filtroContainer.style.display !== 'block') {
         filtroContainer.style.display = 'block';
    } else {
         filtroContainer.style.display = 'none';
    }
});

// Manejador para aplicar el filtro cuando se cambia el valor del select
document.getElementById('filtroEstado').addEventListener('change', function() {
    var value = this.value.toLowerCase();
    var rows = document.querySelectorAll('#table_permisos_completos tr');
    rows.forEach(function(row) {
        // Se asume que la columna de estado es la 14ª celda
        var estado = row.querySelector('td:nth-child(14)').textContent.toLowerCase();
        if (value === "" || estado.includes(value)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

// Cerrar el contenedor de filtro si se hace clic fuera de él
document.addEventListener('click', function(event) {
    var filtroContainer = document.getElementById('filtroEstadoContainer');
    var toggleButton = document.getElementById('toggleFiltroEstado');
    
    if (!filtroContainer.contains(event.target) && event.target !== toggleButton) {
        filtroContainer.style.display = 'none';
    }
});

$(document).ready(function(){
    // Mostrar pendientes y ocultar las demás secciones
    $('#menu-pendientes').click(function(e){
        e.preventDefault();
        console.log('Sección pendiente clickeada');
        $('#seccion-pendientes').show();
        $('#seccion-aprobadas').hide();
        $('#seccion-todas').hide();
        $('.menu-navegacion a').removeClass('menu-activo');
        $(this).addClass('menu-activo');
    });

    // Mostrar aprobadas y ocultar las demás secciones
    $('#menu-aprobadas').click(function(e){
        e.preventDefault();
        console.log('Sección aprobadas clickeada');
        $('#seccion-pendientes').hide();
        $('#seccion-aprobadas').show();
        $('#seccion-todas').hide();
        $('.menu-navegacion a').removeClass('menu-activo');
        $(this).addClass('menu-activo');
    });

    // Mostrar todas y ocultar las demás secciones
    $('#menu-todas').click(function(e){
        e.preventDefault();
        console.log('Sección todas clickeada');
        $('#seccion-pendientes').hide();
        $('#seccion-aprobadas').hide();
        $('#seccion-todas').show();
        $('.menu-navegacion a').removeClass('menu-activo');
        $(this).addClass('menu-activo');
    });
});

$(document).ready(function() {
    // Función de búsqueda para el listado de solicitudes aprobadas
    $("#buscarAprobadas").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_body_select_Aprobadas tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // Función de búsqueda para el listado de todas las solicitudes
    $("#buscarTodas").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#table_permisos_completos tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});

</script>
</body>

</html>