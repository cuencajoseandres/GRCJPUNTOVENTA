<?php
require_once('../conexion.php');
require '../../../vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$response = ['error' => true, 'mensaje' => 'Error desconocido'];

try {
    if (!isset($_FILES['archivo_excel'])) {
        throw new Exception("No se subió ningún archivo.");
    }

    $archivoTmp = $_FILES['archivo_excel']['tmp_name'];
    $spreadsheet = IOFactory::load($archivoTmp);
    $hoja = $spreadsheet->getActiveSheet();
    $filas = $hoja->toArray(null, true, true, true);

    $contador = 0;

    foreach ($filas as $i => $fila) {
        if ($i == 1) continue; // Saltar encabezado

        $nombre = trim($fila['A']);
        $descripcion = trim($fila['B']);
        $monto = trim($fila['C']);
        $fecha = trim($fila['D']);
        $metodo = trim($fila['E']);
        $tipo = trim($fila['F']);
        $observaciones = trim($fila['G']);

        if (empty($nombre) || empty($monto) || empty($fecha) || empty($metodo) || empty($tipo)) {
            continue; // Saltar filas incompletas
        }

        $stmt = $pdo->prepare("INSERT INTO gasto (nombre_gasto, descripcion, monto, fecha, rela_metodo_pago, rela_tipo_gasto, observaciones)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $monto, $fecha, $metodo, $tipo, $observaciones]);
        $contador++;
    }

    $response = [
        'error' => false,
        'mensaje' => "Se importaron correctamente $contador gastos."
    ];

} catch (Exception $e) {
    $response['mensaje'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>