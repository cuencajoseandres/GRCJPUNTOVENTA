<?php
require('../conexion.php');
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if (!$id) {
    echo json_encode(['error' => true, 'mensaje' => 'ID de producto no proporcionado']);
    exit;
}

try {
    // 1️⃣ Verificar si el producto está vinculado a alguna venta o detalle
    $verificar = $pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM detalle_factura 
        WHERE rela_producto = ?
    ");
    $verificar->execute([$id]);
    $relacion = $verificar->fetch(PDO::FETCH_ASSOC);

    if ($relacion && $relacion['total'] > 0) {
        echo json_encode([
            'error' => true,
            'mensaje' => '⚠️ No se puede eliminar este producto porque está vinculado a una o más ventas.'
        ]);
        exit;
    }

    // 2️⃣ Eliminar producto si no tiene vínculos
    $stmt = $pdo->prepare("DELETE FROM producto WHERE id_producto = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['error' => false, 'mensaje' => '✅ Producto eliminado correctamente']);
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'No se encontró el producto o ya fue eliminado']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => true, 'mensaje' => 'Error en la BD: ' . $e->getMessage()]);
}
?>