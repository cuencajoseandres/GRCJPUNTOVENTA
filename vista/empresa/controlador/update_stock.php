<?php
require('conexion.php');

header('Content-Type: application/json');

$id = $_POST['id_producto'] ?? null;
$cantidad = $_POST['cantidad'] ?? null;

if(!$id || !$cantidad){
    echo json_encode(['error'=>true, 'mensaje'=>'Datos incompletos']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE producto SET cant_product = cant_product + :cantidad WHERE id_producto = :id");
    $stmt->execute([':cantidad'=>$cantidad, ':id'=>$id]);

    echo json_encode(['error'=>false, 'mensaje'=>'Stock actualizado correctamente']);
} catch(PDOException $e){
    echo json_encode(['error'=>true, 'mensaje'=>'Error: '.$e->getMessage()]);
}
?>