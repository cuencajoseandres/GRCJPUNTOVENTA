<?php
require_once '../conexion.php';

$id = $_GET['id_servicio'] ?? null;

if (!$id) {
    echo json_encode(['error' => true, 'mensaje' => 'ID no válido']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM servicio WHERE id_servicio = ?");
$stmt->execute([$id]);
$servicio = $stmt->fetch(PDO::FETCH_ASSOC);

if ($servicio) {
    echo json_encode($servicio);
} else {
    echo json_encode(['error' => true, 'mensaje' => 'Servicio no encontrado']);
}
?>