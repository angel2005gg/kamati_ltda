<?php
require '../controlador/vendor/autoload.php'; // Incluir PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Obtener los datos enviados desde JavaScript
$data = json_decode(file_get_contents('php://input'), true);

// Crear una nueva instancia de Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$columnWidth = 15;

// Recorre desde la columna 'A' hasta la 'Z' (o más si es necesario)
foreach (range('A', 'Z') as $columnID) {
    $sheet->getColumnDimension($columnID)->setWidth($columnWidth);
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$borderStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['argb' => 'FF000000'], // Color negro
        ],
    ],
];
$backgroundStyle = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FFC1CBF5', // Código de color en formato ARGB
        ],
    ],
];
$backgroundStyleBlue = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => [
            'argb' => 'FF002D4B', // Código de color en formato ARGB
        ],
    ],
];

$styleArray = [
    'font' => [
        'bold' => true,
        'size' => 12,
        'color' => ['argb' => 'FFFFFFFF'], // Blanco en ARGB
    ],
];
//Colocar tamaño 12 negrilla y color blanco
$styleArrayBlanco = [
    'font' => [
        'bold' => true,
        'size' => 12,
        'color' => ['argb' => 'FFFFFFFF'], // Blanco en ARGB
    ],
];
//Colocar al medio y en el centro 
$alignmentStyle = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
    ],
];


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$materialesDatos = $data['materialesDatos'];
$maquinariaDatos = $data['maquinariaDatos'];
$contenedoresArray = $data['contenedoresArray'];

$viaticosDataAc = $data['viaticosAc'];
$cargoDataAc = $data['cargosAc'];

