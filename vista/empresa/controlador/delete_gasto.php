<?php
require('../conexion.php');
header('Content-Type: application/json; charset=utf-8');

try {
    if (empty($_POST['id'])) {
        throw new Exception("ID no recibido");
    }

    $stmt = $pdo->prepare("DELETE FROM gasto WHERE id_gasto = ?");
    $stmt->execute([$_POST['id']]);

    echo json_encode(['error' => false, 'mensaje' => 'Gasto eliminado correctamente.']);
} catch (Exception $e) {
    echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
}
?>
