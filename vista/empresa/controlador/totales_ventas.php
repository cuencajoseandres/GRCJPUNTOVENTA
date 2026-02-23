<?php
require_once('../conexion.php');
header('Content-Type: application/json');

$filtro = $_GET['filtro'] ?? 'hoy';

switch ($filtro) {
    case 'hoy':
        $whereFecha = "DATE(f.fecha_fact) = CURDATE()";
        break;
    case 'semana':
        $whereFecha = "YEARWEEK(f.fecha_fact, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'mes':
        $whereFecha = "MONTH(f.fecha_fact) = MONTH(CURDATE()) 
                       AND YEAR(f.fecha_fact) = YEAR(CURDATE())";
        break;
    case 'anio':
        $whereFecha = "YEAR(f.fecha_fact) = YEAR(CURDATE())";
        break;
    default:
        $whereFecha = "1=1";
}

try {

    // PRODUCTOS
    $stmtProd = $pdo->prepare("
        SELECT
            COALESCE(SUM(df.cant_venta_product),0) AS cant_ventas_productos,
            COALESCE(SUM(df.subtotal_product),0)   AS total_ventas_productos
        FROM detalle_factura df
        INNER JOIN factura f ON df.rela_factura = f.id_factura
        WHERE $whereFecha
    ");
    $stmtProd->execute();
    $prod = $stmtProd->fetch(PDO::FETCH_ASSOC);

    // SERVICIOS
    $stmtServ = $pdo->prepare("
        SELECT
            COALESCE(SUM(fs.cant_vent_serv),0) AS cant_servicios,
            COALESCE(SUM(fs.subtotal_serv),0)  AS total_servicios
        FROM detalle_fact_serv fs
        INNER JOIN factura f ON fs.rela_factura = f.id_factura
        WHERE $whereFecha
    ");
    $stmtServ->execute();
    $serv = $stmtServ->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        'error' => false,
        'cantVentasProductos'  => (int)$prod['cant_ventas_productos'],
        'totalVentasProductos' => (float)$prod['total_ventas_productos'],
        'cantServicios'        => (int)$serv['cant_servicios'],
        'totalServicios'       => (float)$serv['total_servicios']
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}
