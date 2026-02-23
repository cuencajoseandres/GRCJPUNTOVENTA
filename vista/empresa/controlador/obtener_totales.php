<?php
require('conexion.php');
header('Content-Type: application/json; charset=utf-8');

try {
    // Total de productos
    $stmtProd = $pdo->query("SELECT SUM(cant_product) AS total_productos FROM producto");
    $totalProductos = $stmtProd->fetchColumn() ?? 0;

    // Total en costo
    $stmtCosto = $pdo->query("SELECT SUM(cant_product * precio_costo_produc) AS total_costo FROM producto");
    $totalCosto = $stmtCosto->fetchColumn() ?? 0;

    // Total en precio gremio (precio para técnicos)
    $stmtGremio = $pdo->query("SELECT SUM(cant_product * precio_gremio_product) AS total_gremio FROM producto");
    $totalGremio = $stmtGremio->fetchColumn() ?? 0;

    // Total en precio público (precio para clientes)
    $stmtPublico = $pdo->query("SELECT SUM(cant_product * precio_publico_product) AS total_publico FROM producto");
    $totalPublico = $stmtPublico->fetchColumn() ?? 0;

    echo json_encode([
        'error' => false,
        'total_productos' => $totalProductos,
        'total_gremio' => $totalGremio,
        'total_costo' => $totalCosto,
        'total_publico' => $totalPublico
    ]);

} catch (PDOException $e) {
    echo json_encode(['error' => true, 'mensaje' => $e->getMessage()]);
}
?>
