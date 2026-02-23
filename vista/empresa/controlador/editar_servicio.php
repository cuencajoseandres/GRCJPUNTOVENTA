<?php
require_once '../conexion.php';

$id = $_POST['id_servicio'] ?? null;
$nombre = $_POST['nombre_serv'] ?? '';
$descri = $_POST['descri_serv'] ?? '';
$precio = $_POST['precio_serv'] ?? '';

if (!$id || !$nombre) {
    echo json_encode(['error' => true, 'mensaje' => 'Datos incompletos']);
    exit;
}

$stmt = $pdo->prepare("UPDATE servicio SET nombre_serv = ?, descri_serv = ?, precio_serv = ? WHERE id_servicio = ?");
$ok = $stmt->execute([$nombre, $descri, $precio, $id]);

if ($ok) {
    echo json_encode(['error' => false, 'mensaje' => 'Servicio actualizado correctamente']);
} else {
    echo json_encode(['error' => true, 'mensaje' => 'Error al actualizar el servicio']);
}
?>