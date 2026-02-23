<?php
ob_start();

// ==================== INICIAR SESIÓN SEGURA ====================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== PÁGINAS PÚBLICAS ====================
$publicas = ['index.php', 'login.php', 'solicitar-cuenta.php'];

// Nombre de la página actual
$pagina_actual = strtolower(basename($_SERVER['PHP_SELF']));

// Si la página es pública NO validar sesión
if (in_array($pagina_actual, $publicas)) {
    return;
}

// ==================== REGENERAR ID (evitar fijación) ====================
if (!isset($_SESSION['regenerado'])) {
    session_regenerate_id(true);
    $_SESSION['regenerado'] = true;
}

// ==================== VALIDAR INACTIVIDAD ====================
$inactividad = 600; // 10 minutos
$ruta_login = "/PROYECTOS/producto_servicio_ecommerce/index.php"; // ⚠️ AJUSTAR A TU RUTA REAL

if (isset($_SESSION['ultimo_movimiento'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_movimiento'];

    if ($tiempo_inactivo > $inactividad) {
        session_unset();
        session_destroy();

        // Si es AJAX, responder JSON y no redirigir
        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            echo json_encode(["status" => "expired"]);
            exit;
        }

        header("Location: $ruta_login?mensaje=sesion_expirada");
        exit;
    }
}
$_SESSION['ultimo_movimiento'] = time();

// ==================== VALIDAR SESIÓN ACTIVA ====================
if (!isset($_SESSION['usuario']['id_usuario'])) {

    // Si viene desde AJAX no enviar header, enviar JSON
    if (
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        echo json_encode(["status" => "no_session"]);
        exit;
    }

    header("Location: $ruta_login");
    exit;
}

// ==================== DATOS DEL USUARIO ====================
$user       = $_SESSION['usuario'];
$id_user    = $user['id_usuario'] ?? null;
$usuario    = $user['usuario_user'] ?? null;
$correo     = $user['correo_user'] ?? null;
$nombre_tipo_user = $user['nombre_tipo_user'] ?? null;
$tipo_usuario     = (int) $_SESSION['usuario']['tipo_usuario'];

// Datos adicionales
$empresa    = "Empresa";
$user_name  = $usuario;


