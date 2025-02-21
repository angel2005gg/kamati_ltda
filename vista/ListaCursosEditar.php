<?php
require_once '../controlador/ControladorCursoUsuario.php';
require_once '../modelo/Usuarios.php';
require_once '../configuracion/auth.php';

verificarAutenticacion();

// Obtener id y objeto usuario de la sesión
$id_usuario = $_SESSION['idUser'];
$usuario = $_SESSION['user'];

// Por defecto, asumimos que es un usuario normal (no contratista)
$_SESSION['tipoUsuario'] = 'usuario';

// Crear una instancia del modelo CursoUsuario
$cursoUsuarioModel = new CursoUsuario();

// Obtener los cursos asociados al ID del usuario
$cursosUsuarios = $cursoUsuarioModel->obtenerCursosPorUsuario($id_usuario);

// Definir la función calcularEstado
function calcularEstado($fecha_inicio, $fecha_fin) {
    $fecha_actual = new DateTime();
    $fecha_fin_dt = new DateTime($fecha_fin);
    $intervalo = $fecha_actual->diff($fecha_fin_dt)->days;
    
    if ($fecha_actual > $fecha_fin_dt) {
        return ['estado' => 'Vencido', 'clase' => 'estado-vencido'];
    } elseif ($intervalo <= 20) {
        return ['estado' => 'A vencer', 'clase' => 'estado-a-vencer'];
    } else {
        return ['estado' => 'Vigente', 'clase' => 'estado-vigente'];
    }
}

// Verificar autenticación
if (!isset($_SESSION['user'])) {
    die("Error: No se ha iniciado sesión.");
}

$rolUsuario = $usuario->getId_Rol_Usuario();
if ($rolUsuario === null) {
    die("Error: No se pudo determinar el rol del usuario.");
}

