<?php
require('conexion.php');
require_once '../../../vendor/autoload.php'; // PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Productos');

// Encabezados
$sheet->setCellValue('A1', 'Código');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Precio_Gremio_May');

// Obtener datos
$sql = $pdo->query("
    SELECT 
        cod_product,
        nombre_product,
        precio_gremio_product
    FROM producto
");

$fila = 2;
foreach ($sql as $prod) {
    $sheet->setCellValue("A$fila", $prod['cod_product']);
    $sheet->setCellValue("B$fila", $prod['nombre_product']);
    $sheet->setCellValue("C$fila", $prod['precio_gremio_product']);
    $fila++;
}

// Descargar archivo
$writer = new Xlsx($spreadsheet);
$nombreArchivo = 'productos_gremio_may_' . date('Y-m-d_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
$writer->save('php://output');
exit;
?>
