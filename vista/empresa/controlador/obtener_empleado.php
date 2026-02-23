<?php
require('conexion.php');
header('Content-Type: application/json');

$id = $_GET['id_empleado'] ?? null;
if(!$id){
    echo json_encode(['error'=>true,'mensaje'=>'ID no proporcionado']);
    exit;
}

$stmt = $pdo->prepare("
    SELECT e.id_empleado, p.id_persona, p.nombre_pers, p.apellido_pers, p.dni_pers, p.correo_pers, 
           e.rela_rol_empleado,
           t.num_tel, t.rela_tip_tel
    FROM empleado e
    INNER JOIN persona p ON e.rela_pers = p.id_persona
    LEFT JOIN telefono t ON t.rela_persona = p.id_persona
    WHERE e.id_empleado = ?
");
$stmt->execute([$id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if($empleado){
    echo json_encode(['error'=>false,'empleado'=>$empleado]);
}else{
    echo json_encode(['error'=>true,'mensaje'=>'Empleado no encontrado']);
}

?>