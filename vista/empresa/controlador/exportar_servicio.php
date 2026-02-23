<?php
require('conexion.php');
require_once '../../../vendor/autoload.php'; // si usás PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Servicios');

// Encabezados
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Código');
$sheet->setCellValue('C1', 'Nombre');
$sheet->setCellValue('D1', 'Descripción');
$sheet->setCellValue('E1', 'Precio');

// Obtener datos
$sql = $pdo->query("SELECT id_servicio, codigo_serv, nombre_serv, descri_serv, precio_serv FROM servicio ORDER BY id_servicio DESC");
$fila = 2;
foreach ($sql as $serv) {
    $sheet->setCellValue("A$fila", $serv['id_servicio']);
    $sheet->setCellValue("B$fila", $serv['codigo_serv']);
    $sheet->setCellValue("C$fila", $serv['nombre_serv']);
    $sheet->setCellValue("D$fila", $serv['descri_serv']);
    $sheet->setCellValue("E$fila", $serv['precio_serv']);
    $fila++;
}

// Descargar archivo
$writer = new Xlsx($spreadsheet);
$nombreArchivo = 'servicios_' . date('Y-m-d_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
$writer->save('php://output');
exit;
?>
