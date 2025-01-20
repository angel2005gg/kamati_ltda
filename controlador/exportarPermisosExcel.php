<?php
// Incluir el autoload de Composer
require '../vendor/autoload.php';

// Incluir la biblioteca PHPSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat; // Agregar para dar formato a las fechas

// Incluir tu modelo o la clase que contiene la conexión a la base de datos
require_once '../modelo/dao/PermisosDao.php';  // Asegúrate de que la ruta sea correcta

class ExportarPermisos
{
    public function exportarPermisosExcel()
    {
        try {
            // Crear una instancia del modelo
            $modelo = new PermisosDao(); // Asegúrate de usar el modelo correcto
            $datos = $modelo->consultarPermisoSolicitadoExcel();

            if (empty($datos)) {
                throw new Exception('No hay datos para exportar.');
            }

            // Crear una nueva hoja de cálculo
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Encabezados de la tabla
            $encabezados = [
                'A1' => 'Primer Nombre',
                'B1' => 'Primer Apellido',
                'C1' => 'Segundo Nombre',
                'D1' => 'Segundo Apellido',
                'E1' => 'Número de Identificación',
                'F1' => 'Sede Laboral',
                'G1' => 'Fecha Elaboración',
                'H1' => 'Tipo Permiso',
                'I1' => 'Tiempo',
                'J1' => 'Cantidad de Tiempo',
                'K1' => 'Fecha Inicio Novedad',
                'L1' => 'Fecha Fin Novedad',
                'M1' => 'Días Compensados',
                'N1' => 'Cantidad Días Compensados',
                'O1' => 'Total Horas Permiso',
                'P1' => 'Motivo Novedad',
                'Q1' => 'Remuneración',
                'R1' => 'Estado Permiso',
                'S1' => 'ID Permisos',
                'T1' => 'Nombre Cargo',
                'U1' => 'Nombre Área'
            ];

            foreach ($encabezados as $columna => $valor) {
                $sheet->setCellValue($columna, $valor);
            }

            // Agregar datos a la tabla
            $fila = 2;
            foreach ($datos as $dato) {
                $sheet->setCellValue('A' . $fila, htmlspecialchars($dato['primer_nombre']));
                $sheet->setCellValue('B' . $fila, htmlspecialchars($dato['primer_apellido']));
                $sheet->setCellValue('C' . $fila, htmlspecialchars($dato['segundo_nombre']));
                $sheet->setCellValue('D' . $fila, htmlspecialchars($dato['segundo_apellido']));
                $sheet->setCellValue('E' . $fila, htmlspecialchars($dato['numero_identificacion']));
                $sheet->setCellValue('F' . $fila, htmlspecialchars($dato['sede_laboral']));

                // Formato de fecha para las columnas de fecha
                $fechaElaboracion = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($dato['fecha_elaboracion']));
                $sheet->setCellValue('G' . $fila, $fechaElaboracion);
                $sheet->getStyle('G' . $fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

                $fechaInicioNovedad = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($dato['fecha_inicio_novedad']));
                $sheet->setCellValue('K' . $fila, $fechaInicioNovedad);
                $sheet->getStyle('K' . $fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

                $fechaFinNovedad = \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(strtotime($dato['fecha_fin_novedad']));
                $sheet->setCellValue('L' . $fila, $fechaFinNovedad);
                $sheet->getStyle('L' . $fila)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DDMMYYYY);

                $sheet->setCellValue('H' . $fila, htmlspecialchars($dato['tipo_permiso']));
                $sheet->setCellValue('I' . $fila, htmlspecialchars($dato['tiempo']));
                $sheet->setCellValue('J' . $fila, htmlspecialchars($dato['cantidad_tiempo']));
                $sheet->setCellValue('M' . $fila, htmlspecialchars($dato['dias_compensados']));
                $sheet->setCellValue('N' . $fila, htmlspecialchars($dato['cantidad_dias_compensados']));
                $sheet->setCellValue('O' . $fila, htmlspecialchars($dato['total_horas_permiso']));
                $sheet->setCellValue('P' . $fila, htmlspecialchars($dato['motivo_novedad']));
                $sheet->setCellValue('Q' . $fila, htmlspecialchars($dato['remuneracion']));
                $sheet->setCellValue('R' . $fila, htmlspecialchars($dato['estado_permiso']));
                $sheet->setCellValue('S' . $fila, htmlspecialchars($dato['id_Permisos']));
                $sheet->setCellValue('T' . $fila, htmlspecialchars($dato['nombre_cargo']));
                $sheet->setCellValue('U' . $fila, htmlspecialchars($dato['nombre_area']));
                $fila++;
            }

            // Crear el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $filename = 'permisos.xlsx';

            // Enviar el archivo al navegador para descargar
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        } catch (Exception $e) {
            // Manejo de errores
            error_log($e->getMessage());
            echo 'Ocurrió un error al generar el archivo Excel.';
        }
    }
}

// Validar la solicitud POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Crear una instancia de la clase y llamar al método para exportar
    $exportarPermisos = new ExportarPermisos();
    $exportarPermisos->exportarPermisosExcel();
} else {
    echo 'Método no permitido.';
}
?>