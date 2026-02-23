<?php
require('conexion.php');
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (empty($id)) {
    echo json_encode(['error' => true, 'mensaje' => 'ID de producto no proporcionado']);
    exit;
}

try {
    // Trae datos del producto con su estado, categoría y proveedor (si querés mostrar nombres)
    $sql = "SELECT 
                p.id_producto,
                p.nombre_product,
                p.descri_product,
                p.cod_product,
                p.precio_costo_produc,
                p.precio_publico_product,
                p.precio_gremio_product,
                p.precio_mayorista_product,
                p.cant_product,
                p.fecha_product,
                p.rela_categoria,
                p.rela_proveedor,
                p.rela_estado,
                e.descri_prod_est AS estado_nombre
            FROM producto p
            LEFT JOIN estado_producto e ON p.rela_estado = e.id_prod_estado
            WHERE p.id_producto = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        echo json_encode(['error' => false, 'data' => $data]);
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'Producto no encontrado']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => true, 'mensaje' => 'Error en BD: ' . $e->getMessage()]);
}
?>