// Incluir el archivo de navegación correcto según el rol del usuario
switch ($rolUsuario) {
    case 1: // Admin
        if ($usuario->getId_Usuarios() === 5) {
            include 'navBarJefeAdminLin.php';
        } else {
            include 'navBarAdmin.php';
        }
        break;
    case 2: // Jefe
        if ($usuario->getId_Usuarios() === 4) {
            include 'navBarJefeAdminL.php';
        } else if ($usuario->getId_Usuarios() === 9) {
            include 'navBarJefeAdmin.php';
        } else if ($usuario->getId_Usuarios() === 1) {
            include 'navBarJefeHelmer.php';
        } else if ($usuario->getId_Usuarios() === 11) {
            include 'navBarJefeCielo.php';
        } else if ($usuario->getNombre_area() === 'Comercial') {
            if ($usuario->getId_Usuarios() === 7 || $usuario->getId_Usuarios() === 10) {
                include 'navBarJefeComercial.php';
            } else {
                include 'navBarJefe.php';
            }
        } else {
            include 'navBarJefe.php';
        }
        break;
    case 3: // Trabajador
        if ($usuario->getNombre_area() === 'Comercial') {
            if ($usuario->getId_Usuarios() === 84) {
                include 'navBarJefeComercial.php';
            } else {
                include 'navBarTrabajadorComercial.php';
            }
        } else if ($usuario->getId_Usuarios() === 47) {
            include 'navBarTrabajadorLuz.php';
        } else {
            include 'navBarTrabajador.php';
        }
        break;
    default:
        include 'navBar.php';
        break;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Cursos de Usuarios</title>
    <style>
        tr:nth-child(odd) { background-color: #fff; }
        .estado-vigente { color: green; }
        .estado-a-vencer { color: orange; }
        .estado-vencido { color: red; }
        .search-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }
        .search-container select {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ddd;
            min-width: 150px;
        }
        .filter-label {
            font-weight: bold;
            margin-right: 5px;
        }
        tr.fila-vigente td { background-color:rgb(186, 255, 186) !important; } /* Verde claro */
        tr.fila-a-vencer td { background-color:rgb(255, 255, 181) !important; } /* Amarillo claro */
        tr.fila-vencido td { background-color:rgb(255, 189, 189) !important; } /* Rojo claro */
    </style>
</head>
<body>
    <br><br><br>
    <div class="container mt-4">
        <h2>Tus cursos actuales</h2>
        <br>
        <div class="search-container">
            
            <div>
                <span class="filter-label">Curso:</span>
                <select id="filtroCurso">
                    <option value="">Todos los cursos</option>
                </select>
            </div>
            <div>
                <span class="filter-label">Empresa:</span>
                <select id="filtroEmpresa">
                    <option value="">Todas las empresas</option>
                </select>
            </div>
            <div>
                <span class="filter-label">Estado:</span>
                <select id="filtroEstado">
                    <option value="">Todos los estados</option>
                    <option value="Vigente">Vigente</option>
                    <option value="A vencer">A vencer</option>
                    <option value="Vencido">Vencido</option>
                </select>
                
            </div>
            <div>
    <button id="limpiarFiltros" class="btn btn-secondary" style="padding: 8px 15px; margin-left: 10px;">
        Limpiar Filtros
    </button>
</div>
        </div>
        
        <table class="table table-bordered" id="tablaUsuarios">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Área</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Curso</th>
                    <th>Empresa</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($cursosUsuarios)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron resultados.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cursosUsuarios as $cursoUsuario): 
                        // Solo mostrar cursos que no tengan tipo definido (legacy) o que sean tipo 'usuario'
                        if (isset($cursoUsuario['tipo']) && $cursoUsuario['tipo'] === 'contratista') {
                            continue;
                        }
                        $estado = calcularEstado($cursoUsuario['fecha_inicio'], $cursoUsuario['fecha_fin']);
                    ?>
                        <tr class="fila-<?php echo strtolower(str_replace(' ', '-', $estado['estado'])); ?>">
                            <td><?php echo htmlspecialchars($cursoUsuario['nombre_usuario'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['area'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['fecha_inicio'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['fecha_fin'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['nombre_curso'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($cursoUsuario['empresa'] ?? 'N/A'); ?></td>
                            <td class="<?php echo $estado['clase']; ?>"><?php echo $estado['estado']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function(){
            // Obtener todos los cursos y empresas únicos para los filtros
            const filas = document.querySelectorAll("#tablaUsuarios tbody tr");
            const cursos = new Set();
            const empresas = new Set();
            
            filas.forEach(fila => {
                const curso = fila.cells[4].textContent.trim();
                const empresa = fila.cells[5].textContent.trim();
                if (curso !== 'N/A') cursos.add(curso);
                if (empresa !== 'N/A') empresas.add(empresa);
            });

            // Llenar los selectores de filtros
            const filtroCurso = document.getElementById('filtroCurso');
            const filtroEmpresa = document.getElementById('filtroEmpresa');

            cursos.forEach(curso => {
                const option = new Option(curso, curso);
                filtroCurso.add(option);
            });

            empresas.forEach(empresa => {
                const option = new Option(empresa, empresa);
                filtroEmpresa.add(option);
            });

            // Función para filtrar la tabla
            function filtrarTabla() {
                const cursoBuscado = filtroCurso.value.toLowerCase();
                const empresaBuscada = filtroEmpresa.value.toLowerCase();
                const estadoBuscado = filtroEstado.value;

                filas.forEach(fila => {
                    const curso = fila.cells[4].textContent.toLowerCase();
                    const empresa = fila.cells[5].textContent.toLowerCase();
                    const estado = fila.cells[6].textContent;

                    const coincideCurso = !cursoBuscado || curso.includes(cursoBuscado);
                    const coincideEmpresa = !empresaBuscada || empresa.includes(empresaBuscada);
                    const coincideEstado = !estadoBuscado || estado === estadoBuscado;

                    fila.style.display = coincideCurso && coincideEmpresa && coincideEstado ? "" : "none";
                });
            }

            // Agregar eventos de cambio a los filtros
            filtroCurso.addEventListener("change", filtrarTabla);
            filtroEmpresa.addEventListener("change", filtrarTabla);
            filtroEstado.addEventListener("change", filtrarTabla);

            // Agregar función para el botón de limpiar filtros
const btnLimpiar = document.getElementById('limpiarFiltros');
btnLimpiar.addEventListener('click', function() {
    // Recargar la página actual
    window.location.reload();
});
        });
    </script>
</body>
</html>