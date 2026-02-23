<?php
session_start();
require('../../validacion_login/conexion.php');

/* ===============================
   CONFIGURACIÓN
================================ */
$inactividad = 600; // 10 minutos

/* ===============================
   VALIDAR INACTIVIDAD
================================ */
if (isset($_SESSION['ultimo_movimiento'])) {
    $tiempo_inactivo = time() - $_SESSION['ultimo_movimiento'];

    if ($tiempo_inactivo > $inactividad) {
        session_unset();
        session_destroy();
        header("Location: ../../index.php?mensaje=sesion_expirada");
        exit;
    }
}

// Actualizar tiempo
$_SESSION['ultimo_movimiento'] = time();

/* ===============================
   VALIDAR SESIÓN ACTIVA
================================ */
if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    exit;
}

/* ===============================
   DATOS DEL USUARIO
================================ */
$id_user          = $_SESSION['usuario']['id_usuario'];
$usuario          = $_SESSION['usuario']['usuario_user'];
$correo           = $_SESSION['usuario']['correo_user'];
$contras           = $_SESSION['usuario']['contra_user'];
$tipo_usuario     = (int) $_SESSION['usuario']['tipo_usuario'];
$nombre_tipo_user = $_SESSION['usuario']['nombre_tipo_user'] ?? '';

/* ===============================
   CONTROL DE ACCESO POR ROL
================================ */
/*
  1 = ADMINISTRADOR
  2 = EMPRESA
  3 = EMPLEADO
  4 = CLIENTE
*/

switch ($tipo_usuario) {

    case 1: // ADMIN
    case 2: // EMPRESA
    case 3: // EMPLEADO
        // ✔ Acceso permitido al panel empresa
        $empresa = "Empresa";
        break;

    case 4: // CLIENTE
        // ❌ Cliente NO entra al panel empresa
        header('Location: ../../index.php');
        exit;

    default:
        // Rol inválido
        session_unset();
        session_destroy();
        header('Location: ../../index.php');
        exit;
}
