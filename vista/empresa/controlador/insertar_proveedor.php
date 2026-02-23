<?php
require('conexion.php');
session_start();

header('Content-Type: application/json');

// Verificar sesión
if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(['error' => true, 'mensaje' => 'Usuario no logueado']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];

try {
    // Validar datos
    if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['dni'])) {
        echo json_encode(['error' => true, 'mensaje' => 'Faltan datos obligatorios']);
        exit;
    }

    $nombre   = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $dni      = $_POST['dni'];
    $correo   = $_POST['correo'] ?? '';

    // =============================
    // 1. Insertar en la tabla persona
    // =============================
    $sql_persona = "INSERT INTO persona (nombre_pers, apellido_pers, dni_pers, correo_pers)
                    VALUES (:nombre, :apellido, :dni, :correo)";
    $stmt = $pdo->prepare($sql_persona);
    $stmt->execute([
        ':nombre' => $nombre,
        ':apellido' => $apellido,
        ':dni' => $dni,
        ':correo' => $correo
    ]);

    // Obtener el ID de persona recién insertado
    $id_persona = $pdo->lastInsertId();

    // =============================
    // 2. Insertar en la tabla proveedor
    // =============================
    $sql_proveedor = "INSERT INTO proveedor (rela_pers, rela_user)
                      VALUES (:rela_pers, :rela_user)";
    $stmt = $pdo->prepare($sql_proveedor);
    $stmt->execute([
        ':rela_pers' => $id_persona,
        ':rela_user' => $id_usuario
    ]);

    echo json_encode([
        'error' => false,
        'mensaje' => 'Proveedor agregado correctamente'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error SQL: ' . $e->getMessage()
    ]);
}
