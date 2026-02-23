<?php
require('../conexion.php');
header('Content-Type: application/json');

if (!isset($_POST['id_factura'])) {
    echo json_encode(['error'=>true,'mensaje'=>'ID no recibido']);
    exit;
}

$idFactura = (int)$_POST['id_factura'];

try {

    // 1️⃣ Factura
    $stmt = $pdo->prepare("
        SELECT *
        FROM factura
        WHERE id_factura = ?
        AND rela_estado_factura = 1
    ");
    $stmt->execute([$idFactura]);
    $factura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$factura) {
        throw new Exception('La venta no puede editarse');
    }

    // 2️⃣ Productos
    $stmtProd = $pdo->prepare("
        SELECT 
            df.rela_producto AS id_item,
            p.nombre_product AS item,
            df.cant_venta_product AS cantidad,
            df.precio_unit_product AS precio,
            'producto' AS tipo
        FROM detalle_factura df
        INNER JOIN producto p ON df.rela_producto = p.id_producto
        WHERE df.rela_factura = ?
    ");
    $stmtProd->execute([$idFactura]);
    $productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

    // 3️⃣ Servicios
    $stmtServ = $pdo->prepare("
        SELECT 
            ds.rela_servicio AS id_item,
            s.nombre_serv AS item,
            ds.cant_vent_serv AS cantidad,
            ds.precio_unit_serv AS precio,
            'servicio' AS tipo
        FROM detalle_fact_serv ds
        INNER JOIN servicio s ON ds.rela_servicio = s.id_servicio
        WHERE ds.rela_factura = ?
    ");
    $stmtServ->execute([$idFactura]);
    $servicios = $stmtServ->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'error' => false,
        'factura' => $factura,
        'detalles' => array_merge($productos, $servicios)
    ]);

} catch (Exception $e) {
    echo json_encode(['error'=>true,'mensaje'=>$e->getMessage()]);
}
