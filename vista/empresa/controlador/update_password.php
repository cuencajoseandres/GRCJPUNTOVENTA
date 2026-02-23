<?php
session_start();
require_once('conexion.php');

header('Content-Type: application/json; charset=utf-8');

// 🔐 Validar sesión
if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Usuario no logueado.'
    ]);
    exit;
}

try {

    // ✅ ID SOLO DESDE SESIÓN
    $id = (int) $_SESSION['usuario']['id_usuario'];

    $actual    = trim($_POST['password_actual'] ?? '');
    $nueva     = trim($_POST['password_nueva'] ?? '');
    $confirmar = trim($_POST['password_confirmar'] ?? '');

    if ($actual === '' || $nueva === '' || $confirmar === '') {
        echo json_encode([
            'error' => true,
            'mensaje' => 'Completa todos los campos.'
        ]);
        exit;
    }

    if ($nueva !== $confirmar) {
        echo json_encode([
            'error' => true,
            'mensaje' => 'Las contraseñas no coinciden.'
        ]);
        exit;
    }

    // 🔍 Obtener contraseña actual
    $stmt = $pdo->prepare("
        SELECT contrasenia_user 
        FROM usuario 
        WHERE id_usuario = ?
        LIMIT 1
    ");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'error' => true,
            'mensaje' => 'Usuario no encontrado.'
        ]);
        exit;
    }

    $passwordBD = $user['contrasenia_user'];
    $passwordOK = false;

    // ✅ HASH
    if (password_verify($actual, $passwordBD)) {
        $passwordOK = true;
    }

    // ✅ TEXTO PLANO (compatibilidad)
    if (!$passwordOK && $actual === $passwordBD) {
        $passwordOK = true;
    }

    if (!$passwordOK) {
        echo json_encode([
            'error' => true,
            'mensaje' => 'La contraseña actual es incorrecta.'
        ]);
        exit;
    }

    // 🔐 Hashear nueva contraseña
    $hashNueva = password_hash($nueva, PASSWORD_DEFAULT);

    $update = $pdo->prepare("
        UPDATE usuario 
        SET contrasenia_user = ?
        WHERE id_usuario = ?
    ");
    $update->execute([$hashNueva, $id]);

    echo json_encode([
        'error' => false,
        'mensaje' => 'Contraseña actualizada correctamente.'
    ]);
    exit;

} catch (Throwable $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error interno del servidor.'
    ]);
    exit;
}
