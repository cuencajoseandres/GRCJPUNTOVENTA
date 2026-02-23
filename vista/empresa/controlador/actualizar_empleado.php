<?php
require('conexion.php');
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(['error'=>true,'mensaje'=>'Usuario no logueado']);
    exit;
}

$id_empleado = $_POST['id_empleado'] ?? '';
$id_persona = $_POST['id_persona'] ?? '';
$nombre = $_POST['nombre_pers'] ?? '';
$apellido = $_POST['apellido_pers'] ?? '';
$dni = $_POST['dni_pers'] ?? '';
$correo = $_POST['correo_pers'] ?? '';
$rol = $_POST['rela_rol_empleado'] ?? '';
$num_tel = $_POST['num_tel'] ?? '';
$tip_tel = $_POST['rela_tip_tel'] ?? '';

// Verificar campos obligatorios (sin teléfono)
if(empty($id_empleado) || empty($id_persona) || empty($nombre) || empty($apellido) || empty($dni) || empty($rol)){
    echo json_encode(['error'=>true,'mensaje'=>'Los campos obligatorios deben completarse']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Actualizar persona
    $stmt = $pdo->prepare("UPDATE persona SET nombre_pers=?, apellido_pers=?, dni_pers=?, correo_pers=? WHERE id_persona=?");
    $stmt->execute([$nombre, $apellido, $dni, $correo, $id_persona]);

    // Actualizar empleado
    $stmt = $pdo->prepare("UPDATE empleado SET rela_rol_empleado=? WHERE id_empleado=?");
    $stmt->execute([$rol, $id_empleado]);

    // Actualizar teléfono solo si hay datos
    if(!empty($num_tel) && !empty($tip_tel)){
        $stmt = $pdo->prepare("UPDATE telefono SET num_tel=?, rela_tip_tel=? WHERE rela_persona=?");
        $stmt->execute([$num_tel, $tip_tel, $id_persona]);
    }

    $pdo->commit();
    echo json_encode(['error'=>false,'mensaje'=>'Empleado actualizado correctamente']);
} catch(Exception $e){
    $pdo->rollBack();
    echo json_encode(['error'=>true,'mensaje'=>'Error: '.$e->getMessage()]);
}


?>