<?php
require('../conexion.php');
header('Content-Type: application/json; charset=utf-8');

try {
    $sql = "SELECT 
                g.id_gasto,
                g.nombre_gasto,
                g.descripcion,
                g.monto,
                g.fecha,
                tg.descri_tipo_gasto AS tipo_gasto,
                mp.descri_metodo_pago AS metodo_pago,
                g.observaciones
            FROM gasto g
            LEFT JOIN tipo_gasto tg ON g.rela_tipo_gasto = tg.id_tipo_gasto
            LEFT JOIN metodo_pago mp ON g.rela_metodo_pago = mp.id_metodo_pago
            ORDER BY g.fecha DESC";

    $stmt = $pdo->query($sql);
    $data = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['acciones'] = '
            <button class="btn btn-danger btn-sm eliminarGasto" data-id="'.$row['id_gasto'].'">
                <i class="fas fa-trash"></i>
            </button>';
        $data[] = $row;
    }

    echo json_encode(['data' => $data]);
} catch (Exception $e) {
    echo json_encode(['data' => [], 'error' => $e->getMessage()]);
}
?>
