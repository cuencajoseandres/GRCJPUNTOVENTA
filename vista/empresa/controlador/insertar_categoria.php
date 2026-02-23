<?php
require("conexion.php");
header('Content-Type: application/json; charset=utf-8');

try {
    $nombre = trim($_POST['nombre_categoria'] ?? '');
    $descripcion = trim($_POST['descripcion_categoria'] ?? '');

    // Validación básica
    if ($nombre === '') {
        echo json_encode(['error' => true, 'mensaje' => 'El nombre de la categoría es obligatorio.']);
        exit;
    }

    // Insertar categoría
    $sql = "INSERT INTO categoria (descri_cat) VALUES (:nombre)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['nombre' => $nombre]);

    echo json_encode(['error' => false, 'mensaje' => 'Categoría agregada correctamente.']);
    exit;

} catch (PDOException $e) {
    echo json_encode(['error' => true, 'mensaje' => 'Error en la base de datos: ' . $e->getMessage()]);
    exit;
}
?>