<?php
session_start();
require('conexion.php');
header('Content-Type: application/json; charset=utf-8');

// Verificar sesión
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'No hay sesión activa.']);
    exit;
}

// Capturar datos POST
$nombre       = trim($_POST['nombre_pers'] ?? '');
$apellido     = trim($_POST['apellido_pers'] ?? '');
$dni          = trim($_POST['dni_pers'] ?? '');
$telefono     = trim($_POST['num_tel'] ?? '');
$tipo_tel     = trim($_POST['rela_tip_tel'] ?? '');
$tipo_cliente = trim($_POST['tipo_cliente'] ?? '');


// Validar campos
if (
    $nombre === '' ||
    $apellido === '' ||
    $dni === '' ||
    $telefono === '' ||
    $tipo_tel === '' ||
    $tipo_cliente === ''
) {
    echo json_encode(['error' => true, 'mensaje' => 'Todos los campos son obligatorios.']);
    exit;
}



// Obtener ID usuario desde sesión
$id_usuario = $_SESSION['usuario']['id_usuario'];

try {

  
    // Iniciar transacción
    $pdo->beginTransaction();

    // 1️⃣ Insertar en persona
    $stmt = $pdo->prepare("
        INSERT INTO persona (nombre_pers, apellido_pers, dni_pers)
        VALUES (:nombre, :apellido, :dni)
    ");
    $stmt->execute([
        ':nombre'   => $nombre,
        ':apellido' => $apellido,
        ':dni'      => $dni
    ]);
    $id_persona = $pdo->lastInsertId();

    // 2️⃣ Insertar en clientes (CORREGIDO)
    $stmt = $pdo->prepare("
        INSERT INTO clientes 
        (fecha_registro, rela_user, rela_persona, rela_tipo_cliente)
        VALUES (NOW(), :id_usuario, :id_persona, :tipo_cliente)
    ");
    $stmt->execute([
        ':id_usuario'   => $id_usuario,
        ':id_persona'   => $id_persona,
        ':tipo_cliente' => $tipo_cliente
    ]);
    $id_cliente = $pdo->lastInsertId();

    // 3️⃣ Insertar teléfono
    $stmt = $pdo->prepare("
        INSERT INTO telefono (num_tel, rela_tip_tel, rela_persona)
        VALUES (:telefono, :tipo_tel, :id_persona)
    ");
    $stmt->execute([
        ':telefono'   => $telefono,
        ':tipo_tel'   => $tipo_tel,
        ':id_persona' => $id_persona
    ]);

    // Confirmar transacción
    $pdo->commit();

    echo json_encode([
        'error' => false,
        'mensaje' => 'Cliente registrado correctamente.',
        'id_cliente' => $id_cliente,
        'nombreCompleto' => $nombre . ' ' . $apellido
    ]);
    exit;

} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error al registrar cliente: ' . $e->getMessage()
    ]);
    exit;
}
?>
