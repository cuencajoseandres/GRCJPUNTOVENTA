<?php
// Limpia cualquier salida previa
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
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Código');
$sheet->setCellValue('C1', 'Nombre');
$sheet->setCellValue('D1', 'Descripción');
$sheet->setCellValue('E1', 'Categoría');
$sheet->setCellValue('F1', 'Precio Costo');
$sheet->setCellValue('G1', 'Precio Público');
$sheet->setCellValue('H1', 'Precio Gremio');
$sheet->setCellValue('I1', 'Precio Mayorista');
$sheet->setCellValue('J1', 'Stock');
$sheet->setCellValue('K1', 'Fecha');

// Obtener datos
$sql = $pdo->query("
    SELECT 
        id_producto,
        cod_product,
        nombre_product,
        descri_product,
        rela_categoria,
        precio_costo_produc,
        precio_publico_product,
        precio_gremio_product,
        precio_mayorista_product,
        cant_product,
        fecha_product
    FROM producto
    ORDER BY id_producto DESC
");

$fila = 2;
foreach ($sql as $prod) {
    $sheet->setCellValue("A$fila", $prod['id_producto']);
    $sheet->setCellValue("B$fila", $prod['cod_product']);
    $sheet->setCellValue("C$fila", $prod['nombre_product']);
    $sheet->setCellValue("D$fila", $prod['descri_product']);
    $sheet->setCellValue("E$fila", $prod['rela_categoria']);
    $sheet->setCellValue("F$fila", $prod['precio_costo_produc']);
    $sheet->setCellValue("G$fila", $prod['precio_publico_product']);
    $sheet->setCellValue("H$fila", $prod['precio_gremio_product']);
    $sheet->setCellValue("I$fila", $prod['precio_mayorista_product']);
    $sheet->setCellValue("J$fila", $prod['cant_product']);
    $sheet->setCellValue("K$fila", $prod['fecha_product']);
    $fila++;
}

// Descargar archivo
$writer = new Xlsx($spreadsheet);
$nombreArchivo = 'productos_' . date('Y-m-d_His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

// Limpia cualquier espacio/echo restante
ob_clean();

$writer->save('php://output');
ob_end_flush();
exit;
