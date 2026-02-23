<?php
require_once '../conexion.php';
header('Content-Type: application/json');

$id = $_POST['id_servicio'] ?? null;

if (!$id) {
    echo json_encode(['error' => true, 'mensaje' => 'ID no válido']);
    exit;
}

try {
    // Verificar si el servicio está vinculado a una venta o detalle
    $verificar = $pdo->prepare("
        SELECT COUNT(*) AS total 
        FROM detalle_fact_serv 
        WHERE rela_servicio = ?
    ");
    $verificar->execute([$id]);
    $relacion = $verificar->fetch(PDO::FETCH_ASSOC);

    if ($relacion && $relacion['total'] > 0) {
        echo json_encode([
            'error' => true,
            'mensaje' => '⚠️ No se puede eliminar este servicio porque está vinculado a una o más ventas.'
        ]);
        exit;
    }

    // Si no está vinculado, proceder a eliminar
    $stmt = $pdo->prepare("DELETE FROM servicio WHERE id_servicio = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['error' => false, 'mensaje' => '✅ Servicio eliminado correctamente']);
    } else {
        echo json_encode(['error' => true, 'mensaje' => 'No se encontró el servicio o ya fue eliminado']);
    }

} catch (Exception $e) {
    echo json_encode(['error' => true, 'mensaje' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>