// Escribir el título de la clase h2_separado
$rowCount = 1; // Comenzamos desde la fila 1
$sheet->setCellValue('A' . $rowCount, $data['titulo']);
$sheet->getStyle('A' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$sheet->getStyle('A' . $rowCount)->getFont()->setSize(18);
// Título de h2_separado
$rowCount++;

// Escribir los valores de los 4 campos de manera vertical
$sheet->setCellValue('A' . $rowCount, 'Fecha Actual');
$sheet->setCellValue('B' . $rowCount, $data['campo1']);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
$rowCount++;

$sheet->setCellValue('A' . $rowCount, 'Cliente');
$sheet->setCellValue('B' . $rowCount, $data['campo2']);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
$rowCount++;

$sheet->setCellValue('A' . $rowCount, 'Nombre proyecto');
$sheet->setCellValue('B' . $rowCount, $data['campo3']);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
$rowCount++;

$sheet->setCellValue('A' . $rowCount, 'Código proyecto');
$sheet->setCellValue('B' . $rowCount, $data['campo4']);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
$rowCount++;

// Escribir los valores de los campos horizontales
$sheet->setCellValue('A' . $rowCount, 'TRM USD -> COP');
$sheet->setCellValueExplicit('B' . $rowCount, $data['txtNombre'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('C' . $rowCount, 'TRM EUR -> COP');
$sheet->setCellValueExplicit('D' . $rowCount, $data['txtIdentificacion'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('E' . $rowCount, 'Dolar a asignar al proyecto');
$sheet->setCellValueExplicit('F' . $rowCount, $data['txtIdentificacionUsd'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('G' . $rowCount, 'Euro a asignar al proyecto');
$sheet->setCellValueExplicit('H' . $rowCount, $data['txtIdentificacionEur'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($borderStyle);
$sheet->getStyle('A' . $rowCount . ':H' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
$rowCount++;

// Escribir los nuevos valores de manera horizontal
$sheet->setCellValue('A' . $rowCount, 'MO');
$sheet->setCellValueExplicit('B' . $rowCount, $data['factorMoGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('C' . $rowCount, 'Otros');
$sheet->setCellValueExplicit('D' . $rowCount, $data['factorOGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('E' . $rowCount, 'Viaticos');
$sheet->setCellValueExplicit('F' . $rowCount, $data['viaticosGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('G' . $rowCount, 'Póliza');
$sheet->setCellValueExplicit('H' . $rowCount, $data['polizaGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('I' . $rowCount, 'Siemens');
$sheet->setCellValueExplicit('J' . $rowCount, $data['siemensGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('K' . $rowCount, 'Pilz');
$sheet->setCellValueExplicit('L' . $rowCount, $data['pilzGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('M' . $rowCount, 'Rittal');
$sheet->setCellValueExplicit('N' . $rowCount, $data['rittalGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->setCellValue('O' . $rowCount, 'Phoenix Contact');
$sheet->setCellValueExplicit('P' . $rowCount, $data['phoenixGlobal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$sheet->getStyle('A' . $rowCount . ':P' . $rowCount)->applyFromArray($borderStyle);
$sheet->getStyle('A' . $rowCount . ':P' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
$rowCount = 9; // Fila específica para Viáticos

// Escribir los datos de la tabla de Viáticos
$sheet->setCellValue('A' . $rowCount, 'Viáticos');
$sheet->setCellValue('B' . $rowCount, 'Valor');
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($styleArrayBlanco);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($backgroundStyleBlue);
$sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($alignmentStyle);
$rowCount++;

$viaticosData = $data['viaticos'];
foreach ($viaticosData as $viatico) {
    $sheet->setCellValue('A' . $rowCount, $viatico['nombre']);
    $sheet->setCellValue('B' . $rowCount, $viatico['valor']);
    $sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);
    $rowCount++;
}

$rowCount = 9; // Volver a la fila 9 para la columna E

// Escribir los datos de la tabla de Cargos
$sheet->setCellValue('E' . $rowCount, 'Cargos');
$sheet->setCellValue('F' . $rowCount, 'Valor');
$sheet->getStyle('E' . $rowCount . ':F' . $rowCount)->applyFromArray($styleArrayBlanco);
$sheet->getStyle('E' . $rowCount . ':F' . $rowCount)->applyFromArray($backgroundStyleBlue);
$sheet->getStyle('E' . $rowCount . ':F' . $rowCount)->applyFromArray($alignmentStyle);
$rowCount++;

$cargosData = $data['cargos'];
foreach ($cargosData as $cargo) {
    $sheet->setCellValue('E' . $rowCount, $cargo['nombre']);
    $sheet->setCellValue('F' . $rowCount, $cargo['valor']);
    $sheet->getStyle('E' . $rowCount . ':F' . $rowCount)->applyFromArray($borderStyle);
    $rowCount++;
}

$rowCount = 21;
// Obtener los nombres de las tablas enviados desde JavaScript
$nombreTablaMateriales = $data['nombreTablaMateriales'];

// Suponemos que ya tienes los datos de 'materialesDatos' disponibles en PHP
foreach ($nombreTablaMateriales as $index => $nombreTabla) {
    // Escribir "Materiales"
    $sheet->setCellValue('B' . $rowCount, 'Materiales');
    $sheet->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
    $sheet->getStyle('B' . $rowCount)->getFont()->setSize(16);
    $rowCount++;

    // Escribir "Nombre Tabla" y su valor
    $sheet->setCellValue('B' . $rowCount, 'Nombre Tabla:');
    $sheet->setCellValue('C' . $rowCount, $nombreTabla);
    $sheet->setCellValue('F' . $rowCount, $data['inputFactorIndepedienteMateriales'][$index]);
    $sheet->getStyle('F' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
    $sheet->getStyle('F' . $rowCount)->getFont()->setSize(14);
    $rowCount++;

    // Acceder a los factores según el índice
    $sheet->setCellValue('A' . $rowCount, 'MO');
    $sheet->setCellValueExplicit('B' . $rowCount, $data['factorMo'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('C' . $rowCount, 'Otros');
    $sheet->setCellValueExplicit('D' . $rowCount, $data['factorO'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('E' . $rowCount, 'Viáticos');
    $sheet->setCellValueExplicit('F' . $rowCount, $data['factorv'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('G' . $rowCount, 'Póliza');
    $sheet->setCellValueExplicit('H' . $rowCount, $data['factorPo'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('I' . $rowCount, 'Siemens');
    $sheet->setCellValueExplicit('J' . $rowCount, $data['factorSm'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('K' . $rowCount, 'Pilz');
    $sheet->setCellValueExplicit('L' . $rowCount, $data['factorPilz'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('M' . $rowCount, 'Rittal');
    $sheet->setCellValueExplicit('N' . $rowCount, $data['factorRt'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('O' . $rowCount, 'Phoenix Contact');
    $sheet->setCellValueExplicit('P' . $rowCount, $data['factorPx'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $rowCount += 2; // Incremento para la siguiente fila

    // Escribir los encabezados
    $sheet->setCellValue('A' . $rowCount, 'Cantidad');
    $sheet->setCellValue('B' . $rowCount, 'Unid');
    $sheet->setCellValue('C' . $rowCount, 'Abreviatura Línea');
    $sheet->setCellValue('D' . $rowCount, 'Referencia');
    $sheet->setCellValue('E' . $rowCount, 'Material');
    $sheet->setCellValue('F' . $rowCount, 'Descripción Material');
    $sheet->setCellValue('G' . $rowCount, 'Proveedor');
    $sheet->setCellValue('H' . $rowCount, 'Nota');
    $sheet->setCellValue('I' . $rowCount, 'Tipo Moneda');
    $sheet->setCellValue('J' . $rowCount, 'Precio lista');
    $sheet->setCellValue('K' . $rowCount, 'Costo Unitario Kamati');
    $sheet->setCellValue('L' . $rowCount, 'Costo total kamati');
    $sheet->setCellValue('M' . $rowCount, 'Valor utilidad');
    $sheet->setCellValue('N' . $rowCount, 'Valor total');
    $sheet->setCellValue('O' . $rowCount, 'T. Entrega');
    $sheet->setCellValue('P' . $rowCount, 'Descuento(%)');
    $sheet->setCellValue('Q' . $rowCount, 'Descuento Adicional(%)');
    $sheet->setCellValue('R' . $rowCount, 'Fecha entrega');
    $sheet->setCellValue('S' . $rowCount, 'Rep');
    $sheet->setCellValue('T' . $rowCount, 'Check');
    $sheet->setCellValue('U' . $rowCount, 'Fac ad');
    $sheet->getRowDimension($rowCount)->setRowHeight(35);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($styleArrayBlanco);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($backgroundStyleBlue);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($alignmentStyle);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
    $rowCount++;

    // Aquí es donde comenzamos a llenar las filas con los datos de materiales
    foreach ($materialesDatos[$index] as $material) {
        $sheet->setCellValue('A' . $rowCount, $material['cantidad']);
        $sheet->setCellValue('B' . $rowCount, $material['unidad']);
        $sheet->setCellValue('C' . $rowCount, $material['abreviatura']);
        $sheet->setCellValue('D' . $rowCount, $material['referencia']);
        $sheet->setCellValue('E' . $rowCount, $material['material']);
        $sheet->setCellValue('F' . $rowCount, $material['descripcionMaterial']);
        $sheet->setCellValue('G' . $rowCount, $material['proveedor']);
        $sheet->setCellValue('H' . $rowCount, $material['nota']);
        $sheet->setCellValue('I' . $rowCount, $material['trm']);
        $sheet->setCellValue('J' . $rowCount, $material['precioLista']);
        $sheet->setCellValue('K' . $rowCount, $material['costoUnitarioKamati']);
        $sheet->setCellValue('L' . $rowCount, $material['costoTotalKamati']);
        $sheet->setCellValue('M' . $rowCount, $material['valorUtilidad']);
        $sheet->setCellValue('N' . $rowCount, $material['valorTotal']);
        $sheet->setCellValue('O' . $rowCount, $material['tiempoEntrega']);
        $sheet->setCellValue('P' . $rowCount, $material['descuento']);
        $sheet->setCellValue('Q' . $rowCount, $material['descuentoAdicional']);
        $sheet->setCellValue('R' . $rowCount, $material['fechaEntrega']);
        $sheet->setCellValue('S' . $rowCount, $material['rep']);
        $sheet->setCellValue('T' . $rowCount, $material['checkEstado'] ? 'Sí' : 'No');
        $sheet->setCellValue('U' . $rowCount, $material['factorAdicional']);
        $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($borderStyle);
        $rowCount++; // Incremento para la siguiente fila
    }
    $rowCount++;

    $sheet->setCellValue('A' . $rowCount, 'Total Kamati: ');
    $sheet->setCellValueExplicit('B' . $rowCount, $data['totalUnique_kamati'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('D' . $rowCount, 'Total Cliente: ');
    $sheet->setCellValueExplicit('E' . $rowCount, $data['totalUnique_cliente'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);


    $rowCount += 2;
}


$rango = 'A' . $rowCount . ':AZ' . $rowCount;

// Definir el estilo de relleno
$sheet->getStyle($rango)->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle($rango)->getFill()->getStartColor()->setARGB('007bff'); 

// También puedes ajustar otros estilos si es necesario, como el color del texto
$sheet->getStyle($rango)->getFont()->getColor()->setARGB(Color::COLOR_BLACK);

$rowCount += 2;

$nombreTablasMaquinaria = $data['nombreTablaMaquinaria'];
foreach ($nombreTablasMaquinaria as $index => $nombreTablaMaquinaria) {
    $sheet->setCellValue('B' . $rowCount, 'MAQUINARIA');
    $sheet->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
    $sheet->getStyle('B' . $rowCount)->getFont()->setSize(16);
    $rowCount++;

    // Escribir "Nombre Tabla" y su valor
    $sheet->setCellValue('B' . $rowCount, 'Nombre Tabla:');
    $sheet->setCellValue('C' . $rowCount, $nombreTablaMaquinaria);
    $sheet->setCellValue('F' . $rowCount, $data['inputFactorIndepedienteMaquinaria'][$index]);
    $sheet->getStyle('F' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
    $sheet->getStyle('F' . $rowCount)->getFont()->setSize(14);
    $rowCount++;

    // Acceder a los factores según el índice
    $sheet->setCellValue('A' . $rowCount, 'MO');
    $sheet->setCellValueExplicit('B' . $rowCount, $data['factorMoMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('C' . $rowCount, 'Otros');
    $sheet->setCellValueExplicit('D' . $rowCount, $data['factorOMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('E' . $rowCount, 'Viáticos');
    $sheet->setCellValueExplicit('F' . $rowCount, $data['factorvMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('G' . $rowCount, 'Póliza');
    $sheet->setCellValueExplicit('H' . $rowCount, $data['factorPoMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('I' . $rowCount, 'Siemens');
    $sheet->setCellValueExplicit('J' . $rowCount, $data['factorSmMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('K' . $rowCount, 'Pilz');
    $sheet->setCellValueExplicit('L' . $rowCount, $data['factorPilzMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('M' . $rowCount, 'Rittal');
    $sheet->setCellValueExplicit('N' . $rowCount, $data['factorRtMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $sheet->setCellValue('O' . $rowCount, 'Phoenix Contact');
    $sheet->setCellValueExplicit('P' . $rowCount, $data['factorPxMaquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $rowCount += 2;
    // Escribir los encabezados
    // Establece los valores en las celdas
    $sheet->setCellValue('A' . $rowCount, 'Cantidad');
    $sheet->setCellValue('B' . $rowCount, 'Unid');
    $sheet->setCellValue('C' . $rowCount, 'Abreviatura Línea');
    $sheet->setCellValue('D' . $rowCount, 'Referencia');
    $sheet->setCellValue('E' . $rowCount, 'Material');
    $sheet->setCellValue('F' . $rowCount, 'Descripción Material');
    $sheet->setCellValue('G' . $rowCount, 'Proveedor');
    $sheet->setCellValue('H' . $rowCount, 'Nota');
    $sheet->setCellValue('I' . $rowCount, 'Tipo Moneda');
    $sheet->setCellValue('J' . $rowCount, 'Precio lista');
    $sheet->setCellValue('K' . $rowCount, 'Costo Unitario Kamati');
    $sheet->setCellValue('L' . $rowCount, 'Costo total kamati');
    $sheet->setCellValue('M' . $rowCount, 'Valor utilidad');
    $sheet->setCellValue('N' . $rowCount, 'Valor total');
    $sheet->setCellValue('O' . $rowCount, 'T. Entrega');
    $sheet->setCellValue('P' . $rowCount, 'Descuento(%)');
    $sheet->setCellValue('Q' . $rowCount, 'Descuento Adicional(%)');
    $sheet->setCellValue('R' . $rowCount, 'Fecha entrega');
    $sheet->setCellValue('S' . $rowCount, 'Rep');
    $sheet->setCellValue('T' . $rowCount, 'Check');
    $sheet->setCellValue('U' . $rowCount, 'Fac ad');

    // Ajusta la altura de la fila a 35 píxeles
    $sheet->getRowDimension($rowCount)->setRowHeight(35);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($styleArrayBlanco);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($backgroundStyleBlue);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($alignmentStyle);
    $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
    $rowCount++;

    foreach ($maquinariaDatos[$index] as $material) {
        $sheet->setCellValue('A' . $rowCount, $material['cantidad']);
        $sheet->setCellValue('B' . $rowCount, $material['unidad']);
        $sheet->setCellValue('C' . $rowCount, $material['abreviatura']);
        $sheet->setCellValue('D' . $rowCount, $material['referencia']);
        $sheet->setCellValue('E' . $rowCount, $material['material']);
        $sheet->setCellValue('F' . $rowCount, $material['descripcionMaterial']);
        $sheet->setCellValue('G' . $rowCount, $material['proveedor']);
        $sheet->setCellValue('H' . $rowCount, $material['nota']);
        $sheet->setCellValue('I' . $rowCount, $material['trm']);
        $sheet->setCellValue('J' . $rowCount, $material['precioLista']);
        $sheet->setCellValue('K' . $rowCount, $material['costoUnitarioKamati']);
        $sheet->setCellValue('L' . $rowCount, $material['costoTotalKamati']);
        $sheet->setCellValue('M' . $rowCount, $material['valorUtilidad']);
        $sheet->setCellValue('N' . $rowCount, $material['valorTotal']);
        $sheet->setCellValue('O' . $rowCount, $material['tiempoEntrega']);
        $sheet->setCellValue('P' . $rowCount, $material['descuento']);
        $sheet->setCellValue('Q' . $rowCount, $material['descuentoAdicional']);
        $sheet->setCellValue('R' . $rowCount, $material['fechaEntrega']);
        $sheet->setCellValue('S' . $rowCount, $material['rep']);
        $sheet->setCellValue('T' . $rowCount, $material['checkEstado'] ? 'Sí' : 'No');
        $sheet->setCellValue('U' . $rowCount, $material['factorAdicional']);
        $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
        $sheet->getStyle('A' . $rowCount . ':U' . $rowCount)->applyFromArray($borderStyle);
        $rowCount++; // Incremento para la siguiente fila
    }
    $rowCount++;

    $sheet->setCellValue('A' . $rowCount, 'Total Kamati: ');
    $sheet->setCellValueExplicit('B' . $rowCount, $data['totalUnique_kamati_maquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);

    $sheet->setCellValue('D' . $rowCount, 'Total Cliente: ');
    $sheet->setCellValueExplicit('E' . $rowCount, $data['totalUnique_cliente_maquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->getStyle('D' . $rowCount . ':E' . $rowCount)->applyFromArray($borderStyle);

    // Incremento para la siguiente fila
    $rowCount += 2;
}
$rango = 'A' . $rowCount . ':AZ' . $rowCount;

// Definir el estilo de relleno
$sheet->getStyle($rango)->getFill()->setFillType(Fill::FILL_SOLID);
$sheet->getStyle($rango)->getFill()->getStartColor()->setARGB('007bff');

// También puedes ajustar otros estilos si es necesario, como el color del texto
$sheet->getStyle($rango)->getFont()->getColor()->setARGB(Color::COLOR_BLACK);

$rowCount += 2;

$contenedoresData = $data['contenedores'];
$tablaAc = $data['nombreTablaActividades'];
foreach ($tablaAc as $index => $nombreTablaActividad) {

    // Escribir el encabezado de la actividad
    $sheet->setCellValue('B' . $rowCount, 'ACTIVIDADES');
    $sheet->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
    $sheet->getStyle('B' . $rowCount)->getFont()->setSize(16);
    $rowCount++;

    // Escribir "Nombre Tabla" y su valor
    $sheet->setCellValue('B' . $rowCount, 'Nombre Tabla:');
    $sheet->setCellValue('C' . $rowCount, $nombreTablaActividad);
    $sheet->setCellValue('F' . $rowCount, $data['inputFactorIndepedienteActividades'][$index]);
    $sheet->getStyle('F' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
    $sheet->getStyle('F' . $rowCount)->getFont()->setSize(14);
    $rowCount++;

    $valueRowCount = $rowCount; // Guardamos el valor actual de rowCount para restablecerlo

    // Escribir los datos de los factores según el índice
    $sheet->setCellValue('A' . $rowCount, 'MO');
    $sheet->setCellValueExplicit('B' . $rowCount, $data['factorMoAc'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('C' . $rowCount, 'Otros');
    $sheet->setCellValueExplicit('D' . $rowCount, $data['factorOAc'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('E' . $rowCount, 'Viáticos');
    $sheet->setCellValueExplicit('F' . $rowCount, $data['factorvAc'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->setCellValue('G' . $rowCount, 'Póliza');
    $sheet->setCellValueExplicit('H' . $rowCount, $data['factorPoAc'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->getStyle('A' . $rowCount . ':H' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
    $rowCount++;

    if (isset($contenedoresData[$index])) {
        $contenedor = $contenedoresData[$index];
        $viaticos = $contenedor['viaticos'];
        $cargos = $contenedor['cargos'];

        // Verifica que los datos de viáticos y cargos existan
        echo "Viáticos: ";
        print_r($viaticos);
        echo "Cargos: ";
        print_r($cargos);

        // Resto del código para escribir en el Excel
        $sheet->setCellValue('I' . $rowCount, 'Viáticos');
        $sheet->setCellValue('J' . $rowCount, 'Valor');
        $sheet->getStyle('I' . $rowCount . ':J' . $rowCount)->applyFromArray($styleArrayBlanco);
        $sheet->getStyle('I' . $rowCount . ':J' . $rowCount)->applyFromArray($backgroundStyleBlue);
        $sheet->getStyle('I' . $rowCount . ':J' . $rowCount)->applyFromArray($alignmentStyle);
        $rowCount++;

        // Escribir los viáticos de la actividad actual
        foreach ($viaticos as $viaticoAc) {
            $sheet->setCellValue('I' . $rowCount, $viaticoAc['nombre']);
            $sheet->setCellValue('J' . $rowCount, $viaticoAc['valor']);
            $sheet->getStyle('I' . $rowCount . ':J' . $rowCount)->applyFromArray($borderStyle);
            $rowCount++;
        }

        // Escribir la tabla de Cargos para esta actividad
        $sheet->setCellValue('L' . $rowCount, 'Cargos');
        $sheet->setCellValue('M' . $rowCount, 'Valor');
        $sheet->getStyle('L' . $rowCount . ':M' . $rowCount)->applyFromArray($styleArrayBlanco);
        $sheet->getStyle('L' . $rowCount . ':M' . $rowCount)->applyFromArray($backgroundStyleBlue);
        $sheet->getStyle('L' . $rowCount . ':M' . $rowCount)->applyFromArray($alignmentStyle);
        $rowCount++;

        // Escribir los cargos de la actividad actual
        foreach ($cargos as $cargo) {
            $sheet->setCellValue('L' . $rowCount, $cargo['nombre']);
            $sheet->setCellValue('M' . $rowCount, $cargo['valor']);
            $sheet->getStyle('L' . $rowCount . ':M' . $rowCount)->applyFromArray($borderStyle);
            $rowCount++;
        }
    }
    $rowCount += 2;
    // Escribir los encabezados
    // Establece los valores en las celdas
    $sheet->setCellValue('A' . $rowCount, 'Cant');
    $sheet->setCellValue('B' . $rowCount, 'Unid');
    $sheet->setCellValue('C' . $rowCount, 'Abrv Línea');
    $sheet->setCellValue('D' . $rowCount, 'Desc Breve');
    $sheet->setCellValue('E' . $rowCount, 'Desc Personal');
    $sheet->setCellValue('F' . $rowCount, 'Cant. Personas');
    $sheet->setCellValue('G' . $rowCount, 'Nota');
    $sheet->setCellValue('H' . $rowCount, 'Costo externo unitario');
    $sheet->setCellValue('I' . $rowCount, 'Costo Alimentación');
    $sheet->setCellValue('J' . $rowCount, 'Costo Transport');
    $sheet->setCellValue('K' . $rowCount, 'Costo Día Kam');
    $sheet->setCellValue('L' . $rowCount, 'Costo total días Kam');
    $sheet->setCellValue('M' . $rowCount, 'Valor día utilidad');
    $sheet->setCellValue('N' . $rowCount, 'Valor total días Utilidad');
    $sheet->setCellValue('O' . $rowCount, 'Rep');
    $sheet->setCellValue('P' . $rowCount, 'Check');
    $sheet->setCellValue('Q' . $rowCount, 'Fac Ad');

    // Ajusta la altura de la fila a 35 píxeles
    $sheet->getRowDimension($rowCount)->setRowHeight(35);
    $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)->applyFromArray($styleArrayBlanco);
    $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)->applyFromArray($backgroundStyleBlue);
    $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)->applyFromArray($alignmentStyle);
    $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
    $rowCount++;

    // Recorrer los contenedores
    $contenedorIds = array_keys($contenedoresArray);

    // Si $index es 0, procesamos el primer contenedor, si es 1, procesamos el segundo, etc.
    if (isset($contenedorIds[$index])) {
        $contenedorId = $contenedorIds[$index]; // Obtener el ID del contenedor correspondiente
        $turnos = $contenedoresArray[$contenedorId]; // Obtener los turnos de este contenedor

        // Aquí procesas los turnos como lo hacías antes
        $sheet->setCellValue('A' . $rowCount, "Contenedor ID: $contenedorId");
        $sheet->mergeCells('A' . $rowCount . ':Q' . $rowCount);
        $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)->applyFromArray($borderStyle);
        $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
        $rowCount++;

        // Recorrer los turnos dentro del contenedor
        foreach ($turnos as $turnoData) {
            $sheet->setCellValue('A' . $rowCount, $turnoData['turno'][0]['starTime']);
            $sheet->setCellValue('B' . $rowCount, $turnoData['turno'][0]['endTime']);
            $sheet->setCellValue('C' . $rowCount, $turnoData['turno'][0]['tipoTiempo']);
            $sheet->getStyle('A' . $rowCount . ':C' . $rowCount)->applyFromArray($borderStyle);
            $sheet->getStyle('A' . $rowCount . ':C' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
            $rowCount++;

            // Recorrer las actividades del turno
            foreach ($turnoData['actividades'] as $actividad) {
                $sheet->setCellValue('A' . $rowCount, $actividad['cantidad']);
                $sheet->setCellValue('B' . $rowCount, $actividad['unidad']);
                $sheet->setCellValue('C' . $rowCount, $actividad['abreviatura']);
                $sheet->setCellValue('D' . $rowCount, $actividad['referencia']);
                $sheet->setCellValue('E' . $rowCount, $actividad['material']);
                $sheet->setCellValue('F' . $rowCount, $actividad['descripcionMaterial']);
                $sheet->setCellValue('G' . $rowCount, $actividad['proveedor']);
                $sheet->setCellValue('H' . $rowCount, $actividad['nota']);
                $sheet->setCellValueExplicit('I' . $rowCount, $actividad['trm'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('J' . $rowCount, $actividad['precioLista'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('K' . $rowCount, $actividad['costoUnitarioKamati'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('L' . $rowCount, $actividad['costoTotalKamati'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('M' . $rowCount, $actividad['valorUtilidad'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValueExplicit('N' . $rowCount, $actividad['valorTotal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->setCellValue('O' . $rowCount, $actividad['tiempoEntrega']);
                $sheet->setCellValue('P' . $rowCount, $actividad['checkEstado']);
                $sheet->setCellValue('Q' . $rowCount, $actividad['factorAdicional']);
                $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)->applyFromArray($borderStyle);
                $sheet->getStyle('A' . $rowCount . ':Q' . $rowCount)
            ->getAlignment()
            ->setWrapText(true);
                $rowCount++;
            }
        }
    }
    $rowCount++;

    $sheet->setCellValue('A' . $rowCount, 'Total Kamati: ');
    $sheet->setCellValueExplicit('B' . $rowCount, $data['totalKamatiAcs'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->getStyle('A' . $rowCount . ':B' . $rowCount)->applyFromArray($borderStyle);

    $sheet->setCellValue('D' . $rowCount, 'Total Cliente: ');
    $sheet->setCellValueExplicit('E' . $rowCount, $data['totalClienteAcs'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $sheet->getStyle('D' . $rowCount . ':E' . $rowCount)->applyFromArray($borderStyle);

    $rowCount += 2;
}

$rowCount += 2;




// Crear una nueva hoja
$newSheet = $spreadsheet->createSheet();
$newSheet->setTitle('Abreviaturas');
// Define el ancho deseado para las columnas
$columnWidth = 25;

// Recorre desde la columna 'A' hasta la 'Z' (o más si es necesario)
foreach (range('A', 'S') as $columnID) {
    $newSheet->getColumnDimension($columnID)->setWidth($columnWidth);
}
// Cambiar a la nueva hoja para escribir en ella
$spreadsheet->setActiveSheetIndexByName('Abreviaturas');

// Inicializar contadores y estilos para las celdas
$rowCount = 1;


$newSheet->setCellValue('A' . $rowCount, 'ABREVIATURAS LÍNEA KAMATI');

// Aplica estilos a la celda
$newSheet->getStyle('A' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$newSheet->getStyle('A' . $rowCount)->getFont()->setSize(14);



$newSheet->setCellValue('A' . $rowCount, 'ABREVIATURAS');
$newSheet->setCellValue('B' . $rowCount, 'AUTO');
$newSheet->setCellValue('C' . $rowCount, 'COM ');
$newSheet->setCellValue('D' . $rowCount, 'CE');
$newSheet->setCellValue('E' . $rowCount, 'VAR');
$newSheet->setCellValue('F' . $rowCount, 'SOFT ');
$newSheet->setCellValue('G' . $rowCount, 'REP');
$newSheet->setCellValue('H' . $rowCount, 'LP');
$newSheet->setCellValue('I' . $rowCount, 'O ');
$newSheet->setCellValue('J' . $rowCount, 'PILZ ');
$newSheet->setCellValue('K' . $rowCount, 'PC  ');
$newSheet->setCellValue('L' . $rowCount, 'R  ');
$newSheet->setCellValue('M' . $rowCount, 'V ');
$newSheet->setCellValue('N' . $rowCount, 'MO ');
$newSheet->setCellValue('O' . $rowCount, 'SUP ');
$newSheet->setCellValue('P' . $rowCount, 'ING');
$newSheet->setCellValue('Q' . $rowCount, 'PM');
$newSheet->setCellValue('R' . $rowCount, 'SISO ');
$newSheet->setCellValue('A' . $rowCount, 'ABREVIATURAS');
$newSheet->setCellValue('B' . $rowCount, 'AUTO');
$newSheet->setCellValue('C' . $rowCount, 'COM ');
$newSheet->setCellValue('D' . $rowCount, 'CE');
$newSheet->setCellValue('E' . $rowCount, 'VAR');
$newSheet->setCellValue('F' . $rowCount, 'SOFT ');
$newSheet->setCellValue('G' . $rowCount, 'REP');
$newSheet->setCellValue('H' . $rowCount, 'LP');
$newSheet->setCellValue('I' . $rowCount, 'O ');
$newSheet->setCellValue('J' . $rowCount, 'PILZ ');
$newSheet->setCellValue('K' . $rowCount, 'PC  ');
$newSheet->setCellValue('L' . $rowCount, 'R  ');
$newSheet->setCellValue('M' . $rowCount, 'V ');
$newSheet->setCellValue('N' . $rowCount, 'MO ');
$newSheet->setCellValue('O' . $rowCount, 'SUP ');
$newSheet->setCellValue('P' . $rowCount, 'ING');
$newSheet->setCellValue('Q' . $rowCount, 'PM');
$newSheet->setCellValue('R' . $rowCount, 'SISO ');

// Aplicar el color de fondo a todas las celdas en esta fila
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);
$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'LÍNEA');
$newSheet->getStyle('A' . $rowCount . ':A' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->setCellValue('B' . $rowCount, 'AUTOMATIZACIÓN');
$newSheet->setCellValue('C' . $rowCount, 'COMUNICACIÓN ');
$newSheet->setCellValue('D' . $rowCount, 'MANIOBRA');
$newSheet->setCellValue('E' . $rowCount, 'VARIADORES');
$newSheet->setCellValue('F' . $rowCount, 'SOFTWARE');
$newSheet->setCellValue('G' . $rowCount, 'REPUESTOS');
$newSheet->setCellValue('H' . $rowCount, 'MANIOBRA');
$newSheet->setCellValue('I' . $rowCount, 'OTROS');
$newSheet->setCellValue('J' . $rowCount, 'PILZ');
$newSheet->setCellValue('K' . $rowCount, 'PHOENIX');
$newSheet->setCellValue('L' . $rowCount, 'RITTAL');
$newSheet->setCellValue('M' . $rowCount, 'VIIATICOS');
$newSheet->setCellValue('N' . $rowCount, 'MANO');
$newSheet->setCellValue('O' . $rowCount, 'SUPERVISOR');
$newSheet->setCellValue('P' . $rowCount, 'INGENIERÍA');
$newSheet->setCellValue('Q' . $rowCount, 'PROJECT');
$newSheet->setCellValue('R' . $rowCount, 'SISO');
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);

$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'VALORES');
$newSheet->getStyle('A' . $rowCount . ':A' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->setCellValueExplicit('B' . $rowCount, $data['automatizacion'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('C' . $rowCount, $data['comunicacion'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('D' . $rowCount, $data['maniobrace'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('E' . $rowCount, $data['variadores'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('F' . $rowCount, $data['software'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('G' . $rowCount, $data['repuestos'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('H' . $rowCount, $data['maniobralp'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('I' . $rowCount, $data['otros'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('J' . $rowCount, $data['pilz'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('K' . $rowCount, $data['phoenix'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('L' . $rowCount, $data['rittal'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('M' . $rowCount, $data['viaticosAv'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('N' . $rowCount, $data['mano'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('O' . $rowCount, $data['supervisor'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('P' . $rowCount, $data['ingenieria'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('Q' . $rowCount, $data['project'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('R' . $rowCount, $data['siso'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);
$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'TOTAL KAMATI');
$newSheet->setCellValueExplicit('B' . $rowCount, $data['totalAbKamati'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$rowCount += 2;

$newSheet->setCellValue('A' . $rowCount, 'ABREVIATURAS LÍNEA CLIENTE');
// Aplica estilos a la celda
$newSheet->getStyle('A' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$newSheet->getStyle('A' . $rowCount)->getFont()->setSize(14);

$rowCount += 2;
$newSheet->setCellValue('A' . $rowCount, 'ABREVIATURAS');
$newSheet->setCellValue('B' . $rowCount, 'AUTO');
$newSheet->setCellValue('C' . $rowCount, 'COM ');
$newSheet->setCellValue('D' . $rowCount, 'CE');
$newSheet->setCellValue('E' . $rowCount, 'VAR');
$newSheet->setCellValue('F' . $rowCount, 'SOFT ');
$newSheet->setCellValue('G' . $rowCount, 'REP');
$newSheet->setCellValue('H' . $rowCount, 'LP');
$newSheet->setCellValue('I' . $rowCount, 'O ');
$newSheet->setCellValue('J' . $rowCount, 'PILZ ');
$newSheet->setCellValue('K' . $rowCount, 'PC  ');
$newSheet->setCellValue('L' . $rowCount, 'R  ');
$newSheet->setCellValue('M' . $rowCount, 'V ');
$newSheet->setCellValue('N' . $rowCount, 'MO ');
$newSheet->setCellValue('O' . $rowCount, 'SUP ');
$newSheet->setCellValue('P' . $rowCount, 'ING');
$newSheet->setCellValue('Q' . $rowCount, 'PM');
$newSheet->setCellValue('R' . $rowCount, 'SISO ');
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);
$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'LÍNEA');
$newSheet->getStyle('A' . $rowCount . ':A' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->setCellValue('B' . $rowCount, 'AUTOMATIZACIÓN');
$newSheet->setCellValue('C' . $rowCount, 'COMUNICACIÓN ');
$newSheet->setCellValue('D' . $rowCount, 'MANIOBRA');
$newSheet->setCellValue('E' . $rowCount, 'VARIADORES');
$newSheet->setCellValue('F' . $rowCount, 'SOFTWARE');
$newSheet->setCellValue('G' . $rowCount, 'REPUESTOS');
$newSheet->setCellValue('H' . $rowCount, 'MANIOBRA');
$newSheet->setCellValue('I' . $rowCount, 'OTROS');
$newSheet->setCellValue('J' . $rowCount, 'PILZ');
$newSheet->setCellValue('K' . $rowCount, 'PHOENIX');
$newSheet->setCellValue('L' . $rowCount, 'RITTAL');
$newSheet->setCellValue('M' . $rowCount, 'VIIATICOS');
$newSheet->setCellValue('N' . $rowCount, 'MANO');
$newSheet->setCellValue('O' . $rowCount, 'SUPERVISOR');
$newSheet->setCellValue('P' . $rowCount, 'INGENIERÍA');
$newSheet->setCellValue('Q' . $rowCount, 'PROJECT');
$newSheet->setCellValue('R' . $rowCount, 'SISO');
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);

$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'VALORES');
$newSheet->getStyle('A' . $rowCount . ':A' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->setCellValueExplicit('B' . $rowCount, $data['automatizacionCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('C' . $rowCount, $data['comunicacionCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('D' . $rowCount, $data['maniobraceCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('E' . $rowCount, $data['variadoresCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('F' . $rowCount, $data['softwareCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('G' . $rowCount, $data['repuestosCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('H' . $rowCount, $data['maniobralpCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('I' . $rowCount, $data['otrosCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('J' . $rowCount, $data['pilzCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('K' . $rowCount, $data['phoenixCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('L' . $rowCount, $data['rittalCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('M' . $rowCount, $data['viaticosAvCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('N' . $rowCount, $data['manoCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('O' . $rowCount, $data['supervisorCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('P' . $rowCount, $data['ingenieriaCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('Q' . $rowCount, $data['projectCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->setCellValueExplicit('R' . $rowCount, $data['sisoCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);
$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'PORCENTAJE');
$newSheet->getStyle('A' . $rowCount . ':A' . $rowCount)->applyFromArray($backgroundStyle);
$newSheet->setCellValue('B' . $rowCount, $data['automatizacionClientePorcentaje']);
$newSheet->setCellValue('C' . $rowCount, $data['comunicacionClientePorcentaje']);
$newSheet->setCellValue('D' . $rowCount, $data['maniobraceClientePorcentaje']);
$newSheet->setCellValue('E' . $rowCount, $data['variadoresClientePorcentaje']);
$newSheet->setCellValue('F' . $rowCount, $data['softwareClientePorcentaje']);
$newSheet->setCellValue('G' . $rowCount, $data['repuestosClientePorcentaje']);
$newSheet->setCellValue('H' . $rowCount, $data['maniobralpClientePorcentaje']);
$newSheet->setCellValue('I' . $rowCount, $data['otrosClientePorcentaje']);
$newSheet->setCellValue('J' . $rowCount, $data['pilzClientePorcentaje']);
$newSheet->setCellValue('K' . $rowCount, $data['phoenixClientePorcentaje']);
$newSheet->setCellValue('L' . $rowCount, $data['rittalClientePorcentaje']);
$newSheet->setCellValue('M' . $rowCount, $data['viaticosAvClientePorcentaje']);
$newSheet->setCellValue('N' . $rowCount, $data['manoClientePorcentaje']);
$newSheet->setCellValue('O' . $rowCount, $data['supervisorClientePorcentaje']);
$newSheet->setCellValue('P' . $rowCount, $data['ingenieriaClientePorcentaje']);
$newSheet->setCellValue('Q' . $rowCount, $data['projectClientePorcentaje']);
$newSheet->setCellValue('R' . $rowCount, $data['sisoClientePorcentaje']);
$newSheet->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($borderStyle);
$rowCount++;
$newSheet->setCellValue('A' . $rowCount, 'TOTAL CLIENTE');
$newSheet->setCellValueExplicit('B' . $rowCount, $data['totalAbCliente'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);


// Crear una nueva hoja
$newSheetTotal = $spreadsheet->createSheet();
$newSheetTotal->setTitle('Totales');

// Define el ancho deseado para las columnas
$columnWidth = 35;

// Recorre desde la columna 'A' hasta la 'Z' (o más si es necesario)
foreach (range('A', 'Z') as $columnID) {
    $newSheetTotal->getColumnDimension($columnID)->setWidth($columnWidth);
}
// Cambiar a la nueva hoja para escribir en ella
$spreadsheet->setActiveSheetIndexByName('Totales');

// Inicializar contadores y estilos para las celdas
$rowCount = 1;

$newSheetTotal->setCellValue('B' . $rowCount, 'TOTALES DE TABLAS CLIENTE');
// Aplica estilos a la celda
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setSize(14);

$rowCount++;
$newSheetTotal->setCellValue('B' . $rowCount, 'Materiales');
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setSize(16);
$rowCount++;
$newSheetTotal->setCellValue('B' . $rowCount, 'Nombre Tabla');
$newSheetTotal->setCellValue('C' . $rowCount, 'Total Cliente');

$rowCount++;
foreach ($nombreTablaMateriales as $index => $nombreTabla) { 
    $newSheetTotal->setCellValue('B' . $rowCount, $nombreTabla);
    $newSheetTotal->setCellValueExplicit('C' . $rowCount, $data['totalUnique_cliente'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $newSheetTotal->getStyle('B' . $rowCount . ':C' . $rowCount)->applyFromArray($borderStyle);
    $rowCount++;
}
$rowCount++; // Incrementa el contador de filas
$newSheetTotal->setCellValue('B' . $rowCount, 'Total Cliente Materiales'); // Escribe el texto descriptivo

// Primero, elimina los puntos y cambia la coma por punto
$formattedTotal = str_replace('.', '', $data['formattedTotal']); // Elimina los puntos (miles)
$formattedTotal = str_replace(',', '.', $formattedTotal); // Cambia la coma por punto decimal

// Ahora, escribe el número como un valor flotante
$newSheetTotal->setCellValue('C' . $rowCount, (float) $formattedTotal); // Escribe el valor como número

// Aplica el formato de contabilidad con moneda
$newSheetTotal->getStyle('C' . $rowCount)->getNumberFormat()->setFormatCode('[$$-2] #,##0.00'); // Usar símbolo de moneda, puedes cambiar el "€" por "$" u otro símbolo

// También puedes alinear el valor a la derecha, que es común en los formatos contables
$newSheetTotal->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$rowCount++; // Incrementa el contador de filas

$newSheetTotal->setCellValue('B' . $rowCount, 'Maquinaria');
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setSize(16);
$rowCount++;
$newSheetTotal->setCellValue('B' . $rowCount, 'Nombre Tabla:');
$newSheetTotal->setCellValue('C' . $rowCount, 'Total Cliente: ');
$rowCount++;
foreach ($nombreTablasMaquinaria as $index => $nombreTablaMaquinaria) {
    $newSheetTotal->setCellValue('B' . $rowCount, $nombreTablaMaquinaria);
    $newSheetTotal->setCellValueExplicit('C' . $rowCount, $data['totalUnique_cliente_maquinaria'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $newSheetTotal->getStyle('B' . $rowCount . ':C' . $rowCount)->applyFromArray($borderStyle);
    $rowCount++;
}
$rowCount++; // Incrementa el contador de filas
$newSheetTotal->setCellValue('B' . $rowCount, 'Total Cliente Maquinaria'); // Escribe el texto descriptivo

// Primero, elimina los puntos y cambia la coma por punto
$formattedTotal = str_replace('.', '', $data['formattedTotal_maquinaria']); // Elimina los puntos (miles)
$formattedTotal = str_replace(',', '.', $formattedTotal); // Cambia la coma por punto decimal

// Ahora, escribe el número como un valor flotante
$newSheetTotal->setCellValue('C' . $rowCount, (float) $formattedTotal); // Escribe el valor como número

// Aplica el formato de contabilidad con moneda
$newSheetTotal->getStyle('C' . $rowCount)->getNumberFormat()->setFormatCode('[$$-2] #,##0.00'); // Usar símbolo de moneda, puedes cambiar el "€" por "$" u otro símbolo

// También puedes alinear el valor a la derecha, que es común en los formatos contables
$newSheetTotal->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$rowCount++;

$newSheetTotal->setCellValue('B' . $rowCount, 'Atividades');
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setBold(true); // Aplica negrilla
$newSheetTotal->getStyle('B' . $rowCount)->getFont()->setSize(16);
$rowCount++;
$newSheetTotal->setCellValue('B' . $rowCount, 'Nombre Tabla');
$newSheetTotal->setCellValue('C' . $rowCount, 'Total Cliente');
$rowCount++;
foreach ($tablaAc as $index => $nombreTablaActividad) {
    
    $newSheetTotal->setCellValue('B' . $rowCount, $nombreTablaActividad);
    $newSheetTotal->setCellValueExplicit('C' . $rowCount, $data['totalClienteAcs'][$index], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $newSheetTotal->getStyle('B' . $rowCount . ':C' . $rowCount)->applyFromArray($borderStyle);
    $rowCount++;
}
$rowCount++; // Incrementa el contador de filas
$newSheetTotal->setCellValue('B' . $rowCount, 'Total Cliente Actividades'); // Escribe el texto descriptivo

// Primero, elimina los puntos y cambia la coma por punto
$formattedTotal = str_replace('.', '', $data['formattedTotalAcs']); // Elimina los puntos (miles)
$formattedTotal = str_replace(',', '.', $formattedTotal); // Cambia la coma por punto decimal

// Ahora, escribe el número como un valor flotante
$newSheetTotal->setCellValue('C' . $rowCount, (float) $formattedTotal); // Escribe el valor como número

// Aplica el formato de contabilidad con moneda
$newSheetTotal->getStyle('C' . $rowCount)->getNumberFormat()->setFormatCode('[$$-2] #,##0.00'); // Usar símbolo de moneda, puedes cambiar el "€" por "$" u otro símbolo

// También puedes alinear el valor a la derecha, que es común en los formatos contables
$newSheetTotal->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$rowCount++;
$rowCount++; // Incrementa el contador de filas
$newSheetTotal->setCellValue('B' . $rowCount, 'Total Tablas Cliente'); // Escribe el texto descriptivo

// Primero, elimina los puntos y cambia la coma por punto
$formattedTotal = str_replace('.', '', $data['formattedFinalTotal']); // Elimina los puntos (miles)
$formattedTotal = str_replace(',', '.', $formattedTotal); // Cambia la coma por punto decimal

// Ahora, escribe el número como un valor flotante
$newSheetTotal->setCellValue('C' . $rowCount, (float) $formattedTotal); // Escribe el valor como número

// Aplica el formato de contabilidad con moneda
$newSheetTotal->getStyle('C' . $rowCount)->getNumberFormat()->setFormatCode('[$$-2] #,##0.00'); // Usar símbolo de moneda, puedes cambiar el "€" por "$" u otro símbolo

// También puedes alinear el valor a la derecha, que es común en los formatos contables
$newSheetTotal->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
$rowCount++;


// Definir el encabezado para la descarga del archivo
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Cotizacion_' . $data['campo4'] . '.xlsx"');
header('Cache-Control: max-age=0');
header('Pragma: public');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
