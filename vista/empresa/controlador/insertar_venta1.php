<?php
session_start();
require('conexion.php');
header('Content-Type: application/json; charset=utf-8');

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'No hay sesión activa.']);
    exit;
}

// Verificar que lleguen datos
if (!isset($_POST['data'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Faltan datos (no se recibió JSON)']);
    exit;
}

$data = json_decode($_POST['data'], true);

if (
    empty($data['num_venta']) ||
    empty($data['fecha_fact']) ||
    empty($data['empleado']) ||
    empty($data['cliente']) ||
    empty($data['metodo_pago']) ||
    empty($data['detalles'])
) {
    echo json_encode(['error' => true, 'mensaje' => 'Faltan datos obligatorios']);
    exit;
}


try {
    $pdo->beginTransaction();

    $idFactura = null;
    $totalVenta = 0;

    // Calcular total y validar stock solo para productos
    foreach ($data['detalles'] as $d) {
        if ($d['tipo'] === 'producto') {
            $stmtStock = $pdo->prepare("SELECT cant_product FROM producto WHERE id_producto = ?");
            $stmtStock->execute([$d['id']]);
            $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$producto) throw new Exception("Producto ID {$d['id']} no encontrado");
            if ($d['cantidad'] > $producto['cant_product']) throw new Exception("Stock insuficiente para {$d['nombre']}. Disponible: {$producto['cant_product']}");
        }

        $totalVenta += $d['subtotal'];
    }

// ID estado por defecto
$idEstadoFactura = 1; // NO_FACTURADA

$stmt = $pdo->prepare("
    INSERT INTO factura 
    (num_venta, fecha_fact, monto_total, rela_empleado, rela_cliente, rela_metodo_pago, rela_estado_factura)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $data['num_venta'],
    $data['fecha_fact'],
    $totalVenta,
    $data['empleado'],
    $data['cliente'],
    $data['metodo_pago'],
    $idEstadoFactura
]);

$idFactura = $pdo->lastInsertId();

    // Preparar inserts
    $stmtDetProd = $pdo->prepare("INSERT INTO detalle_factura 
        (cant_venta_product, precio_unit_product, subtotal_product, rela_producto, rela_factura)
        VALUES (?, ?, ?, ?, ?)");
    $stmtUpdateStock = $pdo->prepare("UPDATE producto SET cant_product = cant_product - ? WHERE id_producto = ?");

    $stmtDetServ = $pdo->prepare("INSERT INTO detalle_fact_serv
        (cant_vent_serv, precio_unit_serv, subtotal_serv, rela_servicio, rela_factura)
        VALUES (?, ?, ?, ?, ?)");

    // Insertar detalles
    foreach ($data['detalles'] as $d) {
        if ($d['tipo'] === 'producto') {
            // Insertar detalle producto
            $stmtDetProd->execute([
                $d['cantidad'],
                $d['precio'],
                $d['subtotal'],
                $d['id'],
                $idFactura
            ]);
            // Actualizar stock
            $stmtUpdateStock->execute([$d['cantidad'], $d['id']]);
        } elseif ($d['tipo'] === 'servicio') {
            // Insertar detalle servicio
            $stmtDetServ->execute([
                $d['cantidad'],
                $d['precio'],
                $d['subtotal'],
                $d['id'],
                $idFactura
            ]);
        }
    }

    $pdo->commit();
    echo json_encode(['error' => false, 'mensaje' => 'Venta registrada correctamente.']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
}
