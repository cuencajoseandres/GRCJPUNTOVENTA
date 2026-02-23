<?php
// 🔥 Limpia cualquier salida previa (espacios, BOM, warnings)
if (ob_get_length()) ob_end_clean();
ob_start();

require('conexion.php');
require_once '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Productos');

// Encabezados
$sheet->setCellValue('A1', 'Código');
$sheet->setCellValue('B1', 'Nombre');
$sheet->setCellValue('C1', 'Precio Público');

// Obtener datos
$sql = $pdo->query("
    SELECT 
        cod_product,
        nombre_product,
        precio_publico_product
    FROM producto
");

$fila = 2;
foreach ($sql as $prod) {
    $sheet->setCellValue("A$fila", $prod['cod_product']);
    $sheet->setCellValue("B$fila", $prod['nombre_product']);
    $sheet->setCellValue("C$fila", $prod['precio_publico_product']);
    $fila++;
}

// Descargar archivo
$writer = new Xlsx($spreadsheet);
$nombreArchivo = 'productos_publico_' . date('Y-m-d_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

// 🔥 Limpia cualquier espacio/echo previo
ob_clean();

$writer->save('php://output');
ob_end_flush();
exit;
?>