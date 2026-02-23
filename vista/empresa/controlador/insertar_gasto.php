<?php
require('../conexion.php');
header('Content-Type: application/json; charset=utf-8');

try {
    // Recibir datos
    $nombre = isset($_POST['nombre_gasto']) ? trim($_POST['nombre_gasto']) : '';
    $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : '';
    $tipo_gasto = isset($_POST['tipo_gasto']) ? intval($_POST['tipo_gasto']) : 0;
    $monto = isset($_POST['monto']) ? $_POST['monto'] : '';
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : '';
    $metodo_pago = isset($_POST['metodo_pago']) ? intval($_POST['metodo_pago']) : 0;
    $observaciones = isset($_POST['observaciones']) ? trim($_POST['observaciones']) : null;

    // Validaciones básicas
    $errores = [];
    if ($nombre === '') $errores[] = "Nombre del gasto vacío";
    // Si querés que descripcion sea opcional, comentá la siguiente línea:
    if ($descripcion === '') $errores[] = "Descripción vacía";
    if ($tipo_gasto <= 0) $errores[] = "Seleccione el tipo de gasto";
    if ($monto === '' || !is_numeric($monto)) $errores[] = "Monto inválido";
    if ($fecha === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) $errores[] = "Fecha inválida";
    if ($metodo_pago <= 0) $errores[] = "Seleccione el método de pago";

    if (!empty($errores)) {
        throw new Exception(implode('. ', $errores));
    }

    // Insertar en DB
    $stmt = $pdo->prepare("INSERT INTO gasto 
        (nombre_gasto, descripcion, monto, fecha, rela_metodo_pago, rela_tipo_gasto, observaciones)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $nombre,
        $descripcion,
        $monto,
        $fecha,
        $metodo_pago,
        $tipo_gasto,
        $observaciones
    ]);

    echo json_encode(['error' => false, 'mensaje' => 'Gasto registrado correctamente.']);

} catch (Exception $e) {
    echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
}
