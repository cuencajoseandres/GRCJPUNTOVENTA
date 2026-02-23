<?php
session_start();
require_once('../validacion_login/conexion.php');

header('Content-Type: application/json; charset=utf-8');

try {

    // =========================
    // DATOS DEL FORMULARIO
    // =========================
    $usu  = trim($_POST['usuario'] ?? '');
    $pass = trim($_POST['contrasena'] ?? '');

    if ($usu === '' || $pass === '') {
        echo json_encode([
            'error' => true,
            'mensaje' => 'Por favor completa todos los campos.'
        ]);
        exit;
    }

    // =========================
    // CONSULTA USUARIO
    // =========================
    $sql = "
        SELECT 
            u.id_usuario,
            u.usuario_user,
            u.contrasenia_user,
            u.correo_user,
            u.rela_tip_user,
            t.id_tipo_usuario,
            t.descri_tip_user
        FROM usuario u
        INNER JOIN tipo_usuario t ON u.rela_tip_user = t.id_tipo_usuario
        WHERE u.usuario_user = :usuario
           OR u.correo_user = :correo
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':usuario' => $usu,
        ':correo'  => $usu
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode([
            'error' => true,
            'mensaje' => 'Usuario o contraseña incorrectos.'
        ]);
        exit;
    }

    // =========================
    // VERIFICACIÓN CONTRASEÑA
    // =========================

    $passwordBD = $user['contrasenia_user'];
    $loginOK = false;

    // 1️⃣ Si es HASH
    if (password_verify($pass, $passwordBD)) {
        $loginOK = true;
    }

    // 2️⃣ Si es TEXTO PLANO (compatibilidad)
    if (!$loginOK && $pass === $passwordBD) {
        $loginOK = true;

        // 🔥 OPCIONAL (RECOMENDADO):
        // Re-hashear automáticamente la contraseña
        $nuevoHash = password_hash($pass, PASSWORD_DEFAULT);

        $update = $pdo->prepare("
            UPDATE usuario 
            SET contrasenia_user = ?
            WHERE id_usuario = ?
        ");
        $update->execute([$nuevoHash, $user['id_usuario']]);
    }

    if (!$loginOK) {
        echo json_encode([
            'error' => true,
            'mensaje' => 'Usuario o contraseña incorrectos.'
        ]);
        exit;
    }

    // =========================
    // SESIÓN
    // =========================
    $_SESSION['usuario'] = [
        'id_usuario'        => $user['id_usuario'],
        'usuario_user'      => $user['usuario_user'],
        'correo_user'       => $user['correo_user'],
        'tipo_usuario'      => $user['id_tipo_usuario'],
        'nombre_tipo_user'  => $user['descri_tip_user'],
        'contra_user'  => $user['contrasenia_user']

    ];
  echo json_encode([
    'error' => false,
    'mensaje' => 'Inicio de sesión correcto.',
    'tipo_usuario' => $user['id_tipo_usuario'],
    'usuario' => $user['usuario_user'],
    'rol' => $user['descri_tip_user']
]);
    exit;
} catch (PDOException $e) {
    echo json_encode([
        'error' => true,
        'mensaje' => 'Error interno del servidor.'
    ]);
    exit;
}
