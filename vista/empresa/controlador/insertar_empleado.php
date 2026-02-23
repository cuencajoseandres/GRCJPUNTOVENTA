<?php
require('conexion.php');
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(['error'=>true,'mensaje'=>'Usuario no logueado']);
    exit;
}

// DATOS PERSONA
$nombre   = trim($_POST['nombre_pers'] ?? '');
$apellido = trim($_POST['apellido_pers'] ?? '');
$dni      = trim($_POST['dni_pers'] ?? '');
$correo   = trim($_POST['correo_pers'] ?? '');

// DATOS EMPLEADO
$rol      = $_POST['rela_rol_empleado'] ?? '';
$num_tel  = $_POST['num_tel'] ?? '';
$tip_tel  = $_POST['rela_tip_tel'] ?? '';

// DATOS USUARIO
$usuario  = trim($_POST['usuario_user'] ?? '');
$password = trim($_POST['contrasenia_user'] ?? '');
$tipoUser = $_POST['rela_tip_user'] ?? '';

if (
    $nombre==='' || $apellido==='' || $dni==='' ||
    $rol==='' || $num_tel==='' || $tip_tel==='' ||
    $usuario==='' || $password==='' || $tipoUser===''
) {
    echo json_encode(['error'=>true,'mensaje'=>'Todos los campos son obligatorios']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1️⃣ PERSONA
    $stmt = $pdo->prepare("
        INSERT INTO persona (nombre_pers, apellido_pers, dni_pers, correo_pers)
        VALUES (?,?,?,?)
    ");
    $stmt->execute([$nombre, $apellido, $dni, $correo]);
    $id_persona = $pdo->lastInsertId();

    // 2️⃣ USUARIO (login)
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO usuario (usuario_user, contrasenia_user, correo_user, rela_tip_user)
        VALUES (?,?,?,?)
    ");
    $stmt->execute([$usuario, $hash, $correo, $tipoUser]);
    $id_usuario = $pdo->lastInsertId();

    // 3️⃣ EMPLEADO
    $stmt = $pdo->prepare("
        INSERT INTO empleado (rela_pers, rela_user, rela_rol_empleado, fecha_registro)
        VALUES (?,?,?,NOW())
    ");
    $stmt->execute([$id_persona, $id_usuario, $rol]);

    // 4️⃣ TELÉFONO
    $stmt = $pdo->prepare("
        INSERT INTO telefono (num_tel, rela_tip_tel, rela_persona)
        VALUES (?,?,?)
    ");
    $stmt->execute([$num_tel, $tip_tel, $id_persona]);

    $pdo->commit();

    echo json_encode([
        'error'=>false,
        'mensaje'=>'Empleado registrado correctamente'
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode([
        'error'=>true,
        'mensaje'=>'Error: '.$e->getMessage()
    ]);
}
