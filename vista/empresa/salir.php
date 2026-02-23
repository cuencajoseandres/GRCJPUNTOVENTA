<?php
session_start(); // Siempre iniciar la sesión primero

// 1. Vaciar todas las variables de sesión
$_SESSION = [];

// 2. Borrar la cookie de sesión del navegador (opcional pero recomendado)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// 3. Destruir la sesión en el servidor
session_destroy();

// 4. Redirigir al login
header('Location: ../../index.php');
exit;
