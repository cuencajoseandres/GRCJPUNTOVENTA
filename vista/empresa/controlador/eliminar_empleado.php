<?php
require('conexion.php');

$id = $_POST['id_empleado'];

try {
    $pdo->beginTransaction();

    // Primero eliminar empleado
    $stmt = $pdo->prepare("DELETE FROM empleado WHERE id_empleado = ?");
    $stmt->execute([$id]);

    // Opcional: eliminar persona asociada
    // $stmt = $pdo->prepare("DELETE FROM persona WHERE id_persona = ?");
    // $stmt->execute([$id_persona]);

    $pdo->commit();
    echo json_encode(['error'=>false,'mensaje'=>'Empleado eliminado correctamente']);
} catch(Exception $e){
    $pdo->rollBack();
    echo json_encode(['error'=>true,'mensaje'=>'Error: '.$e->getMessage()]);
}
?>
