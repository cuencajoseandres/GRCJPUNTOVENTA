<?php
require('conexion.php');
header('Content-Type: application/json');

try {

    // ==================== FECHAS ====================
    if (isset($_POST['filtro'])) {
        $tipo = $_POST['filtro'];
        switch ($tipo) {
            case 'hoy':
                $inicio = $fin = date('Y-m-d');
                break;
            case 'semana':
                $inicio = date('Y-m-d', strtotime('monday this week'));
                $fin = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'mes':
                $inicio = date('Y-m-01');
                $fin = date('Y-m-t');
                break;
            default:
                $inicio = $_POST['fecha_inicio'] ?? date('Y-m-01');
                $fin = $_POST['fecha_fin'] ?? date('Y-m-d');
        }
    } else {
        $inicio = $_POST['fecha_inicio'] ?? date('Y-m-01');
        $fin = $_POST['fecha_fin'] ?? date('Y-m-d');
    }

    // PARAMETROS UNICOS (FIX ERROR HY093)
    $params = [
        ':inicio' => $inicio,
        ':fin'    => $fin
    ];

    // ==================== TOTALES PRODUCTOS ====================
    $stmtProd = $pdo->prepare("
        SELECT 
            COALESCE(SUM(df.cant_venta_product),0) AS cantidad_productos_vendidos,
            COALESCE(SUM(df.precio_unit_product * df.cant_venta_product),0) AS total_productos_vendidos
        FROM detalle_factura df
        INNER JOIN factura f ON df.rela_factura = f.id_factura
        WHERE f.fecha_fact BETWEEN :inicio AND :fin
    ");
    $stmtProd->execute($params);
    $totalesProd = $stmtProd->fetch(PDO::FETCH_ASSOC);

    // ==================== TOTALES SERVICIOS ====================
    $stmtServ = $pdo->prepare("
        SELECT 
            COALESCE(SUM(fs.cant_vent_serv),0) AS cantidad_servicios_realizados,
            COALESCE(SUM(fs.precio_unit_serv * fs.cant_vent_serv),0) AS total_servicios_realizados
        FROM detalle_fact_serv fs
        INNER JOIN factura f ON fs.rela_factura = f.id_factura
        WHERE f.fecha_fact BETWEEN :inicio AND :fin
    ");
    $stmtServ->execute($params);
    $totalesServ = $stmtServ->fetch(PDO::FETCH_ASSOC);

    // ==================== GASTOS ====================
    $stmtGastos = $pdo->prepare("
        SELECT COALESCE(SUM(monto),0)
        FROM gasto
        WHERE fecha BETWEEN :inicio AND :fin
    ");
    $stmtGastos->execute($params);
    $gastosMensual = $stmtGastos->fetchColumn();

    // ==================== TOTALES GENERALES ====================
    $totales = [
        'cantidad_productos_vendidos' => $totalesProd['cantidad_productos_vendidos'],
        'total_productos_vendidos'    => $totalesProd['total_productos_vendidos'],
        'cantidad_servicios_realizados' => $totalesServ['cantidad_servicios_realizados'],
        'total_servicios_realizados'  => $totalesServ['total_servicios_realizados'],
        'total_general' => $totalesProd['total_productos_vendidos'] + $totalesServ['total_servicios_realizados']
    ];

    // ==================== GANANCIA PRODUCTOS ====================
    $stmtGanProd = $pdo->prepare("
        SELECT COALESCE(SUM((df.precio_unit_product - p.precio_costo_produc) * df.cant_venta_product),0)
        FROM detalle_factura df
        INNER JOIN producto p ON df.rela_producto = p.id_producto
        INNER JOIN factura f ON df.rela_factura = f.id_factura
        WHERE f.fecha_fact BETWEEN :inicio AND :fin
    ");
    $stmtGanProd->execute($params);
    $gananciaProductos = $stmtGanProd->fetchColumn();

    // ==================== GANANCIA SERVICIOS ====================
    $gananciaServicios = $totalesServ['total_servicios_realizados'];

    // ==================== GANANCIA TOTAL ====================
    $gananciaTotal = $gananciaProductos + $gananciaServicios;

    // ==================== DETALLE PRODUCTOS ====================
    $stmtDetalleProd = $pdo->prepare("
        SELECT p.cod_product, p.nombre_product, 
               SUM(df.cant_venta_product) AS cantidad,
               SUM(df.precio_unit_product * df.cant_venta_product) AS total
        FROM detalle_factura df
        INNER JOIN producto p ON df.rela_producto = p.id_producto
        INNER JOIN factura f ON df.rela_factura = f.id_factura
        WHERE f.fecha_fact BETWEEN :inicio AND :fin
        GROUP BY p.id_producto
        ORDER BY total DESC
    ");
    $stmtDetalleProd->execute($params);
    $productos = $stmtDetalleProd->fetchAll(PDO::FETCH_ASSOC);

    // ==================== DETALLE SERVICIOS ====================
    $stmtDetalleServ = $pdo->prepare("
        SELECT s.codigo_serv, s.nombre_serv, 
               SUM(fs.cant_vent_serv) AS cantidad,
               SUM(fs.precio_unit_serv * fs.cant_vent_serv) AS total
        FROM detalle_fact_serv fs
        INNER JOIN servicio s ON fs.rela_servicio = s.id_servicio
        INNER JOIN factura f ON fs.rela_factura = f.id_factura
        WHERE f.fecha_fact BETWEEN :inicio AND :fin
        GROUP BY s.id_servicio
        ORDER BY total DESC
    ");
    $stmtDetalleServ->execute($params);
    $servicios = $stmtDetalleServ->fetchAll(PDO::FETCH_ASSOC);

    // ==================== GANANCIAS POR DIA ====================
    $stmtGananciasDia = $pdo->prepare("
        SELECT DATE(f.fecha_fact) AS fecha,
               COALESCE(SUM((df.precio_unit_product - p.precio_costo_produc) * df.cant_venta_product),0) AS productos,
               COALESCE(SUM(fs.precio_unit_serv * fs.cant_vent_serv),0) AS servicios
        FROM factura f
        LEFT JOIN detalle_factura df ON df.rela_factura = f.id_factura
        LEFT JOIN producto p ON df.rela_producto = p.id_producto
        LEFT JOIN detalle_fact_serv fs ON fs.rela_factura = f.id_factura
        WHERE f.fecha_fact BETWEEN :inicio AND :fin
        GROUP BY DATE(f.fecha_fact)
        ORDER BY fecha ASC
    ");
    $stmtGananciasDia->execute($params);
    $gananciasRaw = $stmtGananciasDia->fetchAll(PDO::FETCH_ASSOC);

    $fechas = [];
    $productosDiarios = [];
    $serviciosDiarios = [];

    foreach ($gananciasRaw as $g) {
        $fechas[] = $g['fecha'];
        $productosDiarios[] = $g['productos'];
        $serviciosDiarios[] = $g['servicios'];
    }

    // ==================== RESPUESTA ====================
    echo json_encode([
        'error' => false,
        'totales' => array_merge($totales, [
            'ganancia_neta_productos' => $gananciaProductos,
            'ganancia_servicios' => $gananciaServicios,
            'ganancia_total' => $gananciaTotal,
            'gastos' => $gastosMensual
        ]),
        'productos' => $productos,
        'servicios' => $servicios,
        'ganancias' => [
            'fechas' => $fechas,
            'productos' => $productosDiarios,
            'servicios' => $serviciosDiarios
        ],
        'inicio' => $inicio,
        'fin' => $fin
    ]);

} catch (Exception $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}
