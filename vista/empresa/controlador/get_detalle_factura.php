<?php
require('conexion.php');
header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['error'=>true,'mensaje'=>'No se recibió ID de factura']);
    exit;
}

$idFactura = intval($_GET['id']);

try {
    $data = [];

    // 1) Detalle de productos
    $stmtProd = $pdo->prepare("
        SELECT p.nombre_product AS nombre, df.cant_venta_product AS cantidad, 
               df.precio_unit_product AS precio_unitario, df.subtotal_product AS subtotal, 'Producto' AS tipo
        FROM detalle_factura df
        INNER JOIN producto p ON df.rela_producto = p.id_producto
        WHERE df.rela_factura = ?
    ");
    $stmtProd->execute([$idFactura]);
    $productos = $stmtProd->fetchAll(PDO::FETCH_ASSOC);

    // 2) Detalle de servicios
    $stmtServ = $pdo->prepare("
        SELECT s.nombre_serv AS nombre, ds.cant_vent_serv AS cantidad,
               ds.precio_unit_serv AS precio_unitario, ds.subtotal_serv AS subtotal, 'Servicio' AS tipo
        FROM detalle_fact_serv ds
        INNER JOIN servicio s ON ds.rela_servicio = s.id_servicio
        WHERE ds.rela_factura = ?
    ");
    $stmtServ->execute([$idFactura]);
    $servicios = $stmtServ->fetchAll(PDO::FETCH_ASSOC);

    // Combinar productos y servicios
    $data = array_merge($productos, $servicios);

    echo json_encode(['error'=>false,'data'=>$data]);

} catch (Exception $e){
    echo json_encode(['error'=>true,'mensaje'=>'Error: '.$e->getMessage()]);
}
?>