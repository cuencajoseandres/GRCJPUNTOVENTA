<?php
require_once('../conexion.php');
require '../../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Crear hoja de cálculo
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Gastos');

// Encabezados
$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Nombre del Gasto');
$sheet->setCellValue('C1', 'Tipo de Gasto');
$sheet->setCellValue('D1', 'Monto');
$sheet->setCellValue('E1', 'Fecha');
$sheet->setCellValue('F1', 'Método de Pago');
$sheet->setCellValue('G1', 'Observaciones');

// Consulta SQL
$sql = "SELECT g.id_gasto, g.nombre_gasto, tg.descri_tipo_gasto AS tipo_gasto, 
               g.monto, g.fecha, mp.descri_metodo_pago AS metodo_pago, g.observaciones
        FROM gasto g
        INNER JOIN tipo_gasto tg ON g.rela_tipo_gasto = tg.id_tipo_gasto
        INNER JOIN metodo_pago mp ON g.rela_metodo_pago = mp.id_metodo_pago
        ORDER BY g.fecha DESC";

$stmt = $pdo->query($sql);
$gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Llenar filas
$fila = 2;
foreach ($gastos as $g) {
    $sheet->setCellValue('A' . $fila, $g['id_gasto']);
    $sheet->setCellValue('B' . $fila, $g['nombre_gasto']);
    $sheet->setCellValue('C' . $fila, $g['tipo_gasto']);
    $sheet->setCellValue('D' . $fila, $g['monto']);
    $sheet->setCellValue('E' . $fila, $g['fecha']);
    $sheet->setCellValue('F' . $fila, $g['metodo_pago']);
    $sheet->setCellValue('G' . $fila, $g['observaciones']);
    $fila++;
}

// Estilo de encabezados
$sheet->getStyle('A1:G1')->getFont()->setBold(true);
$sheet->getStyle('A1:G1')->getAlignment()->setHorizontal('center');
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('G')->setWidth(40);

// Descargar archivo
$nombreArchivo = 'Gastos_' . date('Y-m-d_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"$nombreArchivo\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
