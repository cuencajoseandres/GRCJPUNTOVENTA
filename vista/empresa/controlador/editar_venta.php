<?php
session_start();
require('conexion.php');
header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Sesión no válida']);
    exit;
}

if (!isset($_POST['data'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Datos no recibidos']);
    exit;
}

$data = json_decode($_POST['data'], true);

if (
    empty($data['id_factura']) ||
    empty($data['empleado']) ||
    empty($data['cliente']) ||
    empty($data['metodo_pago']) ||
    empty($data['detalles'])
) {
    echo json_encode(['error' => true, 'mensaje' => 'Datos obligatorios incompletos']);
    exit;
}

$idFactura = (int)$data['id_factura'];

try {
    $pdo->beginTransaction();

    /* =====================================
       1️⃣ TRAER DETALLES ACTUALES (ROLLBACK)
    ===================================== */
    $actuales = [];

    $qProd = $pdo->prepare("
        SELECT id_detalle_fact AS id_detalle, rela_producto AS id_item, cant_venta_product AS cantidad
        FROM detalle_factura
        WHERE rela_factura = ?
    ");
    $qProd->execute([$idFactura]);

    while ($r = $qProd->fetch(PDO::FETCH_ASSOC)) {
        $actuales['producto'][$r['id_detalle']] = $r;
    }

    $qServ = $pdo->prepare("
        SELECT id_fact_serv AS id_detalle, rela_servicio AS id_item, cant_vent_serv AS cantidad
        FROM detalle_fact_serv
        WHERE rela_factura = ?
    ");
    $qServ->execute([$idFactura]);

    while ($r = $qServ->fetch(PDO::FETCH_ASSOC)) {
        $actuales['servicio'][$r['id_detalle']] = $r;
    }

    /* =====================================
       2️⃣ VALIDAR STOCK REAL (EVITA VENTAS)
    ===================================== */
    foreach ($data['detalles'] as $d) {

        if ($d['tipo'] === 'producto') {

            $stmt = $pdo->prepare("
                SELECT cant_product 
                FROM producto 
                WHERE id_producto = ?
            ");
            $stmt->execute([$d['id']]);
            $p = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$p) {
                throw new Exception("Producto no encontrado");
            }

            $cantidadAnterior = 0;
            if ($d['id_detalle'] != 0 && isset($actuales['producto'][$d['id_detalle']])) {
                $cantidadAnterior = $actuales['producto'][$d['id_detalle']]['cantidad'];
            }

            $stockDisponible = $p['cant_product'] + $cantidadAnterior;

            if ($d['cantidad'] > $stockDisponible) {
                throw new Exception("Stock insuficiente para producto ID {$d['id']}. Disponible: {$stockDisponible}");
            }
        }
    }

    /* =====================================
       3️⃣ ACTUALIZAR FACTURA
    ===================================== */
    $stmtFact = $pdo->prepare("
        UPDATE factura 
        SET rela_empleado = ?, rela_cliente = ?, rela_metodo_pago = ?
        WHERE id_factura = ?
    ");
    $stmtFact->execute([
        $data['empleado'],
        $data['cliente'],
        $data['metodo_pago'],
        $idFactura
    ]);

    /* =====================================
       4️⃣ PROCESAR DETALLES
    ===================================== */
    $idsProcesados = ['producto'=>[], 'servicio'=>[]];

    foreach ($data['detalles'] as $d) {

        $subtotal = $d['cantidad'] * $d['precio'];

        /* ---------- PRODUCTOS ---------- */
        if ($d['tipo'] === 'producto') {

            if ($d['id_detalle'] == 0) {

                // INSERT
                $stmt = $pdo->prepare("
                    INSERT INTO detalle_factura 
                    (cant_venta_product, precio_unit_product, subtotal_product, rela_producto, rela_factura)
                    VALUES (?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $d['cantidad'],
                    $d['precio'],
                    $subtotal,
                    $d['id'],
                    $idFactura
                ]);

                // DESCONTAR STOCK
                $pdo->prepare("
                    UPDATE producto SET cant_product = cant_product - ?
                    WHERE id_producto = ?
                ")->execute([$d['cantidad'], $d['id']]);

            } else {

                // UPDATE
                $anterior = $actuales['producto'][$d['id_detalle']]['cantidad'];

                $pdo->prepare("
                    UPDATE detalle_factura
                    SET cant_venta_product = ?, precio_unit_product = ?, subtotal_product = ?
                    WHERE id_detalle_fact = ?
                ")->execute([
                    $d['cantidad'],
                    $d['precio'],
                    $subtotal,
                    $d['id_detalle']
                ]);

                // AJUSTE STOCK
                $diferencia = $d['cantidad'] - $anterior;

                if ($diferencia != 0) {
                    $pdo->prepare("
                        UPDATE producto SET cant_product = cant_product - ?
                        WHERE id_producto = ?
                    ")->execute([$diferencia, $d['id']]);
                }
            }

            $idsProcesados['producto'][] = $d['id_detalle'];
        }

        /* ---------- SERVICIOS ---------- */
        if ($d['tipo'] === 'servicio') {

            if ($d['id_detalle'] == 0) {

                $pdo->prepare("
                    INSERT INTO detalle_fact_serv
                    (cant_vent_serv, precio_unit_serv, subtotal_serv, rela_servicio, rela_factura)
                    VALUES (?, ?, ?, ?, ?)
                ")->execute([
                    $d['cantidad'],
                    $d['precio'],
                    $subtotal,
                    $d['id'],
                    $idFactura
                ]);

            } else {

                $pdo->prepare("
                    UPDATE detalle_fact_serv
                    SET cant_vent_serv = ?, precio_unit_serv = ?, subtotal_serv = ?
                    WHERE id_fact_serv = ?
                ")->execute([
                    $d['cantidad'],
                    $d['precio'],
                    $subtotal,
                    $d['id_detalle']
                ]);
            }

            $idsProcesados['servicio'][] = $d['id_detalle'];
        }
    }

    /* =====================================
       5️⃣ DETECTAR ELIMINADOS + ROLLBACK
    ===================================== */
    foreach ($actuales['producto'] ?? [] as $idDet => $a) {
        if (!in_array($idDet, $idsProcesados['producto'])) {

            // DEVOLVER STOCK
            $pdo->prepare("
                UPDATE producto SET cant_product = cant_product + ?
                WHERE id_producto = ?
            ")->execute([$a['cantidad'], $a['id_item']]);

            // ELIMINAR
            $pdo->prepare("
                DELETE FROM detalle_factura WHERE id_detalle_fact = ?
            ")->execute([$idDet]);
        }
    }

    foreach ($actuales['servicio'] ?? [] as $idDet => $a) {
        if (!in_array($idDet, $idsProcesados['servicio'])) {
            $pdo->prepare("
                DELETE FROM detalle_fact_serv WHERE id_fact_serv = ?
            ")->execute([$idDet]);
        }
    }

    $pdo->commit();

    echo json_encode([
        'error' => false,
        'mensaje' => 'Venta editada correctamente'
    ]);

} catch (Exception $e) {

    $pdo->rollBack();

    echo json_encode([
        'error' => true,
        'mensaje' => $e->getMessage()
    ]);
}
