<?php
require_once('../conexion.php');
session_start();

header('Content-Type: application/json; charset=UTF-8');

try {

   if (
    empty($_POST['nombre_pers']) ||
    empty($_POST['apellido_pers']) ||
    empty($_POST['dni_pers']) ||
    empty($_POST['num_tel']) ||
    empty($_POST['rela_tip_tel']) ||
    empty($_POST['tipo_cliente'])
) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Completa todos los campos obligatorios.'
    ]);
    exit;
}


    // ---- DATOS PERSONA ----
    $nombre  = trim($_POST['nombre_pers']);
    $apellido = trim($_POST['apellido_pers']);
    $dni = trim($_POST['dni_pers']);
    $correo = trim($_POST['correo_pers']) ?: null;
    $tipoCliente = (int) $_POST['tipo_cliente'];

    // ---- DATOS TELÉFONO ----
    $telefono = trim($_POST['num_tel']);
    $tipoTel = trim($_POST['rela_tip_tel']);

    // ---- USUARIO DE SESIÓN ----
    $idUser = $_SESSION['usuario']['id_usuario'] ?? null;

    // ---- INICIO TRANSACCIÓN ----
    $pdo->beginTransaction();

    // 1️⃣ INSERT EN PERSONA
    $sqlPersona = $pdo->prepare("
        INSERT INTO persona (nombre_pers, apellido_pers, dni_pers, correo_pers)
        VALUES (?, ?, ?, ?)
    ");
    $sqlPersona->execute([$nombre, $apellido, $dni, $correo]);

    $idPersona = $pdo->lastInsertId();


    // 2️⃣ INSERT EN TELEFONO
    $sqlTel = $pdo->prepare("
        INSERT INTO telefono (num_tel, rela_tip_tel, rela_persona)
        VALUES (?, ?, ?)
    ");
    $sqlTel->execute([$telefono, $tipoTel, $idPersona]);


    // 3️⃣ INSERT EN CLIENTES
  $sqlCliente = $pdo->prepare("
    INSERT INTO clientes (fecha_registro, rela_user, rela_persona, rela_tipo_cliente)
    VALUES (NOW(), ?, ?, ?)
");
$sqlCliente->execute([$idUser, $idPersona, $tipoCliente]);


    // CONFIRMAMOS TODO
    $pdo->commit();

    echo json_encode([
        'error' => false,
        'mensaje' => 'Cliente registrado correctamente.'
    ]);
    exit;

} catch (PDOException $e) {

    // Revertimos si hay error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo json_encode([
        'error' => true,
        'mensaje' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
    exit;
}





?>
